<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmOrderForm;
use Cart;
use Exception;
use Illuminate\Support\Str;
use App\Repositories\OrderRepository;
use App\Repositories\CartRepository;
use App\Repositories\CartItemRepository;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Cart as CartResource;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
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
     * OrderRepository object
     *
     * @var \App\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Controller instance
     *
     * @param  \App\Repositories\CartRepository  $cartRepository
     * @param  \App\Repositories\CartItemRepository  $cartItemRepository
     * @param  \App\Repositories\OrderRepository  $orderRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        OrderRepository $orderRepository
    ) {

        Auth::setDefaultDriver('api');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->orderRepository = $orderRepository;
    }


    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json([
                'success' => false,
                'message'   => Cart::getErrorMessage(),
                'errors'   => Cart::getErrors()
            ]);
        }

        if (!request()->get('payment')) {
            return response()->json([
                'success' => false,
                'message'   => 'Please specify payment method.',
                'errors'   => null
            ]);
        }

        try {
            Cart::collectTotals();

            $this->validateOrder();

            $dataArray = Cart::prepareDataForOrder();
            $orders = array();

            foreach ($dataArray as $data) {
                $orders[] = $this->orderRepository->create(array_merge($data, ['payment' => request()->get('payment')]));
            }

            Cart::deActivateCart();

            return response()->json([
                'success' => true,
                'orders'   => OrderResource::collection($orders),
            ]);
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        if (
            auth()->check()
            && !auth()->user()->is_active
        ) {
            throw new \Exception(trans('Your account has been inactive.'));
        }

        if (
            auth()->user()
            && auth()->user()->status != 'accept'
        ) {
            // dd(auth()->user()->status);
            throw new \Exception(trans('Your account is not active.'));
        }

        if (!$cart->address) {
            throw new \Exception(trans('Please check the address.'));
        }

        // if (!$cart->shipping_method) {
        //     throw new \Exception(trans('Please specify shipping method.'));
        // }

        // if (!$cart->payment) {
        //     throw new \Exception(trans('Please specify payment method.'));
        // }
    }
}
