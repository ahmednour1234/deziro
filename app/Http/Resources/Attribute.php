<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Attribute extends JsonResource
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
            // 'code' => $this->code,
            'name' => $this->name,
            // 'type' => $this->type,
            // 'position' => $this->position,
            // 'is_required' => $this->is_required,
            // 'is_unique' => $this->is_unique,
            // 'validation' => $this->validation,
            // 'value_per_locale' => $this->value_per_locale,
            // 'value_per_user' => $this->value_per_user,
            // 'is_filterable' => $this->is_filterable,
            // 'is_configurable' => $this->is_configurable,
            // 'is_visible_on_front' => $this->is_visible_on_front,
            // 'is_user_defined' => $this->is_user_defined,
            // 'swatch_type' => $this->swatch_type,
            'selected_options' => isset($this->selected_options) ? $this->selected_options : null,
            'options' => AttributeOption::collection($this->options),
        ];
    }
}
