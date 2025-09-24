<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;

class AddressZip
{
    protected HttpClient $httpClient;

    public function __construct(
        protected ?string $clientId,
        protected ?string $secretKey,
        protected string $baseUri,
        protected ?string $token,
        ?HttpClient $httpClient = null,
    ) {
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

    public function search(
        array $data,
        int $page = 1,
        int $limit = 1000,
    ) {
        $token    = $this->token ?: (new Token($this->clientId, $this->secretKey, $this->baseUri))->getToken();
        $response = json_decode($this->getHttpClient()->post('api/v1/addresszip', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'form_params' => array_merge($data, [
                'page'  => $page,
                'limit' => $limit,
            ]),
        ])->getBody()->getContents(), true);

        if (empty($response['addresses'])) {
            throw new AddressesNotFoundException('No addresses found');
        }

        return $response['addresses'];
    }
}
