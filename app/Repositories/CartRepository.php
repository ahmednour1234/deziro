<?php

namespace App\Repositories;

use App\Eloquent\Repository;
use Illuminate\Container\Container;

class CartRepository extends Repository
{

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'App\Models\Cart';
    }

    /**
     * Method to detach associations. Use this only with guest cart only.
     *
     * @param  int  $cartId
     * @return bool
     */
    public function deleteParent($cartId)
    {
        return $this->model->destroy($cartId);
    }
}
