<?php

use Faker\Generator as Faker;

$factory->define(App\Module::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'mod_index' => $faker->randomDigitNotNull,
        'description' => $faker->paragraph(10),
    ];
});
