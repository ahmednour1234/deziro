<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use App\Contracts\OrderItem;
use App\Eloquent\Repository;

class OrderItemRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'App\Models\OrderItem';
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \App\Contracts\OrderItem
     */
    public function create(array $data)
    {
        if (
            isset($data['product'])
            && $data['product']
        ) {
            $data['product_id'] = $data['product']->id;
            $data['product_type'] = get_class($data['product']);

            unset($data['product']);
        }

        return parent::create($data);
    }
}
