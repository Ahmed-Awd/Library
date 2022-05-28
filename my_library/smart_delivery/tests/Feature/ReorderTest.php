<?php

namespace Tests\Feature;

use App\Services\OrderService;
use Tests\TestCase;

class ReorderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        settings()->setAll([
            'initial_price' => 5,
            'additional_price' => 2,
            'taken_percentage_from_delivery' => 25,
        ]);
    }

    public function test_not_enough_balance()
    {
        $result = resolve(OrderService::class)->reorder($this->getOrderObject(100, 24));

        $this->assertEquals('You don\'t have enough balance!', $result["message"]);
    }

    public function test_reorder_successfully()
    {
        $result = resolve(OrderService::class)->reorder($this->getOrderObject(100, 2500));

        $this->assertEquals('Reordered successfully', $result["message"]);
    }

    public function getOrderObject($deliveryPrice, $balance)
    {
        return (object) [
            'id' => 1,
            'delivery_price' => $deliveryPrice,
            'store' => (object)[
                'owner_id' => 1,
                'owner' => (object)[
                    'balance' => $balance
                ]
            ],
        ];
    }
}
