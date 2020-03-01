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

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */


use App\Enums\{Gender, CNH};
use App\Models\Driver;

$factory->define(
    Driver::class,
    function (Faker\Generator $faker) {
        return
            [
                'name'       => $faker->firstName,
                'cpf'        => $faker->unique()->cpf(false),
                'birth_date' => $faker->date,
                'gender'     => $faker->randomElement(Gender::getAll()),
                'own_truck'  => $faker->boolean,
                'cnh'        => $faker->randomElement(CNH::getAll()),
            ];
    }
);
