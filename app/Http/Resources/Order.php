<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'cart_id' => $this->cart_id,
            'user_id' => $this->user_id,
            'is_sold' => $this->user_id == auth()->user()->id,
            'user_email' => $this->user_email,
            'user_first_name' => $this->user_first_name,
            'user_last_name' => $this->user_last_name,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'coupon_id' => $this->coupon_id,
            'total_item_count' => number_format($this->total_item_count),
            'total_qty_ordered' => number_format($this->total_qty_ordered),
            'exchange_rate' => $this->exchange_rate,
            'order_currency_code' => $this->order_currency_code,
            'grand_total' => number_format($this->grand_total, 4, '.', ''),
            // 'base_grand_total' => number_format($this->base_grand_total, 4, '.', ''),
            'sub_total' => number_format($this->sub_total, 4, '.', ''),
            'discount_amount' => number_format($this->discount_amount, 4, '.', ''),
            'discount_percent' => number_format($this->discount_percent, 4, '.', ''),
            // 'base_sub_total' => number_format($this->base_sub_total, 4, '.', ''),
            'fees_percent'  => getFeesPercent(),
            'fees_amount' => number_format($this->fees_amount, 4, '.', ''),
            // 'base_fees_amount' => number_format($this->base_fees_amount, 4, '.', ''),
            'vat' => (($this->sub_total - $this->discount_amount) * 0.11),
            'items' => OrderItem::collection($this->items),
            'address' => new Address($this->getAddressAttribute()),
            'payment' => new OrderPayment($this->payment),
            'rate' => number_format($this->rate),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
