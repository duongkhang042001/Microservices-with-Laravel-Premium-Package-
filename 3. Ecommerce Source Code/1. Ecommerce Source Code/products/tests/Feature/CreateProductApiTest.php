<?php

namespace Tests\Feature;

use App\Models\Category;
use Ecommerce\Common\Enums\Events;
use Ecommerce\Common\Services\RedisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::flushall();
    }

    /** @test */
    public function it_should_return_201()
    {
        $category = Category::factory()->create();
        $response = $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_should_return_422_if_name_missing()
    {
        $category = Category::factory()->create();
        $response = $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'description' => 'Description',
            'price' => 39,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_return_422_if_price_missing()
    {
        $category = Category::factory()->create();
        $response = $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_return_422_if_category_not_exists()
    {
        $response = $this->json('POST', '/api/v1/products', [
            'categoryId' => 1,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_return_the_new_product()
    {
        $category = Category::factory()->create();
        $product = $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ])->json('data');

        $this->assertEquals('Test Product', $product['name']);
        $this->assertEquals('Description', $product['description']);
        $this->assertEquals(29, $product['price']);
        $this->assertEquals($category->id, $product['category']['id']);
    }

    /** @test */
    public function it_should_create_a_new_product()
    {
        $category = Category::factory()->create();
        $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ])->json('data');

        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ]);
    }

    /** @test */
    public function it_should_create_a_product_created_event()
    {
        $category = Category::factory()->create();
        $product = $this->json('POST', '/api/v1/products', [
            'categoryId' => $category->id,
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 29,
        ])->json('data');

        $events = Redis::xRange(
            RedisService::ALL_EVENTS_KEY,
            (int) now()->subHour()->valueOf(),
            (int) now()->valueOf()
        );

        $event = collect($events)->last();
        $data = json_decode($event['event'], true);

        $this->assertEquals(Events::PRODUCT_CREATED, $data['type']);
        $this->assertEquals($product['id'], $data['data']['id']);
    }
}
