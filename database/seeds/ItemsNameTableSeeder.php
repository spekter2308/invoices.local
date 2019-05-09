<?php

use Illuminate\Database\Seeder;
use App\InvoiceItemName;

class ItemsNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            $itemName = InvoiceItemName::create([
                'name' => 'item-' . $i
            ]);
        }
    }
}
