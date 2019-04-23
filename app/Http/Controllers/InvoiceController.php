<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Customer;
use App\Company;
use App\Item;
use Http\Env\Response;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index(Invoice $invoice)
    {
        $invoices = $invoice->latest()->with(['company', 'customer'])->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoiceNumber = Invoice::all()->last()->number + 1;

        return view('invoices.create', compact('invoiceNumber'));
    }

    public function store(Request $request)
    {
        $data = $request->input();

        if(\request()->expectsJson()){
            return \response()->json($data);
        }

        return back()->with('message', 'success');

    }
}
