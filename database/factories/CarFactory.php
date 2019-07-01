<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Car;
use Faker\Generator as Faker;

$factory->define(Car::class, function (Faker $faker) {
    return [
        'vendor_id' => 1,
        'make' => 'Toyota',
        'model' => 'Corolla',
        'color' => $faker->colorName,
        'description' => $faker->text($maxNbChars = 150),
        'condition' => rand(0,1) == 1 ? 'new' : 'old',
        'price' => mt_rand(10000000, 99999999),
        'picture' => '13b73edae8443990be1aa8f1a483bc27.jpg'
    ];
});
