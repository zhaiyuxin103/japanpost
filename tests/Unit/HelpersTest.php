<?php

declare(strict_types=1);

test('getClientIP function exists', function (): void {
    expect(function_exists('getClientIP'))->toBeTrue();
});

test('getClientIP returns string', function (): void {
    $result = getClientIP();

    expect($result)->toBeString();
});

test('getClientIP handles different server configurations', function (): void {
    // Test with different HTTP headers that might contain client IP
    $headers = [
        'HTTP_X_FORWARDED_FOR'  => '192.168.1.1',
        'HTTP_X_REAL_IP'        => '192.168.1.2',
        'HTTP_CF_CONNECTING_IP' => '192.168.1.3',
        'REMOTE_ADDR'           => '192.168.1.4',
    ];

    foreach ($headers as $key => $value) {
        $_SERVER[$key] = $value;
        $result        = getClientIP();

        expect($result)->toBeString();

        unset($_SERVER[$key]);
    }
});

test('getClientIP handles multiple IP formats', function (): void {
    $testCases = [
        '192.168.1.1' => 'IPv4 address',
        '10.0.0.1'    => 'Private IPv4 address',
        '127.0.0.1'   => 'Localhost IPv4',
        '2001:db8::1' => 'IPv6 address',
        '::1'         => 'Localhost IPv6',
    ];

    foreach ($testCases as $ip => $description) {
        $_SERVER['HTTP_X_FORWARDED_FOR'] = $ip;
        $result                          = getClientIP();

        expect($result)->toBeString();

        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
    }
});

test('getClientIP handles empty server variables', function (): void {
    // Clear all relevant server variables
    $serverKeys = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR',
    ];

    foreach ($serverKeys as $serverKey) {
        unset($_SERVER[$serverKey]);
    }

    $result = getClientIP();

    expect($result)->toBeString();
});

test('getClientIP is consistent with multiple calls', function (): void {
    $_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.100';

    $result1 = getClientIP();
    $result2 = getClientIP();
    $result3 = getClientIP();

    expect($result1)->toEqual($result2)->toEqual($result3);

    unset($_SERVER['HTTP_X_FORWARDED_FOR']);
});

test('getClientIP handles comma-separated IP list', function (): void {
    $_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.1, 10.0.0.1, 172.16.0.1';

    $result = getClientIP();

    expect($result)->toBeString();

    unset($_SERVER['HTTP_X_FORWARDED_FOR']);
});
