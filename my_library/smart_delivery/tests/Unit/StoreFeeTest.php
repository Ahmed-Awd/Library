<?php

namespace Tests\Unit;

use App\Services\OrderService;
use PHPUnit\Framework\TestCase;

class StoreFeeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        app()->bind(SettingsServiceInterface::class, SettingsTestService::class);
    }

    public function test_zero_price()
    {
        settings()->set('taken_percentage_from_delivery', 25);

        $result = resolve(OrderService::class)->calculateStoreFee(0);

        $this->assertEquals(0, $result);
    }

    public function test_zero_percentage()
    {
        settings()->set('taken_percentage_from_delivery', 0);

        $result = resolve(OrderService::class)->calculateStoreFee(25);

        $this->assertEquals(0, $result);
    }

    public function test_quarter_percentage()
    {
        settings()->set('taken_percentage_from_delivery', 25);

        $result = resolve(OrderService::class)->calculateStoreFee(100);

        $this->assertEquals(25, $result);
    }
}
