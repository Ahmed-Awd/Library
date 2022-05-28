<?php

namespace Tests\Feature;

use Tests\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use Tests\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\GeoDistance;
use App\Services\OrderService;
use Tests\TestCase;

class OrderCreateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        settings()->setAll([
            'initial_price' => 5,
            'additional_price' => 2,
            'taken_percentage_from_delivery' => 25,
            'initial_distance' => 2,
        ]);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function test_balance_is_zero()
    {
        resolve(GeoDistance::class)->setDistance(5);

        $result = resolve(OrderService::class)->createOrder(
            (object)['lat' => 23.123, 'lng' => 23.123, 'owner_id' => 1, 'owner' => (object)['balance' => 0]],
            ['location' => ['lat' => 23.123, 'lng' => 23.123]]
        );

        $this->assertEquals('Order has been susponded. Balance is not enough.', $result["message"]);
    }

    public function test_balance_is_not_enough()
    {
        resolve(GeoDistance::class)->setDistance(5);

        $result = resolve(OrderService::class)->createOrder(
            (object)['lat' => 23.123, 'lng' => 23.123, 'owner_id' => 1, 'owner' => (object)['balance' => 1]],
            ['location' => ['lat' => 23.123, 'lng' => 23.123]]
        );

        $this->assertEquals('Order has been susponded. Balance is not enough.', $result["message"]);
    }

    public function test_success_order()
    {
        resolve(GeoDistance::class)->setDistance(5);

        $result = resolve(OrderService::class)->createOrder(
            (object)['lat' => 23.123, 'lng' => 23.123, 'owner_id' => 1, 'owner' => (object)['balance' => 200]],
            ['location' => ['lat' => 23.123, 'lng' => 23.123]]
        );
        $this->assertEquals('Order has been created!', $result["message"]);
    }
}
