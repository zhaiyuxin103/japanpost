<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yuxin\Japanpost\Exceptions\HttpException;

class HttpClient
{
    protected ?Client $client = null;

    public function __construct(
        protected string $baseUri = 'https://api.da.pf.japanpost.jp/',
        protected ?array $guzzleOptions = [],
    ) {
        //
    }

    public function getClient(): Client
    {
        return new Client(array_merge($this->guzzleOptions, [
            'base_uri' => $this->baseUri,
        ]));
    }

    public function setOptions(array $options): void
    {
        $this->guzzleOptions = $options;
        // Reset client to apply new options
        $this->client = null;
    }

    public function getOptions(): array
    {
        return $this->guzzleOptions;
    }

    public function request(string $method, string $uri, array $options = [])
    {
        try {
            return $this->getClient()->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get(string $uri, array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    public function post(string $uri, array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    public function put(string $uri, array $options = [])
    {
        return $this->request('PUT', $uri, $options);
    }

    public function delete(string $uri, array $options = [])
    {
        return $this->request('DELETE', $uri, $options);
    }
}
