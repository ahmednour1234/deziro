<?php

namespace App\Repositories;

use App\Eloquent\Repository;
use App\Models\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductAttributeValueRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'App\Models\ProductAttributeValue';
    }

    /**
     * @param  string  $column
     * @param  int  $attributeId
     * @param  int  $productId
     * @param  string  $value
     * @return boolean
     */
    public function isValueUnique($productId, $attributeId, $column, $value)
    {
        $result = $this->resetScope()->model->where($column, $value)->where('attribute_id', '=', $attributeId)->where('product_id', '!=', $productId)->get();

        return !$result->count();
    }
}
