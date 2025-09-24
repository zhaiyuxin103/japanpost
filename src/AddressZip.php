<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;

class AddressZip
{
    public array $guzzleOptions = [];

    public function __construct(
        protected ?string $clientId = null,
        protected ?string $secretKey = null,
        protected string $baseUri = 'https://api.da.pf.japanpost.jp/',
        protected ?string $token = null,
    ) {
        //
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

    public function search(
        array $data,
        int $page = 1,
        int $limit = 1000,
    ) {
        $token    = $this->token ?: (new Token($this->clientId, $this->secretKey))->getToken();
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
