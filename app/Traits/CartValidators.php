<?php

namespace App\Traits;

/**
 * Cart validators. In this trait, you will get all sorted collections of
 * methods which can be used to check the carts for validation.
 *
 * Note: This trait will only work with the Cart facade. Unless and until,
 * you have all the required repositories in the parent class.
 */
trait CartValidators
{
    /**
     * Check whether cart has product.
     *
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function hasProduct($product): bool
    {
        $cart = $this->getCart();

        if (!$cart) {
            return false;
        }

        $count = $cart->all_items()->where('product_id', $product->id)->count();

        return $count > 0;
    }

    /**
     * Checks if cart has any error.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        if (
            !$this->getCart()
            || !$this->isItemsHaveSufficientQuantity()
        ) {
            return true;
        }

        return false;
    }

    public function getErrorMessage()
    {
        if (
            !$this->getCart()
        ) {
            return 'Cart is Empty.';
        }
        if (
            !$this->isItemsHaveSufficientQuantity()
        ) {
            return 'Some items do not have enough quantity.';
        }

        return '';
    }

    public function getErrors(): array|null
    {
        if (
            !$this->getCart()
        ) {
            return null;
        }

        if (
            !$this->isItemsHaveSufficientQuantity()
        ) {
            return $this->getItemsHaveNotSufficientQuantity();
        }

        return null;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @return bool
     */
    public function isItemsHaveSufficientQuantity(): bool
    {
        $cart = cart()->getCart();

        if (!$cart) {
            return false;
        }

        foreach ($cart->items as $item) {
            if (!$this->isItemHaveQuantity($item)) {
                return false;
            }
        }

        return true;
    }

    public function getItemsHaveNotSufficientQuantity(): array
    {
        $cart = cart()->getCart();

        $items = [];

        if (!$cart) {
            return null;
        }

        foreach ($cart->items as $item) {
            if (!$this->isItemHaveQuantity($item)) {
                if ($item->product->totalQuantity())
                    $items[] = [
                        'id' => $item->product->id,
                        'message' => 'The requested quantity of "' . $item->name . '" is not available. only ' . $item->product->totalQuantity() . '(s) have been added.'
                    ];
                else {
                    $items[] = [
                        'id' => $item->product->id,
                        'message' => '"' . $item->name . '" is not available.'
                    ];
                }
            }

            if ($item->product->totalQuantity()) {
                $item->quantity = $item->product->totalQuantity();
                $item->save();
            }
        }

        return count($items) ? $items : null;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @param \App\Models\CartItem  $item
     * @return bool
     */
    public function isItemHaveQuantity($item): bool
    {
        return $item->product->isItemHaveQuantity($item);
    }

    /**
     * Check minimum order.
     *
     * @return boolean
     */
    public function checkMinimumOrder(): bool
    {
        $cart = $this->getCart();

        if (!$cart) {
            return false;
        }

        return $cart->checkMinimumOrder();
    }
}
