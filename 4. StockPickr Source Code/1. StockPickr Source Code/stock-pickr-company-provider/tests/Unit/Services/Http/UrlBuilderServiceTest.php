<?php

namespace Tests\Unit\Services\Http;

use App\Services\Http\UrlBuilderService;
use Tests\TestCase;

class UrlBuilderServiceTest extends TestCase
{
    private UrlBuilderService $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->url = resolve(UrlBuilderService::class);
    }

    /** @test */
    public function it_can_handle_slash_after_url()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('uri')
            ->reveal();

        $this->assertEquals('http://base.url/uri', $url);
    }

    /** @test */
    public function it_can_handle_slash_before_uri()
    {
        $url = $this->url
            ->base('http://base.url')
            ->slash('/uri')
            ->reveal();

        $this->assertEquals('http://base.url/uri', $url);
    }

    /** @test */
    public function it_can_handle_multiple_slash()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('/uri')
            ->reveal();

        $this->assertEquals('http://base.url/uri', $url);
    }

    /** @test */
    public function it_can_handle_no_slash()
    {
        $url = $this->url
            ->base('http://base.url')
            ->slash('uri')
            ->reveal();

        $this->assertEquals('http://base.url/uri', $url);
    }

    /** @test */
    public function it_can_resolve_params()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('uri')
            ->with('name', 'value')
            ->reveal();

        $this->assertEquals('http://base.url/uri?name=value', $url);
    }

    /** @test */
    public function it_can_resolve_multiple_params()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('uri')
            ->with('name', 'value')
            ->with('id', '1')
            ->with('price', '1990')
            ->reveal();

        $this->assertEquals('http://base.url/uri?name=value&id=1&price=1990', $url);
    }

    /** @test */
    public function it_can_resolve_multiple_params_in_array()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('uri')
            ->withParams([
                'name'  => 'value',
                'id'    => 1,
                'price' => 1990
            ])
            ->reveal();

        $this->assertEquals('http://base.url/uri?name=value&id=1&price=1990', $url);
    }

    /** @test */
    public function it_can_use_and_as_an_alias_for_with()
    {
        $url = $this->url
            ->base('http://base.url/')
            ->slash('uri')
            ->and('name', 'value')
            ->reveal();

        $this->assertEquals('http://base.url/uri?name=value', $url);
    }
}
