<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'title' => $faker->words(2, true),
        'description' => $faker->words(4, true),
        'notes' => $faker->sentence(6)
    ];
});
