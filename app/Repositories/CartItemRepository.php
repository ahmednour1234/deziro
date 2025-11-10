<?php

namespace App\Repositories;

use App\Eloquent\Repository;

class CartItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'App\Models\CartItem';
    }

    /**
     * @param  int  $cartItemId
     * @return int
     */
    public function getProduct($cartItemId)
    {
        return $this->model->find($cartItemId)->product->id;
    }
}
