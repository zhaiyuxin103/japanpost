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

    protected HttpClient $httpClient;

    protected CacheInterface $cache;

    protected int $cacheTtl = 3600;

    public function __construct(
        protected string $clientId,
        protected string $secretKey,
        protected string $baseUri,
        ?CacheInterface $cache = null,
        ?HttpClient $httpClient = null,
    ) {
        $this->cache = $cache ?? new Psr16Cache(new FilesystemAdapter(
            namespace: self::CACHE_PREFIX,
            defaultLifetime: $this->cacheTtl
        ));
        $this->httpClient = $httpClient ?? new HttpClient($this->baseUri);
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient->getClient();
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->httpClient->setOptions($options);
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
