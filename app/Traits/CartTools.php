<?php

namespace App\Traits;

/**
 * Cart tools. In this trait, you will get all sorted collections of
 * methods which can be used to manipulate cart and its items.
 *
 * Note: This trait will only work with the Cart facade. Unless and until,
 * you have all the required repositories in the parent class.
 */
trait CartTools
{
    /**
     * Remove cart and destroy the session
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function removeCart($cart)
    {
        $this->cartRepository->delete($cart->id);

        if (session()->has('cart')) {
            session()->forget('cart');
        }

        $this->resetCart();
    }

    /**
     * Save cart for guest.
     *
     * @param  \App\Models\Cart  $cart
     * @return void
     */
    public function putCart($cart)
    {
        if (!auth()->guard('api')->check()) {
            $cartTemp = new \stdClass();
            $cartTemp->id = $cart->id;

            session()->put('cart', $cartTemp);
        }
    }

    /**
     * This method handles when guest has some of cart products and then logs in.
     *
     * @return void
     */
    public function mergeCart(): void
    {
        if (session()->has('cart')) {
            $cart = $this->cartRepository->findOneWhere([
                'user_id' => auth()->guard('api')->user()->id,
                'is_active'   => 1,
            ]);

            $this->setCart($cart);

            $guestCart = $this->cartRepository->find(session()->get('cart')->id);

            /**
             * When the logged in user is not having any of the cart instance previously and are active.
             */
            if (!$cart) {
                $this->cartRepository->update([
                    'user_id'         => auth()->guard('api')->user()->id,
                    'user_first_name' => auth()->guard('api')->user()->first_name,
                    'user_last_name'  => auth()->guard('api')->user()->last_name,
                    'user_email'      => auth()->guard('api')->user()->email,
                ], $guestCart->id);

                session()->forget('cart');

                return;
            }

            foreach ($guestCart->items as $guestCartItem) {
                try {
                    $cart = $this->addProduct($guestCartItem->product_id, $guestCartItem->additional);
                } catch (\Exception $e) {
                    //Ignore exception
                }
            }

            $this->collectTotals();

            $this->removeCart($guestCart);
        }
    }

    /**
     * This method will merge deactivated cart, when a user suddenly navigates away
     * from the checkout after click the buy now button.
     *
     * @return void
     */
    public function mergeDeactivatedCart(): void
    {
        if (session()->has('deactivated_cart_id')) {
            $deactivatedCartId = session()->get('deactivated_cart_id');

            if ($this->getCart()) {
                $deactivatedCart = $this->cartRepository->find($deactivatedCartId);

                foreach ($deactivatedCart->items as $deactivatedCartItem) {
                    $this->addProduct($deactivatedCartItem->product_id, $deactivatedCartItem->additional);
                }

                $this->collectTotals();
            } else {
                $this->cartRepository->update(['is_active' => true], $deactivatedCartId);
            }

            session()->forget('deactivated_cart_id');
        }
    }

    /**
     * This method will deactivate the current cart if
     * buy now is active.
     *
     * @return void
     */
    public function deactivateCurrentCartIfBuyNowIsActive()
    {
        if (request()->get('is_buy_now')) {
            if ($deactivatedCart = $this->getCart()) {
                session()->put('deactivated_cart_id', $deactivatedCart->id);

                $this->deActivateCart();
            }
        }
    }

    /**
     * This method will reactivate the cart which is deactivated at the the time of buy now functionality.
     *
     * @return void
     */
    public function activateCartIfSessionHasDeactivatedCartId(): void
    {
        if (session()->has('deactivated_cart_id')) {
            $deactivatedCartId = session()->get('deactivated_cart_id');

            $this->activateCart($deactivatedCartId);

            session()->forget('deactivated_cart_id');
        }
    }

    /**
     * Deactivates current cart.
     *
     * @return void
     */
    public function deActivateCart(): void
    {
        if ($cart = $this->getCart()) {
            $cart = $this->cartRepository->update(['is_active' => false], $cart->id);

            $this->resetCart();

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }

    /**
     * Deactivates current cart.
     *
     * @return void
     */
    public function deActivateCartAndDelete(): void
    {
        if ($cart = $this->getCart()) {
            $cart->delete();

            $this->resetCart();

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }

    /**
     * Activate the cart by id.
     *
     * @param  int  $cartId
     * @return void
     */
    public function activateCart($cartId): void
    {
        $this->cartRepository->update(['is_active' => true], $cartId);

        $this->putCart($this->cartRepository->find($cartId));
    }
}
