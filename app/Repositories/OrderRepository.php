<?php

namespace App\Repositories;

use App\Eloquent\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class OrderRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \App\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'App\Models\Order';
    }

    /**
     * This method will try attempt to a create order.
     *
     * @return \App\Contracts\Order
     */
    public function createOrder(array $data)
    {
        if (
            isset($data['user'])
            && $data['user']
        ) {
            $data['user_id'] = $data['user']->id;
            $data['user_type'] = get_class($data['user']);
        } else {
            unset($data['user']);
        }

        $data['status'] = 'pending';

        $order = $this->model->create($data);
        $order->increment_id = $order->id;
        $order->save();
        // dd($data['payment']);
        $order->payment()->create($data['payment']);

        $order->addresses()->create($data['address']);

        unset($data['address']['user_id']);

        foreach ($data['items'] as $item) {

            $orderItem = $this->orderItemRepository->create(array_merge($item, ['order_id' => $order->id]));
            $orderItem->product->quantity -= $orderItem->qty_ordered;
            $orderItem->product->save();
        }

        return $order;
    }

    /**
     * Create order.
     *
     * @param  array  $data
     * @return \App\Contracts\Order
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            return $this->createOrder($data);
        } catch (\Exception $e) {
            /* rolling back first */
            DB::rollBack();

            /* storing log for errors */
            Log::error($e);

            return $this->createOrder($data);
        } finally {
            /* commit in each case */
            DB::commit();
        }
        return null;
    }
}
