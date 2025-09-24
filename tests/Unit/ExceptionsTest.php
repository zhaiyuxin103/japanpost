<?php

declare(strict_types=1);

use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;
use Yuxin\Japanpost\Exceptions\Exception;
use Yuxin\Japanpost\Exceptions\HttpException;

test('base exception extends php exception', function (): void {
    $exception = new Exception('Test message');

    expect($exception)->toBeInstanceOf(\Exception::class);
    expect($exception->getMessage())->toEqual('Test message');
});

test('http exception extends base exception', function (): void {
    $exception = new HttpException('HTTP error');

    expect($exception)->toBeInstanceOf(Exception::class);
    expect($exception)->toBeInstanceOf(\Exception::class);
    expect($exception->getMessage())->toEqual('HTTP error');
});

test('addresses not found exception extends base exception', function (): void {
    $exception = new AddressesNotFoundException('No addresses found');

    expect($exception)->toBeInstanceOf(Exception::class);
    expect($exception)->toBeInstanceOf(\Exception::class);
    expect($exception->getMessage())->toEqual('No addresses found');
});

it('exceptions can be thrown and caught', function (): void {
    throw new Exception('Test exception');
})->throws(Exception::class, 'Test exception');

it('http exception can be thrown and caught', function (): void {
    throw new HttpException('HTTP failed');
})->throws(HttpException::class, 'HTTP failed');

it('addresses not found exception can be thrown and caught', function (): void {
    throw new AddressesNotFoundException('Addresses not found');
})->throws(AddressesNotFoundException::class, 'Addresses not found');

test('exceptions have correct hierarchy', function (): void {
    $baseException      = new Exception('Base');
    $httpException      = new HttpException('HTTP');
    $addressesException = new AddressesNotFoundException('Addresses');

    expect($baseException)->toBeInstanceOf(\Exception::class);
    expect($httpException)->toBeInstanceOf(Exception::class);
    expect($addressesException)->toBeInstanceOf(Exception::class);
});

test('exceptions can be instantiated with different messages', function (): void {
    $messages = [
        'Error occurred',
        'Something went wrong',
        'Invalid request',
        'Authentication failed',
    ];

    foreach ($messages as $message) {
        $exception = new Exception($message);
        expect($exception->getMessage())->toEqual($message);
    }
});

test('exceptions can be instantiated with codes', function (): void {
    $codes = [400, 401, 403, 404, 500];

    foreach ($codes as $code) {
        $exception = new Exception('Error', $code);
        expect($exception->getCode())->toEqual($code);
    }
});

test('exceptions can accept previous exceptions', function (): void {
    $previous  = new \Exception('Previous error');
    $exception = new Exception('Current error', 0, $previous);

    expect($exception->getPrevious())->toBe($previous);
    expect($exception->getPrevious()->getMessage())->toEqual('Previous error');
});

test('http exception can be used with different http codes', function (): void {
    $httpCodes = [400, 401, 403, 404, 429, 500, 502, 503];

    foreach ($httpCodes as $httpCode) {
        $exception = new HttpException('HTTP Error', $httpCode);
        expect($exception->getCode())->toEqual($httpCode);
    }
});

test('addresses not found exception typically uses 404 code', function (): void {
    $exception = new AddressesNotFoundException('Not found', 404);

    expect($exception->getCode())->toEqual(404);
    expect($exception->getMessage())->toEqual('Not found');
});

test('exception constructor handles various parameter combinations', function (): void {
    // Just message
    $exception1 = new Exception('Message only');
    expect($exception1->getMessage())->toEqual('Message only');
    expect($exception1->getCode())->toEqual(0);

    // Message and code
    $exception2 = new Exception('Message with code', 404);
    expect($exception2->getMessage())->toEqual('Message with code');
    expect($exception2->getCode())->toEqual(404);

    // Message, code, and previous
    $previous   = new \Exception('Previous');
    $exception3 = new Exception('Full parameters', 500, $previous);
    expect($exception3->getMessage())->toEqual('Full parameters');
    expect($exception3->getCode())->toEqual(500);
    expect($exception3->getPrevious())->toBe($previous);
});
