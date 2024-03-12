<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class HttpClient
{
    private Client $httpClient;

    public function __construct(private string $baseUri)
    {
        $this->httpClient = new Client([
            'base_uri' => $baseUri
        ]);
    }

    public function get(
        string $uri,
        array $queryParams = []
    ): Collection {
        $content = $this->httpClient
            ->get($uri, ['query' => $queryParams])
            ->getBody()
            ->getContents();

        $data = collect(json_decode(
            $content,
            associative: true
        ));

        return collect(Arr::get($data, 'data', $data));
    }
}
