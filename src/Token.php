<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Yuxin\Japanpost\Exceptions\HttpException;

class Token
{
    protected const CACHE_PREFIX = 'japanpost';

    protected CacheInterface $cache;

    protected int $cacheTtl = 3600;

    protected array $guzzleOptions = [];

    public function __construct(
        protected string $clientId,
        protected string $secretKey,
        protected string $baseUri = 'https://api.da.pf.japanpost.jp/',
        ?CacheInterface $cache = null,
    ) {
        $this->cache = $cache ?? new Psr16Cache(new FilesystemAdapter(
            namespace: self::CACHE_PREFIX,
            defaultLifetime: $this->cacheTtl
        ));
    }

    public function getHttpClient(): Client
    {
        return new Client(array_merge($this->guzzleOptions, [
            'base_uri' => $this->baseUri,
        ]));
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->guzzleOptions = $options;
    }

    public function getToken(): string
    {
        $token = $this->cache->get($this->getKey());
        if ($token && is_string($token)) {
            return $token;
        }

        return $this->fetchToken();
    }

    protected function fetchToken(): string
    {
        $response = json_decode($this->getHttpClient()->post('api/v1/j/token', [
            'headers' => [
                'x-forwarded-for' => getClientIP(),
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id'  => $this->clientId,
                'secret_key' => $this->secretKey,
            ],
        ])->getBody()->getContents(), true);

        if (empty($response['token'])) {
            throw new HttpException('Failed to get token');
        }

        $token = $response['token'];

        $this->cache->set($this->getKey(), $token, $this->cacheTtl);

        return $token;
    }

    protected function getKey(): string
    {
        return sprintf('%s.token.%s.%s', self::CACHE_PREFIX, $this->clientId, $this->secretKey);
    }
}
