<?php

namespace App\Repositories;

use App\Eloquent\Repository;
use App\Models\Attribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttributeOptionRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'App\Models\AttributeOption';
    }

    /**
     * @param  array  $data
     * @return  \App\Models\AttributeOption
     */
    public function create(array $data)
    {
        $data = $this->validateUserInput($data);

        $data['user_id'] = Auth::user()->id;

        $option = parent::create($data);

        $this->uploadSwatchImage($data, $option->id);

        return $option;
    }

    public function createOrUpdate(array $data)
    {
        $attribute = Attribute::find($data['attribute_id']);
        $user_id = Auth::user()->id;
        $previousOptionIds = $attribute->options()->where('user_id', $user_id)->pluck('id');

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionInputs) {

                    $optionId = $optionInputs['id'];
                    if (Str::contains($optionId, 'option_')) {
                        $this->create(array_merge([
                            'attribute_id' => $attribute->id,
                            'swatch_type' => $attribute->swatch_type,
                        ], $optionInputs));
                    } else {
                        $optionId = $optionInputs['id'];

                        if (is_numeric($index = $previousOptionIds->search($optionId))) {
                            $previousOptionIds->forget($index);
                        }

                        $this->update(array_merge([
                            'swatch_type' => $attribute->swatch_type,
                        ], $optionInputs), $optionId);
                    }
                }
            }
        }
        foreach ($previousOptionIds as $previousOptionId) {
            $productAttributeValue = ProductAttributeValue::join('attributes', 'attributes.id', 'product_attribute_values.attribute_id')
                ->where('attributes.type', 'select')
                ->where('integer_value', $previousOptionId)->first();
            if (!$productAttributeValue)
                $this->delete($previousOptionId);
        }
        return $attribute->options()->where('user_id', $user_id)->get();
    }

    /**
     * @param  array   $data
     * @param  int     $id
     * @param  string  $attribute
     * @return  \App\Models\AttributeOption
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data = $this->validateUserInput($data);

        $option = parent::update($data, $id);

        $this->uploadSwatchImage($data, $id);

        return $option;
    }

    /**
     * @param  array  $data
     * @param  int  $optionId
     * @return void
     */
    public function uploadSwatchImage($data, $optionId)
    {
        if (
            !isset($data['swatch_value'])
            || !$data['swatch_value']
        ) {
            return;
        }

        if ($data['swatch_value'] instanceof \Illuminate\Http\UploadedFile) {
            parent::update([
                'swatch_value' => $data['swatch_value']->store('attribute_option'),
            ], $optionId);
        }
    }

    /**
     * Validate user input.
     *
     * @param  array  $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if (isset($data['swatch_type']) && $data['swatch_type'] == 'color' && isset($data['color_swatch_value']))
            $data['swatch_value'] = $data['color_swatch_value'];
        else if (isset($data['color_swatch_value']))
            unset($data['color_swatch_value']);

        if (isset($data['swatch_type']) && $data['swatch_type'] == 'image' && isset($data['image_swatch_value']))
            $data['swatch_value'] = $data['image_swatch_value'];
        else if (isset($data['image_swatch_value']))
            unset($data['image_swatch_value']);

        return $data;
    }
}
