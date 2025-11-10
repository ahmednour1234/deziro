<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }


    /**
     * Get all the attributes for the attribute groups.
     */
    public function getProductFlatAttribute()
    {
        return $this->product_flat()->first();
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    /**
     * Get the child item.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(static::class, 'id', 'parent_id');
    }

    /**
     * Get the parent item record associated with the cart item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
