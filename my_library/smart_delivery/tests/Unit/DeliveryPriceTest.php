<?php

namespace Tests\Unit;

use App\Repositories\OrderRepositoryInterface;
use App\Services\OrderService;
use App\Services\SettingsServiceInterface;
use PHPUnit\Framework\TestCase;
use Tests\Repositories\OrderRepository;
use Tests\Services\SettingsTestService;

class DeliveryPriceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        app()->bind(SettingsServiceInterface::class, SettingsTestService::class);
        app()->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function test_calcualte_delivery_price_low_distance()
    {
        $intialPrice = 5;
        $initial_distance = 2;
        settings()->set('initial_price', $intialPrice);
        settings()->set('initial_distance', $initial_distance);

        $result = resolve(OrderService::class)->calculateDeliveryPrice(1);

        $this->assertEquals($intialPrice, $result);
    }

    public function test_calcualte_delivery_price_with_additional_distance()
    {
        $intialPrice = 5;
        $additionalPrice = 3;
        $distance = 3;
        settings()->set('initial_price', $intialPrice);
        settings()->set('additional_price', $additionalPrice);

        $result = resolve(OrderService::class)->calculateDeliveryPrice($distance);

        $this->assertEquals($intialPrice + (($distance - 2) * $additionalPrice), $result);
    }
}
