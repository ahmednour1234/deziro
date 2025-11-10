<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutForm;
use App\Http\Resources\Cart as CartResource;
use App\Models\Address;
use App\Models\CartItem;
use App\Repositories\CartItemRepository;
use App\Repositories\CartRepository;
use Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Guard used for API auth.
     *
     * @var string
     */
    protected $guard = 'api';

    /**
     * @var \App\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * @var \App\Repositories\CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * CartController constructor.
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
    ) {
        $this->cartRepository    = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;

        Auth::setDefaultDriver($this->guard);
    }

    /**
     * Apply coupon on cart.
     * بعد تطبيق الكوبون نعيد حساب الـ VAT.
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $response = Cart::applyCoupon($request->input('coupon'));

        $cart = Cart::getCart();

        if ($cart) {
            $this->applyVat($cart);
        }

        return response()->json([
            'success' => true,
            'cart'    => $cart ? new CartResource($cart->fresh()) : null,
            'raw'     => $response,
        ]);
    }

    /**
     * Wrap selected items as gift and recalculate totals + VAT.
     */
    public function wrapAsGift(Request $request): JsonResponse
    {
        $cart         = Cart::getCart();
        $cartItemIds  = $request->input('cart_item_ids');

        if (! $cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], 404);
        }

        if ($cartItemIds) {
            $cartItems        = CartItem::whereIn('id', $cartItemIds)->get();
            $cartItemsToReset = CartItem::where('cart_id', $cart->id)
                ->whereNotIn('id', $cartItemIds)
                ->where('wrap_as_gift', 1)
                ->get();

            $totalWrapAsGiftPriceToSubtract = 0;
            $totalWrapAsGiftPriceToAdd      = 0;

            // Reset wrap for selected items first
            foreach ($cartItems as $cartItem) {
                $totalWrapAsGiftPriceToSubtract += $cartItem->wrap_as_gift_price;

                $cartItem->total             -= $cartItem->wrap_as_gift_price;
                $cartItem->wrap_as_gift      = 0;
                $cartItem->wrap_as_gift_price = 0;
                $cartItem->save();
            }

            // Apply wrap for selected items
            foreach ($cartItems as $cartItem) {
                if ($cartItem->wrap_as_gift == 0 && $cartItem->product && $cartItem->product->status === 'active') {
                    $wrapPrice = $cartItem->product->wrap_as_gift_price * $cartItem->quantity;

                    $cartItem->wrap_as_gift       = 1;
                    $cartItem->wrap_as_gift_price = $wrapPrice;
                    $cartItem->total              += $wrapPrice;
                    $cartItem->save();

                    $totalWrapAsGiftPriceToAdd += $wrapPrice;
                }
            }

            // Reset wrap for other items not selected
            foreach ($cartItemsToReset as $cartItem) {
                $totalWrapAsGiftPriceToSubtract += $cartItem->wrap_as_gift_price;

                $cartItem->total             -= $cartItem->wrap_as_gift_price;
                $cartItem->wrap_as_gift      = 0;
                $cartItem->wrap_as_gift_price = 0;
                $cartItem->save();
            }

            // Update cart totals based on changes
            $updatedTotalWrapAsGiftPrice = $cart->all_items->sum('wrap_as_gift_price');
            $updatedGrandTotal = $cart->grand_total - $totalWrapAsGiftPriceToSubtract + $totalWrapAsGiftPriceToAdd;

            $cart->grand_total              = $updatedGrandTotal;
            $cart->total_wrap_as_gift_price = $updatedTotalWrapAsGiftPrice;
            $cart->save();
        } else {
            // No IDs => reset all wrap_as_gift
            $totalWrapAsGiftPriceToSubtract = 0;

            foreach ($cart->items as $cartItem) {
                $totalWrapAsGiftPriceToSubtract += $cartItem->wrap_as_gift_price;

                $cartItem->total             -= $cartItem->wrap_as_gift_price;
                $cartItem->wrap_as_gift      = 0;
                $cartItem->wrap_as_gift_price = 0;
                $cartItem->save();
            }

            $cart->total_wrap_as_gift_price = 0;
            $cart->grand_total              -= $totalWrapAsGiftPriceToSubtract;
            $cart->save();
        }

        // بعد تعديل التوتال، طبّق VAT
        $this->applyVat($cart);

        $cart = $cart->fresh();

        return response()->json([
            'success' => true,
            'cart'    => new CartResource($cart),
        ], 200);
    }

    /**
     * Update cart quantities and recalc totals + VAT.
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'qty' => 'required|array',
        ]);

        $requestedQuantity = $request->get('qty');

        foreach ($requestedQuantity as $qty) {
            if ($qty <= 0) {
                return response()->json([
                    'message' => trans('shop::app.checkout.cart.quantity.illegal'),
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        foreach ($requestedQuantity as $itemId => $qty) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            Event::dispatch('checkout.cart.item.update.before', $itemId);

            Cart::updateItems(['qty' => $requestedQuantity]);

            Event::dispatch('checkout.cart.item.update.after', $item);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        if ($cart) {
            $this->applyVat($cart);
        }

        return response()->json([
            'message' => __('shop::app.checkout.cart.quantity.success'),
            'data'    => $cart ? new CartResource($cart->fresh()) : null,
            'html'    => view('shop::checkout.cart.mini-cart', ['cart' => $cart])->render(),
        ]);
    }

    /**
     * Clear cart (no VAT needed if no cart).
     */
    public function destroy(): JsonResponse
    {
        Event::dispatch('checkout.cart.delete.before');

        Cart::deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart) : null,
            'html'    => view('shop::checkout.cart.mini-cart', compact('cart'))->render(),
        ]);
    }

    /**
     * Remove single item, recalc totals + VAT.
     */
    public function destroyItem($id): JsonResponse
    {
        Event::dispatch('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::dispatch('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        if ($cart) {
            $this->applyVat($cart);
        }

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart->fresh()) : null,
            'html'    => view('shop::checkout.cart.mini-cart', ['cart' => $cart])->render(),
        ]);
    }

    /**
     * Store selected items as cart, attach address, recalc VAT.
     */
    public function storeItems(CheckoutForm $checkoutRequest): ?JsonResponse
    {
        Cart::deActivateCartAndDelete();

        $ids = request()->get('items') ?? [];

        if (! count($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'data'    => null,
                'errors'  => null,
            ]);
        }

        $errors = [];

        foreach ($ids as $id => $quantity) {
            request()->request->add(['product_id' => $id]);
            request()->request->add(['quantity' => $quantity]);

            try {
                if (! intval($quantity) || $quantity < 1) {
                    $errors[$id] = 'Invalid Quantity';
                    continue;
                }

                $result = Cart::addProduct($id, request()->except('_token'));

                $e = $result['errors'] ?? [];
                foreach ($e as $key => $value) {
                    $errors[$key] = $value;
                }

                if (is_array($result) && isset($result['warning'])) {
                    $errors[$id] = $result['warning'];
                    continue;
                }
            } catch (Exception $e) {
                Log::error('API CartController: ' . $e->getMessage(), [
                    'product_id' => $id,
                    'cart_id'    => Cart::getCart() ?? 0,
                ]);

                $errors[$id] = $e->getMessage();
                continue;
            }
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        if (! $cart) {
            $errorsValues = [];
            foreach ($errors as $key => $value) {
                $errorsValues[] = ['id' => $key, 'message' => $value];
            }

            return response()->json([
                'success' => false,
                'message' => __('Something went wrong!'),
                'data'    => null,
                'errors'  => count($errorsValues) ? $errorsValues : null,
            ]);
        }

        try {
            // Address
            if ($address_id = request()->get('address_id')) {
                $address = Arr::except(
                    Address::findOrFail($address_id)->toArray(),
                    ['id', 'cart_id', 'order_id']
                );
            } else {
                $address = request()->get('address');
            }

            $address['user_id'] = auth()->guard('api')->user()->id ?? null;
            $address['type']    = Address::ADDRESS_TYPE_CART;

            $cart->addresses()->create($address);

            // Apply VAT after totals
            $this->applyVat($cart);

            $errorsValues = [];
            foreach ($errors as $key => $value) {
                $errorsValues[] = ['id' => $key, 'message' => $value];
            }

            return response()->json([
                'success' => true,
                'message' => __('Items added successfully'),
                'data'    => new CartResource($cart->fresh()),
                'errors'  => count($errorsValues) ? $errorsValues : null,
            ]);
        } catch (Exception $e) {
            Log::error('API CartController: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
                'errors'  => null,
            ]);
        }
    }

    /**
     * Apply VAT based on store VAT setting.
     *
     * - لو store->vat_status = 1 → vat = 11% من إجمالي cart قبل الضريبة.
     * - لو Off → vat = 0.
     * - الدالة Idempotent: مش بتراكم الضريبة لو اتندَهت أكثر من مرة.
     *
     * @param  \App\Models\Cart|\Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    protected function applyVat($cart): void
    {
        if (! $cart) {
            return;
        }

        // نفترض عندك علاقة store: $cart->store وعمود vat_status في جدول stores
        $store = $cart->store ?? null;

        // رجّع الـ grand_total لقبل الضريبة عن طريق طرح الـ vat القديم
        $baseTotal = $cart->grand_total - (float) ($cart->vat ?? 0);

        if ($baseTotal < 0) {
            $baseTotal = 0;
        }

        $vat = 0;

        if ($store && (int) ($store->vat ?? 0) === 1) {
            $vat = round($baseTotal * 0.11, 2); // 11%
        }

        $cart->vat         = $vat;
        $cart->grand_total = $baseTotal + $vat;
        $cart->save();
    }
}
