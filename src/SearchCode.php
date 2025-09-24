<?php

declare(strict_types=1);

namespace Yuxin\Japanpost;

use GuzzleHttp\Client;
use Yuxin\Japanpost\Exceptions\AddressesNotFoundException;

class SearchCode
{
    protected HttpClient $httpClient;

    public function __construct(
        protected ?string $clientId,
        protected ?string $secretKey,
        protected string $baseUri,
        protected ?string $token = null,
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
        $token    = $this->token ?: (new Token($this->clientId, $this->secretKey, $this->baseUri))->getToken();
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
