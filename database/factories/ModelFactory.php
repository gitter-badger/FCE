<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Fce\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'school_id' => 1,
        'remember_token' => str_random(10),
    ];
});

$factory->define(Fce\Models\Role::class, function (Faker\Generator $faker) {
    $role = $faker->randomElement(['admin', 'dean', 'executive', 'faculty', 'secretary']);
    return [
        'role' => $role,
        'display_name' => ucfirst($role),
    ];
});

$factory->define(Fce\Models\QuestionSet::class, function (Faker\Generator $faker) {
    return [
        'category' => $faker->word,
        'title' => $faker->sentence,
        'description' => $faker->sentence(10),
    ];
});

$factory->define(Fce\Models\Semester::class, function (Faker\Generator $faker) {
    return [
        'semester' => $faker->word,
    ];
});

$factory->define(Fce\Models\School::class, function (Faker\Generator $faker) {
    return [
        'school' => $faker->word,
        'description' => $faker->sentence(10),
    ];
});

$factory->define(Fce\Models\Question::class, function (Faker\Generator $faker) {
    return [
        'category' => $faker->word,
        'title' => $faker->sentence,
        'description' => $faker->sentence(10),
    ];
});

$factory->define(Fce\Models\Section::class, function (Faker\Generator $faker) {
    return [
        'crn' => $faker->randomNumber(7),
        'course_code' => $faker->word,
        'semester_id' => 1,
        'school_id' => 1,
        'course_title' => $faker->sentence,
        'class_time' => $faker->time(),
        'location' => $faker->sentence,
        'status' => 'Locked',
        'enrolled' => 2,
    ];
});


$factory->define(Fce\Models\Evaluation::class, function (Faker\Generator $faker) {
    for ($idx = 0; $idx < 34; $idx++) {
        if ($idx >= 16) {
            $question_set_id = 2;
        } else {
            $question_set_id = 1;
        }

        $evaluation_scores = $faker->shuffle([1, 0, 1, 0, 0]);
        return [
            'section_id' => 1,
            'question_id' => $idx,
            'question_set_id' => $question_set_id,
            'one' => $evaluation_scores[0],
            'two' => $evaluation_scores[1],
            'three' => $evaluation_scores[2],
            'four' => $evaluation_scores[3],
            'five' => $evaluation_scores[4],
            'comment' => $faker->sentence(5),
        ];
    }
});

$factory->define(Fce\Models\Key::class, function () {
    return [
        'value' => strtoupper(str_random(6)),
        'section_id' => 1,
    ];
});
