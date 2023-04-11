<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItem extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $base_image = $this->product?->images?->first()?->product_image ?: null;
        $attributes = $this->product?->customAttributesToArray();
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'product_type' => $this->product_type,
            'product_id' => $this->product?->id,
            'name' => $this->name,
            'price' => number_format($this->price, 4, '.', ''),
            // 'base_price' => number_format($this->base_price, 4, '.', ''),
            'total' => number_format($this->total, 4, '.', ''),
            // 'base_total' => number_format($this->base_total, 4, '.', ''),
            'image' => $base_image ? url('storage/' . $base_image) : null,
            'attributes' => count($attributes) ? $attributes : null,
            'permutation' => $this->product?->permutation() ?: null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
