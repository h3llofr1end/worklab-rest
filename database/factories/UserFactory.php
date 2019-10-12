<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Category;
use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Category::class, function (Faker $faker){
    return [
        'title' => $faker->word
    ];
});

$factory->define(Product::class, function (Faker $faker){
    return [
        'title' => $faker->word,
        'description' => $faker->text,
        'price' => $faker->numberBetween(1000, 100000),
        'count' => $faker->numberBetween(1, 10000),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
