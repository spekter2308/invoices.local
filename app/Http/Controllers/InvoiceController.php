<?php

namespace App\Http\Controllers;

use App\InvoiceItemName;
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
        $customers = Customer::latest()->get();
        $companies = Company::latest()->get();

        return view('invoices.create', [
            'invoiceNumber' => $invoiceNumber,
            'customers' => $customers,
            'companies' => $companies
        ]);
    }

    public function store(Request $request)
    {

        //$customerId = \request('selectedCustomer');
        //$customer = Customer::findOrFail();

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

        if (\request()->expectsJson()) {
            return \response()->json($invoice);
        }

        return back()->with('message', 'success');

    }

    public function selectItem(InvoiceItemName $invoiceItem)
    {
        $items = $invoiceItem->latest()->paginate(15);
        return view('invoices.table-select-item')->with([
            'items' => $items
        ]);
    }

    public function getSelectItem(InvoiceItemName $invoiceItem)
    {
        $items = $invoiceItem->all();
        return \response()->json($items);
    }

    public function createSelectItem($id = false, InvoiceItemName $invoiceItem)
    {
        $item = (!$id) ? $invoiceItem : $invoiceItem->find($id);

        return view('invoices.create-select-item')->with([
            'item' => $item
        ]);
    }

    public function saveSelectItem($id = false, Request $request, InvoiceItemName $invoiceItem)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
        ]);

        if ($validator->fails())
            return \redirect(route('create-select-item'))->withErrors($validator);

        $status = (!$id) ? $invoiceItem->create($request->all()) : $invoiceItem->find($id)->update($request->all());

        if (!$status) {
            abort(500);
        }

        return redirect(route('select-item'))->with(['success' => 'Item has been save']);

    }

    public function deleteSelectItem($id, InvoiceItemName $invoiceItem)
    {
        $status = $invoiceItem->destroy($id);

        if (!$status) {
            abort(500);
        }

        return redirect(route('select-item'))->with(['success' => 'Item has been delete']);
    }
}
