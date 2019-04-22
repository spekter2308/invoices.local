<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Counter;
use App\Invoice;
use DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $results = Invoice::with(['customer', 'company'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return response()
            ->json(['results' => $results]);
    }

    public function create()
    {
        $counter = Counter::first();

       $form = [
            'number' => ($counter->prefix . $counter->start . $counter->postfix + $counter->increment),
            'customer_id' => null,
            'customer' => null,
            'company_id' => null,
            'company' => null,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'status' => null,
            'amount_paid' => 0,
            'balance' => 0,
            'subtotal' => 0,
            'total' => 0,
            'items' => [
                [
                    'invoice_id' => null,
                    'name' => null,
                    'description' => null,
                    'unit_price' => 0,
                    'quantity' => 0,
                    'tax' => 0,
                    'amount' => 0
                ]
            ]

       ];

        return response()
            ->json(['form' => $form]);
    }
}
