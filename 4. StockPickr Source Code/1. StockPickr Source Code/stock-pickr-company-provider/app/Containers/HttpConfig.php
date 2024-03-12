<?php

namespace App\Containers;

/*
 |
 | Egy API providerhez tartozó Http Config tároló osztálya. Ez a config a config/{provider-name}.php -ban elérhető
 |
 */
class HttpConfig
{
    public string $baseUrl;
    public string $apiToken;

    public function __construct(string $baseUrl, string $apiToken)
    {
        $this->baseUrl = $baseUrl;
        $this->apiToken = $apiToken;
    }
}
