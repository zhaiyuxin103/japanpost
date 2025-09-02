<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;

class SearchCode
{
    protected array $guzzleOptions = [];

    public function __construct(
        protected ?string $clientId = null,
        protected ?string $secretKey = null,
        protected ?string $token = null
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

    /**
     * 搜索地址
     *
     * @param  int  $choikitype  返回的城镇区域字段是否带括号
     */
    public function search(
        string $code,
        int $page = 1,
        int $limit = 1000,
        int $choikitype = 1,
        int $searchtype = 1
    ): array {
        $token    = $this->token ?: (new Token($this->clientId, $this->secretKey))->getToken();
        $response = json_decode($this->getHttpClient()->get('api/v1/searchcode/' . $code, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'query' => [
                'page'       => $page,
                'limit'      => $limit,
                'choikitype' => $choikitype,
                'searchtype' => $searchtype,
            ],
        ])->getBody()->getContents(), true);

        if (empty($response['addresses'])) {
            throw new AddressesNotFoundException('No addresses found');
        }

        return $response['addresses'];
    }
}
