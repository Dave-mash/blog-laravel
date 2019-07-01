<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Cart;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'vendor_id' => 1,
        'buyer_id' => rand(1,3),
        'car_id' => rand(1,3)
    ];
});
