<?php

use Illuminate\Database\Seeder;
use App\Counter;

class CounterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Counter::create([
            'user_id' => 1,
            'prefix' => '0000',
            'start' => 0,
            'increment' => 1,
            'postfix' => ''
        ]);
    }
}
