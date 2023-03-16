<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $boost_ends_in = '';
        if ($this->boosted_at && $this->boost > 0) {
            $startDate = Carbon::now();
            $endDate = Carbon::parse($this->boosted_at)->addDays($this->boost);
            $days = $startDate->diffInDays($endDate);
            $hours = $startDate->copy()->addDays($days)->diffInHours($endDate);
            $minutes = $startDate->copy()->addDays($days)->addHours($hours)->diffInMinutes($endDate);
            $boost_ends_in = $days . 'd ' .  $hours . 'h ' .  $minutes . 'm';
        }

        $exchange_rate = $this->user->isStore() &&  $this->user->vendor_exchange_rate > 0 ? $this->user->vendor_exchange_rate : 60000;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'product_type' => $this->product_type,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'quantity' => $this->totalQuantity(),
            'price' => $this->getMinimalPrice(),
            'formated_price' => currency($this->getMinimalPrice()),
            'lbp_price' => convertPrice($this->getMinimalPrice(), 'LBP', $exchange_rate),
            'lbp_formated_price' => currency($this->getMinimalPrice(), 'LBP', $exchange_rate),
            // 'special_price' => $this->getMinimalPrice(),
            // 'formated_special_price' => currency($this->getMinimalPrice()),
            'description' => $this->description,
            'status' => $this->status,
            $this->mergeWhen($this->product_type == 'simple', [
                'variants' => null,
            ]),
            $this->mergeWhen($this->product_type == 'configurable', [
                'variants' => Product::collection($this->variants),
            ]),
            'images' => $this->images()->count() ? ProductImage::collection($this->images) : null,
            'attributes' => count($this->attributesToArray()) ?  $this->attributesToArray() : null,
            'permutation' => $this->permutation() ? $this->permutation() : null,
            'filterable_attributes' => auth()->user() && count($this->getProductUserFilterAttributes()) ?  $this->getProductUserFilterAttributes() : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
