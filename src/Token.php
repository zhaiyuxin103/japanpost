<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;

class Token
{
    protected array $guzzleOptions = [];

    public function __construct(
        protected string $clientId,
        protected string $secretKey
    ) {
        //
    }

    public function getHttpClient(): Client
    {
        return new Client(array_merge($this->guzzleOptions, [
            'base_uri' => 'https://stub-qz73x.da.pf.japanpost.jp/',
        ]));
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->guzzleOptions = $options;
    }

    public function getToken()
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

        return $response['token'];
    }
}
