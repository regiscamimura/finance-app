<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BalanceEntry;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(BalanceEntry::class, function (Faker $faker) {

    $categories = [
        -1 => ['Car Insurance', 'Groceries', 'Rent', 'Utility Bill'],
        1 => ['Refund', 'Salary', 'Lotery']
    ];

    $type = array_rand($categories);
    $amount = $type > 0 ? $faker->randomFloat($nbMaxDecimals = 2, $min = 500, $max = 10000) : $faker->randomFloat($nbMaxDecimals = 2, $min = -10000, $max = -50);
    $date = $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'());

    return [
        'label' => $faker->randomElement($categories[$type]),
        'amount' => $amount,
        'date' => $date
    ];
});
