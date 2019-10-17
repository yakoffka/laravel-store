<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Comment;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {

    $created_at = $faker->dateTimeBetween('-1 months', '-1 days');
    $updated_at = rand(1, 9) < 8 ? $created_at : $faker->dateTimeBetween($created_at, 'now');
    $users = User::all();

    return [
        'product_id' => rand(1, config('custom.num_products_seed')),
        // 'user_id' => 0,
        // 'user_name' => $faker->name,
        'user_id' => rand(1, $users->count()),
        'user_name' => '',
        // 'body' => $faker->sentense(rand(5, 9), true),
        'body' => $faker->realText(rand(100, 500)),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
