<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    $data = App\Chapter::select('id')->get();
    $chapter_ids = [];
    foreach($data as $d){
        $chapter_ids[] = $d->id;
    }
    return [
        'question' => $faker->words(10,true) . '?',
        'option1' => $faker->words(5,true) ,
        'option2' => $faker->words(5,true) ,
        'option3' => $faker->words(5,true) ,
        'option4' => $faker->words(5,true) ,
        'answer' => $faker->randomElement(array('1','2','3','4')),
        'chapter_id' => $faker->randomElement($chapter_ids),
    ];
});
