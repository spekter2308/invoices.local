<?php

use App\Counter;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Company::truncate();
        for($i = 1; $i <= 10; $i++){
            Company::create([
                'name' => $faker->company,
                'short_name' => $faker->word,
                'address' => $faker->address,
                'invoice_notes' => $faker->sentence(rand(15, 30), true),
                'logo_img' => null
            ]);
        }
    }
}
