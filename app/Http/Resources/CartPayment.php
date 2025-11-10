<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartPayment extends JsonResource
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
            'method' => $this->method,
            'method_title' => $this->method_title,
            // 'ltn' => $this->ltn,
            // 'receipt' => $this->receipt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
