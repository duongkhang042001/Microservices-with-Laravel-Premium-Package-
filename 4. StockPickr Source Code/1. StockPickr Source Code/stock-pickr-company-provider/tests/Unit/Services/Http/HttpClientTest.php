<?php

namespace Tests\Unit\Services\Http;

use App\Services\Http\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class HttpClientTest extends TestCase
{
    /** @test */
    public function it_should_send_a_get_request()
    {
        $this->mock(Client::class, function ($mock) {
            $mock
                ->shouldReceive('get')
                ->once()
                ->withArgs(['http://base.url/endpoint?token=123', []])
                ->andReturns(new Response(200, ['X-foo' => 'Bar'], 'response'));
        });

        /** @var HttpClient $service */
        $service = resolve(HttpClient::class);
        $service->setBaseUrl('http://base.url');
        $service->setApiToken('123');

        $response = $service->get('endpoint');
    }
}
