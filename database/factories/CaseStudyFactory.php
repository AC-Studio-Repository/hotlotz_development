<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Modules\CaseStudy\Models\CaseStudy;
use Faker\Generator as Faker;

$factory->define(CaseStudy::class, function (Faker $faker) {
    return [
        'name' => $this->faker->name,
        'abstract' => $this->faker->realText(),
        'link' => $this->faker->url
    ];;
});
