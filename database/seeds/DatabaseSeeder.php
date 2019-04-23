<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InvoiceTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
    }
}
