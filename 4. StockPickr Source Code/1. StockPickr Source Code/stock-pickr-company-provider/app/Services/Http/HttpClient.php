<?php

namespace App\Services\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    protected string $baseUrl;
    protected string $apiToken;

    protected Client $client;
    protected UrlBuilderService $urlBuilder;

    public function __construct(Client $client, UrlBuilderService $urlBuilder)
    {
        $this->client = $client;
        $this->urlBuilder = $urlBuilder;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @param array<string, string> $params
     * @param array<string, string> $options
     */
    public function get(string $uri, array $params = [], array $options = [])
    {
        $response = $this->client->get($this->getUrl($uri, $params), $options);
        return $this->parseResponse($response);
    }

    protected function parseResponse(ResponseInterface $response)
    {
        $body = json_decode($response->getBody(), true);

        if (isset($body['data'])) {
            return $body['data'];
        }

        return $body;
    }

    /**
     * @param array<string, string> $params
     */
    protected function getUrl(string $uri, array $params = []): string
    {
        return $this->urlBuilder
            ->base($this->baseUrl)
            ->slash($uri)
            ->with('token', $this->apiToken)
            ->andParams($params)
            ->reveal();
    }
}
