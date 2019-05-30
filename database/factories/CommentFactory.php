<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {

    $created_at = $faker->dateTimeBetween('-1 months', '-1 days');
    $updated_at = rand(1, 9) < 8 ? $created_at : $faker->dateTimeBetween($created_at, 'now');

    return [
        'product_id' => rand(1, 24),
        'user_id' => 0,
        'user_name' => $faker->name,
        // 'comment_string' => $faker->sentense(rand(5, 9), true),
        'comment_string' => $faker->realText(rand(300, 900)),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
