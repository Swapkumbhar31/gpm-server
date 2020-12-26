<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    $minModuleID = App\Module::where('mod_index',App\Module::min('mod_index'))->first()->id;
    $minChapterID = App\Chapter::where('module_id',$minModuleID)->min('chap_index');
    return [
        'name' => $faker->name,
        'profile_img_url' => null,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'contact' => $faker->numberBetween(1000000000,9999999999),
        'api_key' => $faker->uuid,
        'pancard_img_url' => null,
        'referral_code' => str_random(10),
        'chapter_id' => App\Chapter::where('module_id',$minModuleID)->where('chap_index',$minChapterID)->first()->id,
        'remember_token' => str_random(10),
    ];
});
