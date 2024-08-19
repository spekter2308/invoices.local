<?php

    use Illuminate\Database\Seeder;
    use Faker\Factory;
    use App\Customer;

    class CustomerTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            /*$faker = Factory::create();
            Customer::truncate();
            for($i = 1; $i <= 10; $i++) {
                Customer::create([
                    'name' => $faker->name,
                    'address' => $faker->address,
                    'phone' => $faker->phoneNumber,
                    'email' => $faker->email,
                ]);
            }*/
        }
    }
