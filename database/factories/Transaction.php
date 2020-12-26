<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 30),
        'type' => 'earning',
        'status' => 'success',
        'amount' => $faker->randomElement($array = array (1000,10000)),
        'created_at' => $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now', $timezone = null),
    ];
});
