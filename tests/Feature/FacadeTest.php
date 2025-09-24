<?php

declare(strict_types=1);

use Yuxin\Japanpost\AddressZip;
use Yuxin\Japanpost\Facades\Japanpost;
use Yuxin\Japanpost\Japanpost as JapanpostService;
use Yuxin\Japanpost\SearchCode;
use Yuxin\Japanpost\Token;

test('facade class exists', function (): void {
    expect(class_exists(Japanpost::class))->toBeTrue();
});

test('facade extends laravel facade', function (): void {
    $facade = new Japanpost;

    expect($facade)->toBeInstanceOf(Illuminate\Support\Facades\Facade::class);
});

test('facade has correct accessor', function (): void {
    $reflection       = new ReflectionClass(Japanpost::class);
    $reflectionMethod = $reflection->getMethod('getFacadeAccessor');
    $accessor         = $reflectionMethod->invoke(null);

    expect($accessor)->toEqual('japanpost');
});

test('japanpost service can be resolved from container', function (): void {
    $app = app();

    // Test if the service is registered
    expect($app->has('japanpost'))->toBeTrue();

    $service = $app->make('japanpost');
    expect($service)->toBeInstanceOf(JapanpostService::class);
});

test('japanpost service can access token service', function (): void {
    $service = app('japanpost');
    $token   = $service->token();

    expect($token)->toBeInstanceOf(Token::class);
});

test('japanpost service can access address zip service', function (): void {
    $service    = app('japanpost');
    $addressZip = $service->addressZip();

    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});

test('japanpost service can access search code service', function (): void {
    $service    = app('japanpost');
    $searchCode = $service->searchCode();

    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('japanpost service returns singleton instances', function (): void {
    $service = app('japanpost');

    $token1 = $service->token();
    $token2 = $service->token();

    expect($token1)->toBe($token2);
});

test('japanpost service methods return correct service types', function (): void {
    $service = app('japanpost');

    expect($service->token())->toBeInstanceOf(Token::class);
    expect($service->addressZip())->toBeInstanceOf(AddressZip::class);
    expect($service->searchCode())->toBeInstanceOf(SearchCode::class);
});
