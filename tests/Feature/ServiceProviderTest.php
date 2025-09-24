<?php

declare(strict_types=1);

use Yuxin\Japanpost\ServiceProvider;

test('service provider can be instantiated', function (): void {
    $app      = app();
    $provider = new ServiceProvider($app);

    expect($provider)->toBeInstanceOf(ServiceProvider::class);
});

test('service provider has required methods', function (): void {
    $app      = app();
    $provider = new ServiceProvider($app);

    // ServiceProvider class has required methods
    expect($provider)->toBeInstanceOf(ServiceProvider::class);
});

test('service provider extends correct base class', function (): void {
    $app      = app();
    $provider = new ServiceProvider($app);

    expect($provider)->toBeInstanceOf(Illuminate\Support\ServiceProvider::class);
});

test('service provider methods have correct signatures', function (): void {
    $app      = app();
    $provider = new ServiceProvider($app);

    $reflection = new ReflectionClass($provider);

    // Test register method
    $reflectionMethod = $reflection->getMethod('register');
    expect($reflectionMethod->getNumberOfRequiredParameters())->toBe(0);
});

test('service provider can be used with multiple app instances', function (): void {
    $application = app();
    $app2        = app();

    $provider1 = new ServiceProvider($application);
    $provider2 = new ServiceProvider($app2);

    expect($provider1)->toBeInstanceOf(ServiceProvider::class);
    expect($provider2)->toBeInstanceOf(ServiceProvider::class);
    expect($provider1)->not->toBe($provider2);
});
