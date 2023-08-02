<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::updateOrCreate(['name' => 'Administrator', 'slug' => 'admin']);

        \App\Role::updateOrCreate(['name' => 'Viewer', 'slug' => 'view']);

        \App\Role::updateOrCreate(['name' => 'User ', 'slug' => 'user']);

        \App\User::first()->roles()->attach(1);
    }
}
