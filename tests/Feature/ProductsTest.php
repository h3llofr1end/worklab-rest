<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function i_can_get_product_by_id()
    {
        $this->withoutExceptionHandling();
        $product = factory(Product::class)->create();
        $response = $this->get('/api/product/'.$product->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'product'
        ]);
    }

    /** @test */
    public function i_receive_error_when_get_product_by_incorrect_id()
    {
        $response = $this->get('/api/product/9999');
        $response->assertStatus(404);
    }

    /** @test */
    public function unauth_user_cant_create_product()
    {
        $response = $this->post('/api/product', [
            'title' => 'Test product',
            'price' => 1000,
            'count' => 10
        ]);
        $response->assertStatus(401);
    }

    /** @test */
    public function auth_user_can_create_product()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'email' => 'test@mail.ru'
        ]);
        $token = JWTAuth::fromUser($user);
        $response = $this->post('/api/product', [
            'title' => 'Test product',
            'price' => 1000,
            'count' => 10
        ], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['product']);
    }

    /** @test */
    public function auth_user_create_product_with_invalid_data()
    {
        $user = factory(User::class)->create([
            'email' => 'test@mail.ru'
        ]);
        $token = JWTAuth::fromUser($user);
        $response = $this->post('/api/product', [
            'title' => 'Test product',
            'count' => 10
        ], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(302);
    }

    /** @test */
    public function user_can_change_self_product()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create([
            'user_id' => $user->id,
            'price' => 1000
        ]);
        $token = JWTAuth::fromUser($user);
        $response = $this->put('/api/product/'.$product->id, [
            'price' => 1500,
        ], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['product']);
        $this->assertEquals(1500, $product->fresh()->price);
    }

    /** @test */
    public function user_cant_change_product_of_other_user()
    {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create([
            'price' => 1000
        ]);
        $token = JWTAuth::fromUser($user);
        $response = $this->put('/api/product/'.$product->id, [
            'price' => 1500,
        ], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(404);
        $this->assertEquals(1000, $product->fresh()->price);
    }

    /** @test */
    public function user_can_remove_self_product()
    {
        $user = factory(User::class)->create([
            'email' => 'test@mail.ru'
        ]);
        $token = JWTAuth::fromUser($user);
        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->delete('/api/product/'.$product->id, [], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cant_remove_product_of_other_user()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $token = JWTAuth::fromUser($userA);
        $product = factory(Product::class)->create([
            'user_id' => $userB->id
        ]);
        $response = $this->delete('/api/product/'.$product->id, [], ['Authorization' => "Bearer $token"]);
        $response->assertStatus(404);
    }

    /** @test */
    public function unauth_user_cant_delete_products()
    {
        $product = factory(Product::class)->create();
        $response = $this->delete('/api/product/'.$product->id);
        $response->assertStatus(401);
    }
}
