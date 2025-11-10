<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'vat'
    ];

    /**
     * To get relevant associated items with the cart instance
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class)
            ->whereNull('cart_items.parent_id');
    }

    /**
     * To get all the associated items with the cart instance even the parent and child items of configurable products
     */
    public function all_items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the addresses for the cart.
     */
    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the shipping address for the cart.
     */
    public function address(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->addresses()
            ->where('type', 'cart');
    }

    /**
     * Get shipping address for the cart.
     */
    public function getAddressAttribute()
    {
        return $this->address()->first();
    }

    /**
     * Get the payment associated with the cart.
     */
    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CartPayment::class);
    }

    /**
     * Checks if cart have stockable items
     *
     * @return boolean
     */
    public function haveStockableItems(): bool
    {
        foreach ($this->items as $item) {
            if ($item->product->isStockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if cart has downloadable items
     *
     * @return boolean
     */
    public function hasDownloadableItems(): bool
    {
        foreach ($this->items as $item) {
            if (stristr($item->type, 'downloadable') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if cart has items that allow guest checkout
     *
     * @return boolean
     */
    public function hasGuestCheckoutItems(): bool
    {
        foreach ($this->items as $item) {
            if (!$item->product->getAttribute('guest_checkout')) {
                return false;
            }
        }

        return true;
    }
}
