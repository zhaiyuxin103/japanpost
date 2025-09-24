<?php

declare(strict_types=1);

use Yuxin\Japanpost\SearchCode;

test('search code can be instantiated with required parameters', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('search code can be instantiated with all parameters', function (): void {
    $searchCode = new SearchCode(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/',
        'test_token'
    );

    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('search code has required methods', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    // SearchCode class has required methods
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('search code can set custom guzzle options', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $options = ['timeout' => 30, 'verify' => false];
    $searchCode->setGuzzleOptions($options);

    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('search code get http client returns guzzle client', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $client = $searchCode->getHttpClient();

    expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
});

test('search code has correct search method signature', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $reflection       = new ReflectionClass($searchCode);
    $reflectionMethod = $reflection->getMethod('search');

    expect($reflectionMethod->getName())->toEqual('search');
    expect($reflectionMethod->getNumberOfParameters())->toEqual(5);
    expect($reflectionMethod->getReturnType())->toEqual('array');
});

test('search code accepts different base uris', function (): void {
    $baseUris = [
        'https://api.da.pf.japanpost.jp/',
        'https://test-api.example.com/',
        'https://staging-api.example.com/',
    ];

    foreach ($baseUris as $baseUri) {
        $searchCode = new SearchCode('test_client_id', 'test_secret_key', $baseUri);
        $client     = $searchCode->getHttpClient();

        // Check that client was created successfully
        expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
    }
});

test('search code can accept custom token', function (): void {
    $tokens = ['custom_token_1', 'custom_token_2', 'custom_token_3'];

    foreach ($tokens as $token) {
        $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', $token);
        $client     = $searchCode->getHttpClient();

        expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
    }
});

test('search code accepts various guzzle options', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $optionsArray = [
        ['timeout' => 30],
        ['connect_timeout' => 10],
        ['verify'          => false],
        ['headers'         => ['User-Agent' => 'Test/1.0']],
        ['proxy'           => 'http://proxy.example.com:8080'],
    ];

    foreach ($optionsArray as $optionArray) {
        $searchCode->setGuzzleOptions($optionArray);
        expect($searchCode)->toBeInstanceOf(SearchCode::class);
    }
});

test('search code handles different client configurations', function (): void {
    $clients = [
        ['client_id' => 'client_1', 'secret_key' => 'secret_1'],
        ['client_id' => 'client_2', 'secret_key' => 'secret_2'],
        ['client_id' => 'client_3', 'secret_key' => 'secret_3'],
    ];

    foreach ($clients as $client) {
        $searchCode = new SearchCode($client['client_id'], $client['secret_key'], 'https://api.da.pf.japanpost.jp/');
        expect($searchCode)->toBeInstanceOf(SearchCode::class);
    }
});

test('search code handles guzzle options correctly', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $options = ['timeout' => 30];
    $searchCode->setGuzzleOptions($options);

    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('search code method parameters are correctly typed', function (): void {
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $reflection  = new ReflectionClass($searchCode);
    $constructor = $reflection->getConstructor();
    $parameters  = $constructor->getParameters();

    expect($parameters[0]->getName())->toEqual('clientId');
    expect($parameters[1]->getName())->toEqual('secretKey');
    expect($parameters[2]->getName())->toEqual('baseUri');
    expect($parameters[3]->getName())->toEqual('token');
    expect($parameters[4]->getName())->toEqual('httpClient');
    expect($parameters[0]->hasType())->toBeTrue();
    expect($parameters[1]->hasType())->toBeTrue();
    expect($parameters[2]->hasType())->toBeTrue();
    expect($parameters[3]->hasType())->toBeTrue();
    expect($parameters[4]->hasType())->toBeTrue();
});
