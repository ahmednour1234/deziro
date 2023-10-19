<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\GroupedOrder as GroupedOrderResource;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * OrderRepository object
     *
     * @var \App\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var \App\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * Controller instance
     *
     * @param \App\Repositories\OrderRepository     $orderRepository
     * @param \App\Repositories\OrderItemRepository $orderItemRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
    ) {
        $this->guard = 'api';

        Auth::setDefaultDriver($this->guard);

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * Get user order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // $pending = $this->ordersByStatus(Order::STATUS_PENDING);
        // $shipped = $this->ordersByStatus(Order::STATUS_SHIPPED);
        // $delivered = $this->ordersByStatus(Order::STATUS_DELIVERED);
        // $canceled = $this->ordersByStatus(Order::STATUS_CANCELED);

        // return response()->json([
        //     'data' => [
        //         'pending'           => $pending,
        //         'shipped'           => $shipped,
        //         'delivered'         => $delivered,
        //         'canceled'          => $canceled
        //     ]
        // ]);
        $cloned = clone $this->orderRepository;
        $cloned->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $query = $cloned->scopeQuery(function ($query) {
            $query = $query->where(function ($query) {
                return $query->where('user_id', auth()->user()->id)
                    ->orWhereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('order_items')
                            ->join('products', 'products.id', 'order_items.product_id')
                            ->whereRaw('order_items.order_id = orders.id')
                            ->where('products.user_id', auth()->user()->id);
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

        return OrderResource::collection($results);
        // return response()->json([
        //     'data' => $this->orderRepository->where('user_id', '=', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(20)->getCollection()
        // ]);
    }


    // public function getOrdersByStatus($status)
    // {
    //     if ($status == Order::STATUS_PENDING_PAYMENT)
    //         return response()->json([
    //             'data' => $this->groupedOrdersByStatus($status)
    //         ]);
    //     return response()->json([
    //         'data' => $this->ordersByStatus($status)
    //     ]);
    // }

    // public function groupedOrdersByStatus($status)
    // {
    //     $cloned = clone $this->orderRepository;
    //     $cloned->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    //     $query = $cloned->scopeQuery(function ($query) use ($status) {
    //         $query = $query->whereHas('cart');
    //         $query = $query->where('user_id', auth()->user()->id)
    //             ->where('status', $status);

    //         if ($sort = request()->input('sort')) {
    //             $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
    //         } else {
    //             $query = $query->orderBy('id', 'desc');
    //         }

    //         $query = $query->groupBy('cart_id');

    //         return $query;
    //     });

    //     if (is_null(request()->input('pagination')) || request()->input('pagination')) {
    //         $results = $query->paginate(request()->input('limit') ?? 15);
    //     } else {
    //         $results = $query->get();
    //     }

    //     return GroupedOrderResource::collection($results);
    // }

    // public function ordersByStatus($status)
    // {
    //     $cloned = clone $this->orderRepository;
    //     $cloned->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    //     $query = $cloned->scopeQuery(function ($query) use ($status) {
    //         $query = $query->where(function ($query) {
    //             return $query->where('user_id', auth()->user()->id)
    //                 ->orWhereExists(function ($query) {
    //                     $query->select(DB::raw(1))
    //                         ->from('order_items')
    //                         ->join('products', 'products.id', 'order_items.product_id')
    //                         ->whereRaw('order_items.order_id = orders.id')
    //                         ->where('products.user_id', auth()->user()->id);
    //                 });
    //         })
    //             ->where('status', $status);

    //         if ($sort = request()->input('sort')) {
    //             $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
    //         } else {
    //             $query = $query->orderBy('id', 'desc');
    //         }

    //         return $query;
    //     });

    //     if (is_null(request()->input('pagination')) || request()->input('pagination')) {
    //         $results = $query->paginate(request()->input('limit') ?? 15);
    //     } else {
    //         $results = $query->get();
    //     }

    //     return OrderResource::collection($results);
    // }

    public function savePayment($cart_id)
    {
        $orders = $this->orderRepository->findByField("cart_id", $cart_id);
        if (!count($orders))
            return response()->json([
                'success' => false,
                'message'   => 'Cart not found.',
            ],200);
        foreach ($orders as $order) {
            if (!$order)
                return response()->json([
                    'success' => false,
                    'message'   => 'Order not found.',
                ],200);

            if ($order->user_id != auth()->user()->id || $order->status != Order::STATUS_PENDING_PAYMENT)
                return response()->json([
                    'success' => false,
                    'message'   => 'You cannot pay this order.',
                ],200);
            $payment = $order->payment;
            if (!$payment)
                return response()->json([
                    'success' => false,
                    'message'   => 'Payment method is not specified.',
                ],200);

            // if ($payment->method == OrderPayment::METHOD_WHISH_MONEY) {

            //     if (!request()->hasFile('receipt'))
            //         return response()->json([
            //             'success' => false,
            //             'message'   => 'Receipt is required.',
            //         ]);
            // } else if ($payment->method == OrderPayment::METHOD_WHISH_TO_WHISH) {

            //     if (!request()->get('ltn'))
            //         return response()->json([
            //             'success' => false,
            //             'message'   => 'LTN is required.',
            //         ]);
            // } else
            {
                return response()->json([
                    'success' => false,
                    'message'   => 'Invalid Payment Method.',
                ],200);
            }
        }

        foreach ($orders as $order) {

            $payment = $order->payment;

            // if ($payment->method == OrderPayment::METHOD_WHISH_MONEY) {

            //     $payment->receipt = request()->file('receipt')->store('payment/' . $order->id);
            //     $payment->save();
            // } else if ($payment->method == OrderPayment::METHOD_WHISH_TO_WHISH) {

            //     $payment->ltn = request()->get('ltn');
            //     $payment->save();
            // }

            $order->status = Order::STATUS_PENDING;
            $order->save();
        }

        return response()->json([
            'success' => true,
            'message'   => 'Order has been successfully paid.',
        ],200);
    }

    public function rateOrder($id)
    {
        try {
            request()->validate([
                'rate' => ['required', 'numeric', 'between:1,5'],
            ]);
            $order = $this->orderRepository->find($id);
            if (!$order)
                return response()->json([
                    'success' => false,
                    'message'   => 'Order not found.',
                ],200);

            if ($order->rate)
                return response()->json([
                    'success' => false,
                    'message'   => 'Order has already been rated.',
                ],200);


            $order->rate = request()->get('rate');
            $order->save();

            return response()->json([
                'success' => true,
                'message'   => 'Order has been successfully rated.',
            ],200);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message'    => $exception->getMessage(),
            ],200);
        }
    }

    public function cancel($id)
    {

        $user_id = Auth::user()->id;
        $order = Order::where('id', $id)->where('user_id', $user_id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found for this user.',
            ],200);
        }
        if($order->status !='pending'){
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be canceled because it is not in a pending status.',
            ]);
        }
        $currentTime = Carbon::now();
        $orderUpdatedAt = Carbon::createFromTimeString($order->updated_at);
        $timeDifference = $orderUpdatedAt->diffInSeconds($currentTime);
        // dd($timeDifference);
        if ($timeDifference >= 0 && $timeDifference <= 60) {

            $order->status = Order::STATUS_CANCELED;
            $order->save();
            // Cancel the order
            // You can implement your cancellation logic here

            return response()->json([
                'success' => true,
                'message' => 'Order has been canceled.',
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be canceled as it has been more than 1 minute since the last update.',
            ],200);
        }
    }

    public function rateAndFeedback()
    {
        $orderItems = request()->input('order_item');
        $userId = auth()->user()->id; // Get the authenticated user's ID

        foreach ($orderItems as $orderItem) {

            $order_item = OrderItem::find($orderItem['id']);

            if (!$order_item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order item not found'
                ], 200);
            }

            // Retrieve the associated order
            $order = $order_item->order;

            if ($order->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: This order does not belong to you'
                ], 200);
            }

            // Check if the order has a status of "delivered"
            if ($order->status !== 'delivered') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is not delivered'
                ], 200);
            }

            // Update the order item with the received rate and feedback
            $order_item->rate = $orderItem['rate'];
            $order_item->feedback = $orderItem['feedback'];
            $order_item->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Ratings and feedback saved successfully'
        ]);
    }

}
