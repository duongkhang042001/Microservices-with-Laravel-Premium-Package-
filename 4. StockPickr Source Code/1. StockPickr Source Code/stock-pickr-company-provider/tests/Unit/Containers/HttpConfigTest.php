<?php

namespace App\Tests\Unit\Containers;

use App\Containers\HttpConfig;
use Tests\TestCase;

class HttpConfigTest extends TestCase
{
    /** @test */
    public function it_should_create_a_config()
    {
        $config = new HttpConfig('http://base.url', 'token');

        $this->assertEquals('http://base.url', $config->baseUrl);
        $this->assertEquals('token', $config->apiToken);
    }
}
