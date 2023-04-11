<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupedOrder extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cart = $this->cart;
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'user_id' => $this->user_id,
            'is_sold' => $this->user_id == auth()->user()->id,
            'user_email' => $this->user_email,
            'user_first_name' => $this->user_first_name,
            'user_last_name' => $this->user_last_name,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'total_item_count' => number_format($cart->items_count),
            'total_qty_ordered' => number_format($cart->items_qty),
            'exchange_rate' => $cart->exchange_rate,
            'order_currency_code' => $cart->order_currency_code,
            'grand_total' => number_format($cart->grand_total, 4, '.', ''),
            'sub_total' => number_format($cart->sub_total, 4, '.', ''),
            'fees_percent'  => getFeesPercent(),
            'fees_amount' => number_format($cart->fees_amount, 4, '.', ''),
            'items' => CartItem::collection($cart->items),
            'address' => new Address($this->getAddressAttribute()),
            'payment' => new OrderPayment($this->payment),
            'rate' => number_format($this->rate),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
