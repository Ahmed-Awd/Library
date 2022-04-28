<?php

namespace App\Repositories;

use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    private OrderItem $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    public function get($order)
    {
        return $order->orderItems;
    }

    public function show($orderItem)
    {
        return $this->orderItem->where('id', $orderItem->id)->first();
    }

    public function store($data)
    {
        $this->orderItem->create($data);
    }

    public function update($orderItem, $data)
    {
        $orderItem->update($data);
    }
}
