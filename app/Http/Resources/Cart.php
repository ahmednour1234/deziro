<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_email' => $this->user_email,
            'user_first_name' => $this->user_first_name,
            'user_last_name' => $this->user_last_name,
            'shipping_method' => $this->shipping_method,
            'items_count' => $this->items_count,
            'items_qty' => $this->items_qty,
            'exchange_rate' => $this->exchange_rate,
            'cart_currency_code' => $this->cart_currency_code,
            'grand_total' => number_format($this->grand_total, 4, '.', ''),
            'total_wrap_as_gift_price' => number_format($this->total_wrap_as_gift_price, 4, '.', ''),
            // 'base_grand_total' => number_format($this->base_grand_total, 4, '.', ''),
            'sub_total' => number_format($this->sub_total, 4, '.', ''),
            'discount_amount' => number_format($this->discount_amount, 4, '.', ''),
            // 'base_sub_total' => number_format($this->base_sub_total, 4, '.', ''),
            'fees_amount' => number_format($this->fees_amount, 4, '.', ''),
            // 'base_fees_amount' => number_format($this->base_fees_amount, 4, '.', ''),
            'vat' => number_format(($this->grand_total * 0.11), 4 , '.' , ''),
            'items' => CartItem::collection($this->items),
            'address' => new Address($this->address),
            'payment' => new CartPayment($this->payment),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
