<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPayment extends Model
{
    use HasFactory;

    protected $table = 'order_payment';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public const METHOD_CASH_ON_DELIVERY    = 'cash-on-delivery';
    // public const METHOD_WHISH_TO_WHISH      = 'whish-to-whish';
    // public const METHOD_WHISH_MONEY         = 'whish-money';

    protected $methodTitle = [
        self::METHOD_CASH_ON_DELIVERY   => 'Cash On Delivery',
        // self::METHOD_WHISH_TO_WHISH     => 'Whish To Whish',
        // self::METHOD_WHISH_MONEY        => 'Whish Money',
    ];

    /**
     * Returns the method title from method code
     */
    public function getMethodTitleAttribute()
    {
        return $this->methodTitle[$this->method];
    }
}
