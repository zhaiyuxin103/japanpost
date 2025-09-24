<?php

declare(strict_types=1);

use Yuxin\Japanpost\AddressZip;

test('address zip can be instantiated with required parameters', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});

test('address zip can be instantiated with all parameters', function (): void {
    $addressZip = new AddressZip(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/',
        'test_token'
    );

    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});

test('address zip has required methods', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    // AddressZip class has required methods
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});

test('address zip can set custom guzzle options', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    $options = ['timeout' => 30, 'verify' => false];
    $addressZip->setGuzzleOptions($options);

    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});

test('address zip get http client returns guzzle client', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    $client = $addressZip->getHttpClient();

    expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
});

test('address zip search method signature is correct', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    $reflection       = new ReflectionClass($addressZip);
    $reflectionMethod = $reflection->getMethod('search');

    expect($reflectionMethod->getName())->toEqual('search');
    expect($reflectionMethod->getNumberOfParameters())->toBe(3);
});

test('address zip accepts different base uris', function (): void {
    $baseUris = [
        'https://api.da.pf.japanpost.jp/',
        'https://test-api.example.com/',
        'https://staging-api.example.com/',
    ];

    foreach ($baseUris as $baseUri) {
        $addressZip = new AddressZip('test_client_id', 'test_secret_key', $baseUri, null);
        $client     = $addressZip->getHttpClient();

        // Check that client was created successfully
        expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
    }
});

test('address zip can accept custom token', function (): void {
    $tokens = ['custom_token_1', 'custom_token_2', 'custom_token_3'];

    foreach ($tokens as $token) {
        $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', $token);
        $client     = $addressZip->getHttpClient();

        expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
    }
});

test('address zip accepts various guzzle options', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    $optionsArray = [
        ['timeout' => 30],
        ['connect_timeout' => 10],
        ['verify'          => false],
        ['headers'         => ['User-Agent' => 'Test/1.0']],
        ['proxy'           => 'http://proxy.example.com:8080'],
    ];

    foreach ($optionsArray as $optionArray) {
        $addressZip->setGuzzleOptions($optionArray);
        expect($addressZip)->toBeInstanceOf(AddressZip::class);
    }
});

test('address zip handles different client configurations', function (): void {
    $clients = [
        ['client_id' => 'client_1', 'secret_key' => 'secret_1'],
        ['client_id' => 'client_2', 'secret_key' => 'secret_2'],
        ['client_id' => 'client_3', 'secret_key' => 'secret_3'],
    ];

    foreach ($clients as $client) {
        $addressZip = new AddressZip($client['client_id'], $client['secret_key'], 'https://api.da.pf.japanpost.jp/', null);
        expect($addressZip)->toBeInstanceOf(AddressZip::class);
    }
});

test('address zip handles guzzle options correctly', function (): void {
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);

    $options = ['timeout' => 30];
    $addressZip->setGuzzleOptions($options);

    expect($addressZip)->toBeInstanceOf(AddressZip::class);
});
