<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AttributeOption extends JsonResource
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
            'name' => $this->name,
            // 'swatch_value' => $this->swatch_value && $this->attribute->swatch_type == 'image' ? Storage::url($this->swatch_value) : $this->swatch_value,
            // 'sort_order' => $this->sort_order,
        ];
    }
}
