<?php

namespace App\Helpers;

use App\Http\Resources\User as ResourcesUser;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use App\Models\Address;
use App\Models\Cart as CartModel;
use App\Models\CartPayment;
use App\Models\User;
use App\Repositories\CartItemRepository;
use App\Repositories\CartRepository;
use App\Traits\CartTools;
use App\Traits\CartValidators;
use App\Repositories\AddressRepository;
use App\Repositories\ProductRepository;

class Cart
{
    use CartTools, CartValidators;

    /**
     * @var \App\Models\Cart
     */
    private $cart;

    /**
     * Create a new class instance.
     *
     * @param  \App\Repositories\CartRepository  $cartRepository
     * @param  \App\Repositories\CartItemRepository  $cartItemRepository
     * @param  \App\Repositories\ProductRepository  $productRepository
     * @param  \App\Repositories\AddressRepository  $addressRepository
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected ProductRepository $productRepository,
        protected AddressRepository $addressRepository
    ) {
        $this->initCart();
    }

    /**
     * Returns cart.
     *
     * @return \App\Models\Cart|null
     */
    public function initCart()
    {
        $this->getCart();

        if ($this->cart) {
            $this->removeInactiveItems();
        }
    }

    /**
     * Returns cart.
     *
     * @return \App\Models\Cart|null
     */
    public function getCart(): ?\App\Models\Cart
    {
        if ($this->cart) {
            return $this->cart;
        }


        if (auth()->guard('api')->check()) {
            $this->cart = $this->cartRepository->findOneWhere([
                'user_id' => auth()->guard('api')->user()->id,
                'is_active'   => 1,
            ]);
        } elseif (session()->has('cart')) {
            $this->cart = $this->cartRepository->find(session()->get('cart')->id);
        }

        return $this->cart;
    }

    /**
     * Set cart model to the variable for reuse
     *
     * @param \App\Models\Cart
     * @return  void
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Reset cart
     *
     * @return  void
     */
    public function resetCart()
    {
        $this->cart = null;
    }

    /**
     * Get cart item by product.
     *
     * @param  array  $data
     * @param  array|null  $parentData
     * @return \App\Models\CartItem|void
     */
    public function getItemByProduct($data, $parentData = null)
    {
        $items = $this->getCart()->all_items;
        // foreach ($items as $item) {
        //     if ($item->product->compareOptions($item->additional, $data['additional'])) {
        //         if (!isset($data['additional']['parent_id'])) {
        //             return $item;
        //         }

        //         if ($item->parent->product->compareOptions($item->parent->additional, $parentData ?: request()->all())) {
        //             return $item;
        //         }
        //     }
        // }
    }

    /**
     * Add items in a cart with some cart and item details.
     *
     * @param  int  $productId
     * @param  array  $data
     * @return \App\Models\Cart|string|array
     * @throws Exception
     */
    public function addProduct($productId, $data)
    {

        $cart = $this->getCart();

        if (
            !$cart
            && !$cart = $this->create($data)
        ) {
            return ['warning' => __('Item cannot be added to cart.')];
        }

        // dd($cart);
        $product = $this->productRepository->active()->activeUser()->where('products.id', $productId)->first();
        // dd($this->productRepository->active()->activeUser()->where('products.id', $productId)->first());
        if (!$product) {
            return ['warning' => "-1"];
        }
        $haveSufficientQuantity = true;
        if (!$product->haveSufficientQuantity(isset($data['quantity']) ? $data['quantity'] : 1)) {
            $haveSufficientQuantity = false;
            if ($product->totalQuantity())
                $data['quantity'] = $product->totalQuantity();
            else
                return ['warning' => __('"' . $product->name . '" is not available.')];
        }

        if ($product->status != 'active') {
            return ['warning' => __('Inactive item cannot be added to cart.')];
        }

        if ($product->status != 'active') {
            return ['warning' => __('Inactive item cannot be added to cart.')];
        }

        $cartProducts = $product->prepareForCart($data);

        if (is_string($cartProducts)) {
            if ($cart->all_items->count() <= 0) {
                $this->removeCart($cart);
            } else {
                $this->collectTotals();
            }

            throw new Exception($cartProducts);
        } else {

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->cartItemRepository->where('cart_id', $cart->id)->where('product_id', $cartProduct['product_id'])->first();


                if (!$cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                }
            }
        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        if (!$haveSufficientQuantity) {
            return ['warning' => 'The requested quantity of "' . $product->name . '" is not available. only ' . $product->totalQuantity() . ' item(s) have been added.'];
        }

        return $this->getCart();
    }

