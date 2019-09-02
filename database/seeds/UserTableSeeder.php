<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            \App\User::updateOrCreate([
               'name' => 'Admin',
               'email' => 'chance.ck@gmail.com',
               'password' => Hash::make('2.jXyK:Eq68.#w(`'),
            ]);
    }
}
