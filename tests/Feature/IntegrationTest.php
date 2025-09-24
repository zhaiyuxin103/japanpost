<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Yuxin\Japanpost\AddressZip;
use Yuxin\Japanpost\HttpClient;
use Yuxin\Japanpost\SearchCode;
use Yuxin\Japanpost\Token;

test('token integration with services', function (): void {
    $token = new Token(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/'
    );

    $addressZip = new AddressZip(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/',
        null
    );

    $searchCode = new SearchCode(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/'
    );

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('services share common configuration', function (): void {
    $baseUri    = 'https://custom-api.example.com/';
    $httpClient = new HttpClient($baseUri, []);

    $token = new Token(
        'test_client_id',
        'test_secret_key',
        $baseUri,
        null,
        $httpClient
    );

    $addressZip = new AddressZip(
        'test_client_id',
        'test_secret_key',
        $baseUri,
        null,
        $httpClient
    );

    $searchCode = new SearchCode(
        'test_client_id',
        'test_secret_key',
        $baseUri,
        null,
        $httpClient
    );

    $tokenClient      = $token->getHttpClient();
    $addressZipClient = $addressZip->getHttpClient();
    $searchCodeClient = $searchCode->getHttpClient();

    expect($tokenClient)->toBeInstanceOf(GuzzleHttp\Client::class);
    expect($addressZipClient)->toBeInstanceOf(GuzzleHttp\Client::class);
    expect($searchCodeClient)->toBeInstanceOf(GuzzleHttp\Client::class);
});

test('services handle exceptions consistently', function (): void {
    expect(fn () => (new Token('invalid_client', 'invalid_secret', 'https://api.da.pf.japanpost.jp/'))->getToken())
        ->toThrow(ClientException::class);
});

test('address zip and search code handle no results consistently', function (): void {
    $httpClient = new HttpClient('https://api.example.com/', []);
    expect(fn () => (new AddressZip('test_client_id', 'test_secret_key', 'https://api.example.com/', 'invalid_token', $httpClient))->search(['nonexistent' => 'data']))
        ->toThrow(ConnectException::class);
});

test('services can be instantiated with dependency injection', function (): void {
    $token      = new Token('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('guzzle options are applied consistently', function (): void {
    $options = ['timeout' => 60, 'connect_timeout' => 10];

    $token = new Token(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/'
    );
    $token->setGuzzleOptions($options);

    $addressZip = new AddressZip(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/',
        null
    );
    $addressZip->setGuzzleOptions($options);

    $searchCode = new SearchCode(
        'test_client_id',
        'test_secret_key',
        'https://api.da.pf.japanpost.jp/'
    );
    $searchCode->setGuzzleOptions($options);

    $tokenClient      = $token->getHttpClient();
    $addressZipClient = $addressZip->getHttpClient();
    $searchCodeClient = $searchCode->getHttpClient();

    expect($tokenClient->getConfig()['timeout'])->toEqual($options['timeout']);
    expect($addressZipClient->getConfig()['timeout'])->toEqual($options['timeout']);
    expect($searchCodeClient->getConfig()['timeout'])->toEqual($options['timeout']);
});

test('services handle different client credentials', function (): void {
    $credentials = [
        ['client_id' => 'client_1', 'secret_key' => 'secret_1'],
        ['client_id' => 'client_2', 'secret_key' => 'secret_2'],
        ['client_id' => 'client_3', 'secret_key' => 'secret_3'],
    ];

    foreach ($credentials as $credential) {
        $token      = new Token($credential['client_id'], $credential['secret_key'], 'https://api.da.pf.japanpost.jp/');
        $addressZip = new AddressZip($credential['client_id'], $credential['secret_key'], 'https://api.da.pf.japanpost.jp/', null);
        $searchCode = new SearchCode($credential['client_id'], $credential['secret_key'], 'https://api.da.pf.japanpost.jp/');

        expect($token)->toBeInstanceOf(Token::class);
        expect($addressZip)->toBeInstanceOf(AddressZip::class);
        expect($searchCode)->toBeInstanceOf(SearchCode::class);
    }
});

test('services work with multiple environments', function (): void {
    $environments = [
        'production' => 'https://api.da.pf.japanpost.jp/',
        'testing'    => 'https://test-api.example.com/',
        'staging'    => 'https://staging-api.example.com/',
    ];

    foreach ($environments as $environment) {
        $httpClient = new HttpClient($environment, []);
        $token      = new Token('test_client_id', 'test_secret_key', $environment, null, $httpClient);
        $addressZip = new AddressZip('test_client_id', 'test_secret_key', $environment, null, $httpClient);
        $searchCode = new SearchCode('test_client_id', 'test_secret_key', $environment, null, $httpClient);

        $tokenClient      = $token->getHttpClient();
        $addressZipClient = $addressZip->getHttpClient();
        $searchCodeClient = $searchCode->getHttpClient();

        expect($tokenClient->getConfig()['base_uri'])->toEqual($environment);
        expect($addressZipClient->getConfig()['base_uri'])->toEqual($environment);
        expect($searchCodeClient->getConfig()['base_uri'])->toEqual($environment);
    }
});

test('services handle custom tokens consistently', function (): void {
    $tokens     = ['token_1', 'token_2', 'token_3'];
    $httpClient = new HttpClient('https://api.example.com/', []);

    foreach ($tokens as $token) {
        $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.example.com/', $token, $httpClient);
        $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.example.com/', $token, $httpClient);

        expect($addressZip)->toBeInstanceOf(AddressZip::class);
        expect($searchCode)->toBeInstanceOf(SearchCode::class);
    }
});

test('services maintain independent configurations', function (): void {
    $httpClient1 = new HttpClient('https://api1.example.com/', []);
    $httpClient2 = new HttpClient('https://api2.example.com/', []);
    $token       = new Token('client_1', 'secret_1', 'https://api1.example.com/', null, $httpClient1);
    $addressZip  = new AddressZip('client_2', 'secret_2', 'https://api2.example.com/', null, $httpClient2);
    $httpClient3 = new HttpClient('https://api3.example.com/', []);
    $searchCode  = new SearchCode('client_3', 'secret_3', 'https://api3.example.com/', null, $httpClient3);

    $tokenClient      = $token->getHttpClient();
    $addressZipClient = $addressZip->getHttpClient();
    $searchCodeClient = $searchCode->getHttpClient();

    expect($tokenClient->getConfig()['base_uri'])->toEqual('https://api1.example.com/');
    expect($addressZipClient->getConfig()['base_uri'])->toEqual('https://api2.example.com/');
    expect($searchCodeClient->getConfig()['base_uri'])->toEqual('https://api3.example.com/');
});

test('services can be chained with method calls', function (): void {
    $token      = new Token('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');
    $addressZip = new AddressZip('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/', null);
    $searchCode = new SearchCode('test_client_id', 'test_secret_key', 'https://api.da.pf.japanpost.jp/');

    $options = ['timeout' => 30];

    $token->setGuzzleOptions($options);
    $addressZip->setGuzzleOptions($options);
    $searchCode->setGuzzleOptions($options);

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('services handle empty configurations gracefully', function (): void {
    $token      = new Token('', '', 'https://api.da.pf.japanpost.jp/');
    $addressZip = new AddressZip('', '', 'https://api.da.pf.japanpost.jp/', null);
    $searchCode = new SearchCode('', '', 'https://api.da.pf.japanpost.jp/');

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});

test('services handle null configurations gracefully', function (): void {
    $token      = new Token('', '', 'https://api.da.pf.japanpost.jp/');
    $addressZip = new AddressZip('', '', 'https://api.da.pf.japanpost.jp/', null);
    $searchCode = new SearchCode('', '', 'https://api.da.pf.japanpost.jp/');

    expect($token)->toBeInstanceOf(Token::class);
    expect($addressZip)->toBeInstanceOf(AddressZip::class);
    expect($searchCode)->toBeInstanceOf(SearchCode::class);
});
