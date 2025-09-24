<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Yuxin\Japanpost\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.japanpost', [
            'client_id'  => 'test_client_id',
            'secret_key' => 'test_secret_key',
            'base_uri'   => 'https://api.da.pf.japanpost.jp/',
        ]);
    }
}
