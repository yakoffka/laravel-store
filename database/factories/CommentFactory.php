<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */
use App\Comment;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Comment::class, function (Faker $faker) {

    // $created_at = $faker->dateTimeBetween('-1 months', '-1 days');
    // $updated_at = rand(1, 9) < 8 ? $created_at : $faker->dateTimeBetween($created_at, 'now');
    $created_at = Carbon::now();
    $updated_at = Carbon::now();
    $users = User::all();

    return [
        'product_id' => rand(1, config('custom.num_products_seed')),
        'user_id' => rand(1, $users->count()),
        'name' => 'comment #XXX',
        'user_name' => '',
        'body' => $faker->realText(rand(100, 500)),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
