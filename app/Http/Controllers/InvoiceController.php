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

        if (\request()->wantsJson()) {
            return $invoices;
        }

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoiceNumber = Invoice::all()->last()->number + 1;

        return view('invoices.create', compact('invoiceNumber'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create([
            'number' => \request('selectedInvoiceNumber'),
            'customer_id' => \request('selectedCustomer'),
            'company_id' => \request('selectedCompany'),
            'invoice_date' => \request('selectedDateFrom'),
            'due_date' => \request('selectedDateTo'),
            'amount_paid' => 100,
            'subtotal' => 100,
            'total' => 100,
            'balance' => 100,
            'status' => 'Paid'
        ]);

        if(\request()->expectsJson()){
            return \response()->json($invoice);
        }

        return back()->with('message', 'success');

    }
}
