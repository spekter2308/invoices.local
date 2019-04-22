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
        $results = Invoice::with(['customer', 'company'])->paginate(15);

        return response()
            ->json(['results' => $results]);
    }

    public function create()
    {
        $counter = Counter::latest()->first();

       $form = [
            'number' => ($counter->prefix . $counter->start . $counter->postfix),
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

    public function store(Request $request)
    {
        $this->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'company_id' => 'required|integer|exists:companies,id',
            'invoice_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice = new Invoice;

        $invoice->fill($request->except('items'));

        $invoice->sub_total = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $invoice = DB::transaction(function() use ($invoice, $request) {
            $counter = Counter::latest()->first();
            $invoice->number = $counter->prefix . $counter->start . $counter->postfix;


        //custom method from app/Helper/HasManyRelation
        $invoice->storeHasMany([
            'items' => $request->items
        ]);

        $counter->increment('increment');

        return $invoice;

        });

        return response()
            ->json(['saved' => true, 'id' => $invoice->id]);
    }

    public function show($id)
    {
        $model = Invoice::with(['customer', 'company', 'items'])
            ->findOrFail($id);

        return response()
            ->json(['model' => $model]);
    }

    public function edit($id)
    {
        $form = Invoice::with(['customer', 'company', 'items'])
            ->findOrFail($id);

        return response()
            ->json(['form' => $form]);
    }

    public function update($id, Request $request)
    {
        $invoice = Invoice::findOrFail($id);

        $this->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'company_id' => 'required|integer|exists:companies,id',
            'invoice_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|required|integer|exists:items,id,invoice_id,'.$invoice->id,
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice->fill($request->except('items'));

        $invoice->sub_total = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $invoice = DB::transaction(function() use ($invoice, $request) {

            //custom method from app/Helper/HasManyRelation
            $invoice->updateHasMany([
                'items' => $request->items
            ]);

            return $invoice;

        });

        return response()
            ->json(['saved' => true, 'id' => $invoice->id]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->items()->delete();

        $invoice->delete();

        return response()
            ->json(['deleted' => true]);
    }
}
