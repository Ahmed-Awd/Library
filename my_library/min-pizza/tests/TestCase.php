<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        if (!RefreshDatabaseState::$migrated) {
            Artisan::call('migrate:fresh');
            RefreshDatabaseState::$migrated = true;
            Artisan::call('world:init');
            Artisan::call('db:seed --class=DatabaseSeederTest');
        }
    }
}
