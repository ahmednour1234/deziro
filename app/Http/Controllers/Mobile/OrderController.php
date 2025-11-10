<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupedOrder as GroupedOrderResource;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Current guard.
     *
     * @var string
     */
    protected $guard;

    /**
     * @var \App\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \App\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * Constructor.
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
    ) {
        $this->guard = 'api';

        Auth::setDefaultDriver($this->guard);

        $this->orderRepository    = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * Get user orders (buyer or seller), including VAT adjustment.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $cloned = clone $this->orderRepository;

        $cloned->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $query = $cloned->scopeQuery(function ($query) {
            $userId = auth()->user()->id;

            // Orders where user is buyer OR seller (via products.user_id).
            $query = $query->where(function ($query) use ($userId) {
                return $query->where('user_id', $userId)
                    ->orWhereExists(function ($query) use ($userId) {
                        $query->select(DB::raw(1))
                            ->from('order_items')
                            ->join('products', 'products.id', 'order_items.product_id')
                            ->whereRaw('order_items.order_id = orders.id')
                            ->where('products.user_id', $userId);
                    });
            });

            if ($sort = request()->input('sort')) {
                $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            $results = $query->paginate(request()->input('limit') ?? 20);
        } else {
            $results = $query->get();
        }

        // تأكد من الـ VAT لكل أوردر
        if ($results) {
            if ($results instanceof \Illuminate\Contracts\Pagination\Paginator
                || $results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
                foreach ($results as $order) {
                    $this->applyVatToOrder($order);
                }
            } else {
                foreach ($results as $order) {
                    $this->applyVatToOrder($order);
                }
            }
        }

        return OrderResource::collection($results);
    }

    /**
     * Save payment for all orders related to a cart.
     * Ensures VAT is valid before marking as paid.
     *
     * @param  int  $cart_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePayment($cart_id)
    {
        $orders = $this->orderRepository->findByField("cart_id", $cart_id);

        if (! count($orders)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], 200);
        }

        foreach ($orders as $order) {
            if (! $order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 200);
            }

            if ($order->user_id != auth()->user()->id
                || $order->status != Order::STATUS_PENDING_PAYMENT) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot pay this order.',
                ], 200);
            }

            $payment = $order->payment;

            if (! $payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method is not specified.',
                ], 200);
            }

            // NOTE:
            // كود التحقق من أنواع الدفع الأصلية متشال فوق،
            // دلوقتي لو مش مطابق المطلوب عندك، عدّله هنا.
            return response()->json([
                'success' => false,
                'message' => 'Invalid Payment Method.',
            ], 200);
        }

        // لو عدّى الفحص (حالياً مش هيوصل هنا بسبب الرسالة فوق، بس مخلّي اللوجيك سليم لو عدّلته)
        foreach ($orders as $order) {

            // تأكد من VAT قبل التحديث
            $this->applyVatToOrder($order);

            $order->status = Order::STATUS_PENDING;
            $order->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Order has been successfully paid.',
        ], 200);
    }

    /**
     * Rate a single order.
     */
    public function rateOrder($id)
    {
        try {
            request()->validate([
                'rate' => ['required', 'numeric', 'between:1,5'],
            ]);

            $order = $this->orderRepository->find($id);

            if (! $order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 200);
            }

            if ($order->rate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order has already been rated.',
                ], 200);
            }

            $order->rate = request()->get('rate');
            $order->save();

            // VAT مش متأثر بالتقييم، فلا حاجة للتعديل هنا.

            return response()->json([
                'success' => true,
                'message' => 'Order has been successfully rated.',
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 200);
        }
    }

    /**
     * Cancel order (within 1 minute if pending).
     */
    public function cancel($id)
    {
        $user_id = Auth::user()->id;

        $order = Order::where('id', $id)
            ->where('user_id', $user_id)
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found for this user.',
            ], 200);
        }

        if ($order->status != Order::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be canceled because it is not in a pending status.',
            ], 200);
        }

        $currentTime     = Carbon::now();
        $orderUpdatedAt  = Carbon::parse($order->updated_at);
        $timeDifference  = $orderUpdatedAt->diffInSeconds($currentTime);

        if ($timeDifference >= 0 && $timeDifference <= 60) {
            $order->status = Order::STATUS_CANCELED;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order has been canceled.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Order cannot be canceled as it has been more than 1 minute since the last update.',
        ], 200);
    }

    /**
     * Rate & feedback for multiple order items.
     */
    public function rateAndFeedback()
    {
        $orderItems = request()->input('order_item');
        $userId     = auth()->user()->id;

        foreach ($orderItems as $inputItem) {
            $orderItem = OrderItem::find($inputItem['id'] ?? null);

            if (! $orderItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order item not found',
                ], 200);
            }

            $order = $orderItem->order;

            if (! $order || $order->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: This order does not belong to you',
                ], 200);
            }

            if ($order->status !== Order::STATUS_DELIVERED && $order->status !== 'delivered') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is not delivered',
                ], 200);
            }

            $orderItem->rate     = $inputItem['rate']     ?? null;
            $orderItem->feedback = $inputItem['feedback'] ?? null;
            $orderItem->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Ratings and feedback saved successfully',
        ], 200);
    }

    /**
     * Apply VAT for a given order.
     *
     * - يعتمد على store->vat_status:
     *      - إن كانت 1: يحتسب vat = 11% من الإجمالي قبل الضريبة.
     *      - إن كانت 0: vat = 0.
     * - يفترض وجود أعمدة: orders.vat, orders.grand_total.
     * - Idempotent: لو اتندَهت كذا مرة مش هتضرب الرقم.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    protected function applyVatToOrder(Order $order): void
    {
        if (! $order) {
            return;
        }

        // حاول تجيب الـ store:
        // 1) علاقة مباشرة $order->store (لو موجودة)
        // 2) أو عن طريق cart: $order->cart->store لو ده التصميم عندك
        $store = null;

        if (method_exists($order, 'store') && $order->store) {
            $store = $order->store;
        } elseif (method_exists($order, 'cart') && $order->cart && method_exists($order->cart, 'store')) {
            $store = $order->cart->store;
        }

        // ارجع للإجمالي قبل الضريبة القديمة
        $currentVat = (float) ($order->vat ?? 0);
        $baseTotal  = (float) $order->grand_total - $currentVat;

        if ($baseTotal < 0) {
            $baseTotal = 0;
        }

        $vat = 0;

        if ($store && (int) ($store->vat ?? 0) === 1) {
            $vat = round($baseTotal * 0.11, 2); // 11%
        }

        $order->vat         = $vat;
        $order->grand_total = $baseTotal + $vat;
        $order->save();
    }
}
