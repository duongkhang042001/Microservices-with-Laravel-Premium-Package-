<?php

namespace App\Services\Http;

use Illuminate\Support\Str;

/*
 |--------------------------------------------------------------------------
 | Url Builder Service
 |--------------------------------------------------------------------------
 |
 | $url->base('http://base.url')
 |     ->slash('endpoint')
 |     ->with('token, 'abc123')
 |     ->and('id', 10)
 |     ->reveal();
 | -> "https://base.url/endpoint?token=abc123&id=10"
 */
class UrlBuilderService
{
    private string $url = '';
    private array $params = [];

    public function base(string $url): UrlBuilderService
    {
        $this->url = $url;
        return $this;
    }

    public function slash(string $uri): UrlBuilderService
    {
        $urlEndsWith = Str::endsWith($this->url, '/');
        $uriStartsWith = Str::startsWith($uri, '/');

        // Mindkettő tartalmaz / jelet
        if ($uriStartsWith && $urlEndsWith) {
            $trimmedUri = Str::substr($uri, 1);
            $this->url .= $trimmedUri;

            return $this;
        }

        // Egyik sem tartalmaz
        if (!$uriStartsWith && !$urlEndsWith) {
            $this->url .= '/' . $uri;
            return $this;
        }

        $this->url .= $uri;
        return $this;
    }

    public function with(string $param, string $value)
    {
        $this->params[$param] = $value;
        return $this;
    }

    public function and(string $param, string $value): UrlBuilderService
    {
        return $this->with($param, $value);
    }

    public function withParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function andParams(array $params)
    {
        return $this->withParams($params);
    }

    public function reveal()
    {
        return $this->url . $this->resolveParams();
    }

    protected function resolveParams(): string
    {
        $params = collect($this->params)
            ->map(fn(string $value, string $param) => "$param=$value")
            ->join('&');

        return $params
            ? "?$params"
            : '';
    }
}
