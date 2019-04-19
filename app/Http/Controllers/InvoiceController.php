<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;

class InvoiceController extends Controller
{
    public function index(Invoice $invoice)
    {
        $invoices = $invoice->latest()->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoiceNumber = Invoice::all()->last()->number + 1;

        return view('invoices.create', compact('invoiceNumber'));
    }
}