    /**
     * Create new cart instance.
     *
     * @param  array  $data
     * @return \App\Models\Cart|null
     */
    public function create($data)
    {
        $cartData = [
            'cart_currency_code'    => getBaseCurrency(),
            'items_count'           => 1,
        ];

        /**
         * Fill in the user data, as far as possible.
         */
        if (auth()->guard('api')->check()) {
            $user = auth()->guard('api')->user();

            $cartData = array_merge($cartData, [
                'user_id'         => $user->id,
                'user_first_name' => $user->first_name,
                'user_last_name'  => $user->last_name,
                'user_email'      => $user->email,
            ]);
        } else {
            $cartData['is_guest'] = 1;
        }

        $cart = $this->cartRepository->create($cartData);

        if (!$cart) {
            session()->flash('error', __('Encountered some issue while making cart instance.'));

            return;
        }

        $this->setCart($cart);

        $this->putCart($cart);
        // dd($cart);
        return $cart;
    }

    /**
     * Update cart items information.
     *
     * @param  array  $data
     * @return bool|void|Exception
     */
    public function updateItems($data)
    {
        foreach ($data['qty'] as $itemId => $quantity) {
            $item = $this->cartItemRepository->find($itemId);

            if (!$item) {
                continue;
            }

            if (
                $item->product
                && !$item->product->status
            ) {
                throw new Exception(__('An item is inactive and was removed from cart.'));
            }

            if ($quantity <= 0) {
                $this->removeItem($itemId);

                throw new Exception(__('Quantity cannot be lesser than one.'));
            }

            $item->quantity = $quantity;

            if (!$this->isItemHaveQuantity($item)) {
                throw new Exception(__('The requested quantity is not available'));
            }

            Event::dispatch('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                'quantity'          => $quantity,
                'total'             => convertPrice($item->price * $quantity),
                'base_total'        => $item->price * $quantity,
                'total_weight'      => $item->weight * $quantity,
                'base_total_weight' => $item->weight * $quantity,
            ], $itemId);

            Event::dispatch('checkout.cart.update.after', $item);
        }

        $this->collectTotals();

