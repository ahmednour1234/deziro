<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELED = 'canceled';

    protected $guarded = [
        'id',
        'items',
        'address',
        'user',
        'payment',
        'created_at',
        'updated_at',
        'vat'
    ];

    protected $statusLabel = [
        self::STATUS_PENDING         => 'Pending',
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_SHIPPED         => 'Shipped',
        self::STATUS_DELIVERED       => 'Delivered',
        self::STATUS_CANCELED        => 'Canceled',
    ];

    /**
     * Get the order items record associated with the order.
     */
    public function getuserFullNameAttribute(): string
    {
        return $this->user_first_name . ' ' . $this->user_last_name;
    }

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * Get the associated cart that was used to create this order.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)
            ->whereNull('parent_id');
    }

    /**
     * Get the order items record associated with the order.
     */
    public function all_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the user record associated with the order.
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the payment for the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    /**
     * Get the billing address for the order.
     */
    public function address(): HasMany
    {
        return $this->addresses()
            ->where('type', Address::ADDRESS_TYPE_ORDER);
    }

    /**
     * Get billing address for the order.
     */
    public function getAddressAttribute()
    {
        return $this->address()
            ->first();
    }


    /**
     * Checks if order can be canceled or not
     *
     * @return bool
     */
    public function canCancel(): bool
    {

        return true;
    }
}
