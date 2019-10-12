<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = new User([
            'name' => 'Test Test',
            'email' => 'test@mail.ru',
            'password' => bcrypt('123456')
        ]);
        $user->save();
    }

    /** @test */
    public function it_will_register_new_user()
    {
        $response = $this->post('api/register', [
            'name' => 'Other Test',
            'email' => 'test2@mail.ru',
            'password' => '123456'
        ]);

        $response->assertJsonStructure([
            'access_token',
        ]);
    }

    /** @test */
    public function it_will_log_a_user_in()
    {
        $response = $this->post('api/login', [
            'email' => 'test@mail.ru',
            'password' => '123456'
        ]);

        $response->assertJsonStructure([
            'access_token',
        ]);
    }

    /** @test */
    public function it_will_not_login_user_with_incorrect_data()
    {
        $response = $this->post('api/login', [
            'email' => 'test@mail.ru',
            'password' => 'incorrect'
        ]);

        $response->assertJsonStructure([
            'error'
        ]);
    }
}
