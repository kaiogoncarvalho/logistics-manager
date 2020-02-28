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

use Laravel\Passport\Client;
use Illuminate\Support\Str;

$factory->define(
    Client::class,
    function (Faker\Generator $faker) {
        return
            [
                'user_id'                => $faker->randomNumber(),
                'name'                   => $faker->firstName,
                'secret'                 => Str::random(40),
                'redirect'               => env('APP_URL'),
                'personal_access_client' => $faker->boolean,
                'password_client'        => $faker->boolean,
                'revoked'                => $faker->boolean,
            ];
    }
);
