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
        $items = ['Expense', 'Hours', 'Product', 'Service'];

        foreach ($items as $item) {
            InvoiceItemName::create([
                'name' => $item
            ]);
        }
    }
}
