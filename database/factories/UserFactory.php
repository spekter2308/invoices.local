<?php

use App\User;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(\App\Invoice::class, function (Faker $faker){
    $amountPaid = $faker->numberBetween(100, 300);
    $total = 1500;
    $balance = $total - $amountPaid;

    return [
        'number' => $faker->numberBetween(1, 100),
        'customer_id' => function() {
            return factory(\App\Customer::class)->create()->id;
        },
        'company_id' => function() {
            return factory(\App\Company::class)->create()->id;
        },
        'amount_paid' => $amountPaid,
        'total' => $total,
        'balance' => $balance,
        'currency' => $faker->currencyCode(),
        'invoice_date' => $faker->dateTime(),
        'due_date' => $faker->dateTimeBetween('+2 days', '+2 month'),
        'status' => 'Partial'
    ];
});

$factory->define(\App\Customer::class, function (Faker $faker){
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
    ];
});

$factory->define(\App\Company::class, function(Faker $faker){
    return [
        'name' => $faker->company,
        'short_name' => $faker->word,
        'address' => $faker->address,
        'invoice_notes' => $faker->sentence(rand(5, 10), true),
        'logo_img' => 'no_image.jpg'
    ];
});

$factory->define(\App\Item::class, function(Faker $faker){
    $unitPrice = rand(10, 300);
    $quantity = rand(1, 10);
    $amount = $unitPrice * $quantity;

    return [
        'invoice_id' => function() {
            return factory(\App\Invoice::class)->create()->id;
        },
        'name' => $faker->name,
        'description' => $faker->sentence(rand(1, 3), true),
        'unit_price' => $unitPrice,
        'quantity' => $quantity,
        'amount' => $amount
    ];
});
