<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public const ADDRESS_TYPE_USER = 'user';
    public const ADDRESS_TYPE_CART = 'cart';
    public const ADDRESS_TYPE_ORDER = 'order';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array default values
     */
    protected $attributes = [
        'type' => self::ADDRESS_TYPE_USER,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'address_id', 'id');
    }
}
