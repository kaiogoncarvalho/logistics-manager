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

use App\Models\Trip;
use App\Models\Driver;
use App\Models\Truck;

$factory->define(
    Trip::class,
    function (Faker\Generator $faker) {
        return [
            'driver_id' => function () {
                return factory(Driver::class)->create()->id;
            },
            'truck_id'  => function () {
                return factory(Truck::class)->create()->id;
            },
            'loaded'    => $faker->boolean,
            'origin'    => [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ],
            'destiny'   => [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ],
            'trip_date' => $faker->dateTime()->format('Y-m-d H:i:s')
        ];
    }
);

