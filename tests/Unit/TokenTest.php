<?php

declare(strict_types=1);

use Yuxin\Japanpost\Token;

test('token can be instantiated with required parameters', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    expect($token)->toBeInstanceOf(Token::class);
});

test('token can be instantiated with all parameters', function (): void {
    $token = new Token('test_client_id', 'test_secret_key', 'https://api.example.com/');

    expect($token)->toBeInstanceOf(Token::class);
});

test('token has required methods', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    // Token class has required methods
    expect($token)->toBeInstanceOf(Token::class);
});

test('token can set custom guzzle options', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    $options = ['timeout' => 30, 'verify' => false];
    $token->setGuzzleOptions($options);

    // Should not throw exception and options should be set
    expect($token)->toBeInstanceOf(Token::class);
});

test('token get http client returns guzzle client', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    $client = $token->getHttpClient();

    expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
});

test('token handles different base uris', function (): void {
    $baseUris = [
        'https://api.da.pf.japanpost.jp/',
        'https://test-api.example.com/',
        'https://staging-api.example.com/',
    ];

    foreach ($baseUris as $baseUri) {
        $token  = new Token('test_client_id', 'test_secret_key', $baseUri);
        $client = $token->getHttpClient();

        // Check that client was created successfully
        expect($client)->toBeInstanceOf(GuzzleHttp\Client::class);
    }
});

test('token accepts various guzzle options', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    $optionsArray = [
        ['timeout' => 30],
        ['connect_timeout' => 10],
        ['verify'          => false],
        ['headers'         => ['User-Agent' => 'Test/1.0']],
        ['proxy'           => 'http://proxy.example.com:8080'],
    ];

    foreach ($optionsArray as $optionArray) {
        $token->setGuzzleOptions($optionArray);
        expect($token)->toBeInstanceOf(Token::class);
    }
});

test('token method signatures are correct', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    // Test getToken method
    $getTokenMethod = new ReflectionMethod($token, 'getToken');
    expect($getTokenMethod->getNumberOfRequiredParameters())->toBe(0);
    expect($getTokenMethod->getReturnType()?->__toString())->toEqual('string');

    // Test setGuzzleOptions method
    $setGuzzleOptionsMethod = new ReflectionMethod($token, 'setGuzzleOptions');
    expect($setGuzzleOptionsMethod->getNumberOfRequiredParameters())->toBe(1);
    expect($setGuzzleOptionsMethod->getReturnType()?->__toString())->toEqual('void');

    // Test getHttpClient method
    $getHttpClientMethod = new ReflectionMethod($token, 'getHttpClient');
    expect($getHttpClientMethod->getNumberOfRequiredParameters())->toBe(0);
    expect($getHttpClientMethod->getReturnType()?->__toString())->toEqual(GuzzleHttp\Client::class);
});

test('token constructor parameters are correctly typed', function (): void {
    $reflection  = new ReflectionClass(Token::class);
    $constructor = $reflection->getConstructor();
    $parameters  = $constructor->getParameters();

    expect($parameters[0]->getName())->toEqual('clientId');
    expect($parameters[1]->getName())->toEqual('secretKey');
    expect($parameters[2]->getName())->toEqual('baseUri');
    expect($parameters[3]->getName())->toEqual('cache');
    expect($parameters[0]->hasType())->toBeTrue();
    expect($parameters[1]->hasType())->toBeTrue();
    expect($parameters[2]->hasType())->toBeTrue();
    expect($parameters[3]->hasType())->toBeTrue();
});

test('token handles different client credentials', function (): void {
    $credentials = [
        ['client_id' => 'client_1', 'secret_key' => 'secret_1'],
        ['client_id' => 'client_2', 'secret_key' => 'secret_2'],
        ['client_id' => 'client_3', 'secret_key' => 'secret_3'],
    ];

    foreach ($credentials as $credential) {
        $token = new Token($credential['client_id'], $credential['secret_key']);
        expect($token)->toBeInstanceOf(Token::class);
    }
});

test('token maintains cache properties', function (): void {
    $token = new Token('test_client_id', 'test_secret_key');

    $reflection = new ReflectionClass($token);

    expect($reflection->hasProperty('cache'))->toBeTrue();
    expect($reflection->hasProperty('cacheTtl'))->toBeTrue();
    expect($reflection->hasProperty('guzzleOptions'))->toBeTrue();
});
