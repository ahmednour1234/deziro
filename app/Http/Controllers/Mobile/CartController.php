<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutForm;
use App\Repositories\CartItemRepository;
use App\Repositories\CartRepository;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\Cart as CartResource;
use App\Models\Address;
use App\Models\CartPayment;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * CartRepository object
     *
     * @var \App\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * CartItemRepository object
     *
     * @var \App\Repositories\CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * Controller instance
     *
     * @param \App\Repositories\CartRepository     $cartRepository
     * @param \App\Repositories\CartItemRepository $cartItemRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
    ) {
        $this->guard = 'api';

        Auth::setDefaultDriver($this->guard);

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Get user cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function get()
    // {
    //     $cart = Cart::getCart();

    //     return response()->json([
    //         'data' => $cart ? new CartResource($cart) : null,
    //     ]);
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function store($id)
    // {
    //     // session()->put('cart', 1212);
    //     // return session()->get('cart');
    //     try {
    //         $result = Cart::addProduct($id, request()->except('_token'));

    //         if (is_array($result) && isset($result['warning'])) {
    //             return response()->json([
    //                 'error' => $result['warning'],
    //             ], 400);
    //         }

    //         Cart::collectTotals();

    //         $cart = Cart::getCart();

    //         return response()->json([
    //             'message' => __('Item is successfully added to cart.'),
    //             'data'    => $cart ? new CartResource($cart) : null,
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error($e);

    //         return response()->json([
    //             'error' => [
    //                 'message' => $e->getMessage(),
    //                 'code'    => $e->getCode()
    //             ]
    //         ]);
    //     }
    // }

    public function applyCoupon(Request $request)
    {
        return Cart::applyCoupon($request['coupon']);
    }

    public function wrapAsGift(Request $request){

    }

    public function rateAndFeedback(Request $request){
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
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

        return response()->json([
            'message' => __('shop::app.checkout.cart.quantity.success'),
            'data'    => $cart ? new CartResource($cart) : null,
            'html'    => view('shop::checkout.cart.mini-cart', compact('cart'))->render()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        Event::dispatch('checkout.cart.delete.before');

        Cart::deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart) : null,
            'html'    => view('shop::checkout.cart.mini-cart', compact('cart'))->render()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyItem($id)
    {
        Event::dispatch('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::dispatch('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => __('shop::app.checkout.cart.item.success-remove'),
            'data'    => $cart ? new CartResource($cart) : null,
            'html'    => view('shop::checkout.cart.mini-cart', compact('cart'))->render()
        ]);
    }

    public function storeItems(CheckoutForm $checkoutRequest): ?JsonResponse
    {
        // dd(1);
        Cart::deActivateCartAndDelete();

        $ids = [];

        if (request()->get('items'))
            $ids = request()->get('items');
        else
            return response()->json([
                'message' => 'Something went wrong!',
                'data'    => null,
                'errors'  => null
            ]);
        $errors = [];


        foreach ($ids as $id => $quantity) {
            request()->request->add(['product_id' => $id]);
            request()->request->add(['quantity' => $quantity]);

            try {
                if (!intval($quantity) || $quantity < 1) {
                    $errors[$id] = 'Invalid Quantity';
                    continue;
                }
                $result = Cart::addProduct($id, request()->except('_token'));
                $e = isset($result['errors']) ? $result['errors'] : [];
                foreach ($e as $key => $value) {
                    $errors[$key] = $value;
                }

                if (is_array($result) && isset($result['warning'])) {
                    $errors[$id] = $result['warning'];
                    continue;
                }
            } catch (Exception $e) {
                Log::error(
                    'API CartController: ' . $e->getMessage(),
                    ['product_id' => $id, 'cart_id' => Cart::getCart() ?? 0]
                );
                Log::error($e);
                $errors[$id] = $e->getMessage();
                continue;
            }
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        if ($cart) {
            try {


                if ($address_id = request()->get('address_id')) {
                    $address = Arr::except(Address::findOrFail($address_id)->toArray(), ['id', 'cart_id', 'order_id']);
                } else {
                    $address = request()->get('address');
                }
                $address['user_id'] =  auth()->guard('api')->user() ? auth()->guard('api')->user()->id : null;
                $address['type'] =  Address::ADDRESS_TYPE_CART;

                $cart->addresses()->create($address);

                $errorsValues = [];
                if (count($errors)) {
                    foreach ($errors as $key => $value) {
                        $errorsValues[] = ['id' => $key, 'message' => $value];
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => __('Items added successfully'),
                    'data'    => new CartResource(Cart::getCart()),
                    'errors'  => count($errorsValues) ? $errorsValues : null
                ]);
            } catch (Exception $e) {
                Log::error(
                    'API CartController: ' . $e->getMessage(),
                );
                Log::error($e);
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data'    => null,
                    'errors'  => null
                ]);
            }
        } else {
            $errorsValues = [];
            if (count($errors)) {
                foreach ($errors as $key => $value) {
                    $errorsValues[] = ['id' => $key, 'message' => $value];
                }
            }
            return response()->json([
                'success' => false,
                'message' => __('Something went wrong!'),
                'data'    => null,
                'errors'  => count($errorsValues) ? $errorsValues : null
            ]);
        }
    }
}
