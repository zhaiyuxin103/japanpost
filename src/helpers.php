<?php

declare(strict_types=1);

if (! function_exists('getClientIP')) {
    function getClientIP()
    {
        // 可能包含客户端 IP 的头部列表
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (! empty($_SERVER[$header])) {
                $ips = explode(',', (string) $_SERVER[$header]);
                $ip  = trim($ips[0]);

                // 验证 IP 地址格式
                if (filter_var($ip, FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // 如果没有找到公网 IP，返回 REMOTE_ADDR
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
