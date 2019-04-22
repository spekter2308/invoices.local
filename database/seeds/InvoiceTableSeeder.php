<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Invoice;
use App\Item;

class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Invoice::truncate();
        Item::truncate();

        foreach (range(1, 20) as $i) {
            $items = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                $unitPrice = $faker->numberBetween(100, 1000);
                $quantity = $faker->numberBetween(1, 10);

                $items->push(new Item([
                    'name' => $faker->name,
                    'description' => $faker->sentence(rand(1, 5), true),
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'tax' => ($faker->numberBetween(5, 10) * $quantity / 100),
                    'amount' => ($unitPrice * $quantity)
                ]));
            }

            $subTotal = $items->sum('amount');
            $allTax = $items->sum('tax');
            $amountPaid = $faker->numberBetween(600, 1300);
            $total = $subTotal + $allTax;
            $balance = $total - $amountPaid;

            $invoice = Invoice::create([
                'number' => $faker->numberBetween(10000, 20000),
                'customer_id' => $faker->numberBetween(1, 10),
                'company_id' => $faker->numberBetween(1, 10),
                'amount_paid' => $amountPaid,
                'balance' => $balance,
                'subtotal' => $subTotal,
                'total' => $total,
                'invoice_date' => $faker->dateTime(),
                'due_date' => $faker->dateTimeBetween('+2 days', '+2 month'),
                'status' => 'Partial'
            ]);

            $invoice->items()->saveMany($items);
        }
    }
}
