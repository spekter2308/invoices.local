<?php

namespace App\Http\Controllers;

use App\InvoiceItemName;
use App\Http\Requests\CreateInvoiceRequest;
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

    public function store(CreateInvoiceRequest $request)
    {

        $data = $request->input();
        if(is_array($data['selectedCustomer'])) {
            $customer = (new Customer())->create([
                'name' => $data['selectedCustomer']['name'],
                'address' => $data['selectedCustomer']['address']
            ]);
            $customerId = $customer->id;
        } else {
            $customerId = $data['selectedCustomer'];
        }

        $total = collect($data['selectedItems'])->sum(function($item) {
            return $item['quantity'] * $item['unitprice'];
        });

        $invoice = Invoice::create([
            'number' => $data['selectedInvoiceNumber'],
            'customer_id' => $customerId,
            'company_id' => \request('selectedCompany'),
            'invoice_date' => \request('selectedDateFrom'),
            'due_date' => \request('selectedDateTo'),
            'amount_paid' => 0,
            'subtotal' => $total,
            'total' => $total,
            'balance' => $total,
            'status' => 'Paid'
        ]);

        /*foreach($data['selectedItems'] as $key => $items) {
            $newItems = [];
            foreach($items as $item) {
                $model = $invoice->{$key}()->getModel();
                $newItems[] = $model->create([
                    'name' => $item['']
                ]);
            }
            // save
            $this->{$key}()->saveMany($newItems);
        }*/

        if(\request()->expectsJson()){
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