        return true;
    }

    /**
     * Remove the item from the cart.
     *
     * @param  int  $itemId
     * @return boolean
     */
    public function removeItem($itemId)
    {
        Event::dispatch('checkout.cart.delete.before', $itemId);

        if (!$cart = $this->getCart()) {
            return false;
        }

        if ($cartItem = $cart->items()->find($itemId)) {
            $cartItem->delete();

            if (!$cart->items()->get()->count()) {
                $this->removeCart($cart);
            } else {
                // Shipping::collectRates();
            }

            Event::dispatch('checkout.cart.delete.after', $itemId);

            $this->collectTotals();

            return true;
        }

        return false;
    }

    /**
     * Remove all items from cart.
     *
     * @return \App\Models\Cart|null
     */
    public function removeAllItems(): ?CartModel
    {
        $cart = $this->getCart();

        Event::dispatch('checkout.cart.delete.all.before', $cart);

        if (!$cart) {
            return $cart;
        }

        foreach ($cart->items as $item) {
            $this->removeItem($item->id);
        }

        Event::dispatch('checkout.cart.delete.all.after', $cart);

        return $cart;
    }

    /**
     * Remove cart items, whose product is inactive.
     *
     * @return void
     */
    public function removeInactiveItems()
    {
        $cart = $this->getCart();

        foreach ($cart->items as $item) {
            if ($this->isCartItemInactive($item)) {
                $this->cartItemRepository->delete($item->id);

                if (!$cart->items->count()) {
                    $this->removeCart($cart);
                }

                session()->flash('info', __('An item is inactive and was removed from cart.'));
            }
        }
    }

    /**
     * Save user address.
     *
     * @param  array  $data
     * @return bool
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function saveUserAddress($data): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        $addressAddressData = $this->gatherAddress($data, $cart);

        if (
            auth()->guard('api')->check()
            && ($user = auth()->guard('api')->user())
            && ($user->email
                && $user->first_name
                && $user->last_name
            )
        ) {
            $cart->user_email = $user->email;
            $cart->user_first_name = $user->first_name;
            $cart->user_last_name = $user->last_name;
        } else {
            $cart->user_email = $cart->address->email;
            $cart->user_first_name = $cart->address->first_name;
            $cart->user_last_name = $cart->address->last_name;
        }

        $cart->save();

        $this->collectTotals();

        return true;
    }

    /**
     * Save payment method for cart.
     *
     * @param  string  $payment
     * @return \App\Models\CartPayment
     */
    public function savePaymentMethod($payment)
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        if ($cartPayment = $cart->payment) {
            $cartPayment->delete();
        }

        $cartPayment = new CartPayment;

        $cartPayment->method = $payment['method'];
        $cartPayment->cart_id = $cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Updates cart totals.
     *
     * @return void
     */
    public function collectTotals(): void
    {
        if (!$this->validateItems()) {
            return;
        }

        if (!$cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $cart);

        $cart->refresh();

        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;
        $cart->fees_amount = $cart->base_fees_amount = 0;

        $quantities = 0;

        foreach ($cart->items as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;

            $cart->sub_total = (float) $cart->sub_total + $item->total;
            $cart->base_sub_total = (float) $cart->base_sub_total + $item->base_total;

            $quantities += $item->quantity;
        }

        $cart->items_qty = $quantities;

        $cart->items_count = $cart->items->count();

        $cart->fees_amount = round($cart->sub_total * getFeesRate(), 2);
        $cart->base_fees_amount = round($cart->base_sub_total * getFeesRate(), 2);

        $cart->grand_total = $cart->sub_total + $cart->fees_amount - $cart->discount_amount;
        $cart->base_grand_total = $cart->base_sub_total + $cart->base_fees_amount - $cart->base_discount_amount;

        $cart->discount_amount = round($cart->discount_amount, 2);
        $cart->base_discount_amount = round($cart->base_discount_amount, 2);

        $cart->sub_total = round($cart->sub_total, 2);
        $cart->base_sub_total = round($cart->base_sub_total, 2);

        $cart->grand_total = round($cart->grand_total, 2);
        $cart->base_grand_total = round($cart->base_grand_total, 2);

        $cart->cart_currency_code = getBaseCurrency();

        $cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $cart);
    }

    /**
     * Updates cart totals.
     *
     * @return void
     */
    public function collectTotalsByUser($items, $user_id)
    {
        if (!$this->validateItems()) {
            return null;
        }

        if (!$cart = $this->getCart()) {
            return null;
        }

        $user = User::findOrFail($user_id);

        $data = array();
        $data['sub_total'] = $data['base_sub_total'] = 0;
        $data['grand_total'] = $data['base_grand_total'] = 0;
        $data['discount_amount'] = $data['base_discount_amount'] = 0;
        $data['fees_amount'] = $data['base_fees_amount'] = 0;

        $quantities = 0;

        foreach ($items as $item) {
            $data['discount_amount'] += $item['discount_amount'];
            $data['base_discount_amount'] += $item['base_discount_amount'];

            $data['sub_total'] = (float) $data['sub_total'] + $item['total'];
            $data['base_sub_total'] = (float) $data['base_sub_total'] + $item['base_total'];

            $quantities += $item['quantity'];
        }
        $data['items_qty'] = $quantities;

        $data['items_count'] = count($items);

        $data['fees_amount'] = round($data['sub_total'] * getFeesRate(), 2);
        $data['base_fees_amount'] = round($data['base_sub_total'] * getFeesRate(), 2);

        $data['grand_total'] = $data['sub_total'] + $data['fees_amount'] - $data['discount_amount'];
        $data['base_grand_total'] = $data['base_sub_total'] + $data['base_fees_amount'] - $data['base_discount_amount'];

        $data['discount_amount'] = round($data['discount_amount'], 2);
        $data['base_discount_amount'] = round($data['base_discount_amount'], 2);

        $data['sub_total'] = round($data['sub_total'], 2);
        $data['base_sub_total'] = round($data['base_sub_total'], 2);

        $data['grand_total'] = round($data['grand_total'], 2);
        $data['base_grand_total'] = round($data['base_grand_total'], 2);

        $data['cart_currency_code'] = getBaseCurrency();
        $data['items'] = $items;

        return $data;
    }

    /**
     * To validate if the product information is changed by admin and the items have been added to the cart before it.
     *
     * @return bool
     */
    public function validateItems(): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        $cartItems = $cart->items()->get();

        if (!count($cartItems)) {
            $this->removeCart($cart);

            return false;
        }

        $isInvalid = false;

        foreach ($cartItems as $item) {
            $validationResult = $item->product ? $item->product->validateCartItem($item) : false;

            if (!$validationResult) {
                $this->removeItem($item->id);

                $isInvalid = true;

                session()->flash('info', __('An item is inactive and was removed from cart.'));
            }
        }

        return !$isInvalid;
    }

    /**
     * Prepare data for order.
     *
     * @return array
     */
    public function prepareDataForOrder(): array
    {
        $result = $this->toArray();
        $itemsByUser = array();
        foreach ($result['items'] as $item) {
            $itemsByUser[$item['user_id']][] = $item;
        }
        $dataArray = array();
        foreach ($itemsByUser as $user_id => $items) {
            $dataArray[$user_id] = $this->collectTotalsByUser($items, $user_id);
        }
        $finalDataArray = array();
        foreach ($dataArray as $user_id => $data) {
            $user = User::findOrFail($user_id);
            $finalData = [
                'cart_id'               => $this->getCart()->id,
                'user_id'               => $result['user_id'],
                'user_email'            => $result['user_email'],
                'user_first_name'       => $result['user_first_name'],
                'user_last_name'        => $result['user_last_name'],
                'user'                  => auth()->guard('api')->check() ? auth()->guard('api')->user() : null,
                'total_item_count'      => $data['items_count'],
                'total_qty_ordered'     => $data['items_qty'],
                'total_qty_ordered'     => $data['items_qty'],
                'order_currency_code'   => $result['cart_currency_code'],
                'grand_total'           => $data['grand_total'],
                'base_grand_total'      => $data['base_grand_total'],
                'sub_total'             => $data['sub_total'],
                'base_sub_total'        => $data['base_sub_total'],
                'discount_amount'       => $data['discount_amount'],
                'base_discount_amount'  => $data['base_discount_amount'],
                'fees_percent'          => getFeesPercent(),
                'fees_amount'           => $data['fees_amount'],
                'base_fees_amount'      => $data['base_fees_amount'],
                'exchange_rate'         => $user->getExchangeRate(),
                'address'               => Arr::except($result['address'], ['id', 'cart_id']),
                // 'payment'               => Arr::except($result['payment'], ['id', 'cart_id']),
            ];

            foreach ($data['items'] as $item) {
                $finalData['items'][] = $this->prepareDataForOrderItem($item);
            }

            $finalDataArray[] = $finalData;
        }
        return $finalDataArray;
    }

    /**
     * Prepares data for order item.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareDataForOrderItem($data): array
    {
        $finalData = [
            'product'              => $this->productRepository->find($data['product_id']),
            'sku'                  => $data['sku'],
            'type'                 => $data['type'],
            'product_type'         => $data['product_type'],
            'name'                 => $data['name'],
            'qty_ordered'          => $data['quantity'],
            'price'                => $data['price'],
            'base_price'           => $data['base_price'],
            'total'                => $data['total'],
            'base_total'           => $data['base_total'],
            'discount_percent'     => $data['discount_percent'],
            'discount_amount'      => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            // 'additional'           => is_array($data['additional']) ? $data['additional'] : [],
        ];


        return $finalData;
    }

    /**
     * Returns cart details in array.
     *
     * @return array
     */
    public function toArray()
    {
        $cart = $this->getCart();

        $data = $cart->toArray();

        $data['address'] = $cart->address->toArray();

        // $data['payment'] = $cart->payment->toArray();

        $data['items'] = $cart->items()->select('cart_items.*', 'products.user_id')
            ->join('products', 'products.id', 'cart_items.product_id')
            ->get()->toArray();

        return $data;
    }

    /**
     * Returns true, if cart item is inactive.
     *
     * @param \App\Models\CartItem $item
     * @return bool
     */
    private function isCartItemInactive(\App\Models\CartItem $item): bool
    {
        static $loadedCartItem = [];

        if (array_key_exists($item->product_id, $loadedCartItem)) {
            return $loadedCartItem[$item->product_id];
        }

        return $loadedCartItem[$item->product_id] = $item->product ? $item->product->isCartItemInactive($item) : false;
    }

    /**
     * Fill user attributes.
     *
     * @return array
     */
    private function fillUserAttributes(): array
    {
        $attributes = [];

        $user = auth()->guard('api')->user();

        if ($user) {
            $attributes['first_name'] = $user->first_name;
            $attributes['last_name'] = $user->last_name;
            $attributes['email'] = $user->email;
            $attributes['user_id'] = $user->id;
        }

        return $attributes;
    }

    /**
     * Fill address attributes.
     *
     * @return array
     */
    private function fillAddressAttributes(array $addressAttributes): array
    {
        $attributes = [];

        $address = new Address();

        foreach ($address->getFillable() as $attribute) {
            if (isset($addressAttributes[$attribute])) {
                $attributes[$attribute] = $addressAttributes[$attribute];
            }
        }

        return $attributes;
    }


    /**
     * Gather billing address.
     *
     * @param  $data
     * @param  $cart
     * @return array
     */
    private function gatherAddress($data, \App\Models\Cart $cart): array
    {
        $userAddress = [];

        if (
            isset($data['address']['address_id'])
            && $data['address']['address_id']
        ) {
            $userAddress = $this
                ->addressRepository
                ->findOneWhere(['id' => $data['address']['address_id']])
                ->toArray();
        }

        $address = array_merge(
            $userAddress,
            $data['address'],
            ['cart_id' => $cart->id],
            $this->fillUserAttributes(),
            $this->fillAddressAttributes($data['address'])
        );

        return $address;
    }
}
