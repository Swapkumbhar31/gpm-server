<?php

use Faker\Generator as Faker;

$factory->define(App\Chapter::class, function (Faker $faker) {
    $data = App\Module::select('id')->get();
    $module_ids = [];
    foreach($data as $d){
        $module_ids[] = $d->id;
    }
    $module_id = $faker->randomElement($module_ids);
    $chap_index = App\Chapter::where('module_id', $module_id)->max('chap_index');
    return [
        'name' => $faker->sentence,
        'module_id' => $module_id,
        'chap_index' => $faker->randomDigitNotNull,
        'video_id' => $faker->sha1.'.mp4',
        'description' => $faker->paragraph(10),
    ];
});
