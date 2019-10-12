<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function i_can_get_all_categories_by_api()
    {
        $this->withoutExceptionHandling();
        factory(Category::class, 10)->create();
        $response = $this->get('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'count',
            'categories'
        ]);
        $response->assertJson([
            'count' => 10
        ]);
    }

    /** @test */
    public function i_will_get_products_in_category()
    {
        $this->withoutExceptionHandling();
        $categoryA = factory(Category::class)->create();
        $categoryB = factory(Category::class)->create();
        $productA = factory(Product::class)->create()->categories()->sync($categoryA);
        $productB = factory(Product::class)->create()->categories()->sync($categoryB);
        $productC = factory(Product::class)->create()->categories()->sync([$categoryA->id, $categoryB->id]);
        $productD = factory(Product::class)->create()->categories()->sync($categoryA);
        $response = $this->get('/api/categories/'.$categoryA->id.'/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'count',
            'products'
        ]);
        $response->assertJson([
            'count' => 3
        ]);
    }

    /** @test */
    public function if_i_set_illegal_category_i_get_error()
    {
        $response = $this->get('/api/category/9999/products');
        $response->assertStatus(404);
    }
}
