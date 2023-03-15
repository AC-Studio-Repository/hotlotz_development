<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Modules\InternalAdvert\Models\InternalAdvert;
use Faker\Generator as Faker;

$factory->define(InternalAdvert::class, function (Faker $faker) {
    return [
        'name' => $this->faker->name,
        'description' => $this->faker->realText(),
        'url' => $this->faker->url
    ];;
});
