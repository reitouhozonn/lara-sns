<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->prefecture(),
        'body' => $faker->realtext(),
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
