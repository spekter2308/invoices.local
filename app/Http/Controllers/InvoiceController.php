<?php

namespace App\Http\Controllers;

use App\InvoiceItemName;
use App\Helper\HasManyRelation;
use App\Http\Requests\CreateInvoiceRequest;
use Illuminate\Http\Request;
use App\Invoice;
use App\Customer;
use App\Company;
use App\Item;
use Http\Env\Response;
use App\Http\Controllers\Controller;
use App\Counter;
use DB;

class InvoiceController extends Controller
{
    use HasManyRelation;

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
        //check for unique invoice number
        $invoiceNumbers = Invoice::all()->sortBy('number')->pluck('number')->toArray();
        $counter = Counter::where(['user_id' => auth()->id()])->first();
        $prefix = $counter->prefix;
        $start = $counter->start;
        $increment = $counter->increment;
        $postfix = $counter->postfix;

        $invoiceNumber = $this->checkInArray($prefix, $start, $increment, $postfix, $invoiceNumbers);

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
        if (is_array($data['selectedCustomer'])) {
            $customer = (new Customer())->create([
                'name' => $data['selectedCustomer']['name'],
                'address' => $data['selectedCustomer']['address']
            ]);
            $customerId = $customer->id;
        } else {
            $customerId = $data['selectedCustomer'];
        }

        $total = collect($data['selectedItems'])->sum(function ($item) {
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
            'status' => 'Draft'
        ]);

        $invoice = DB::transaction(function () use ($invoice, $request) {
            $counter = Counter::where('key', 'invoice')->first();
            dd($counter);
            $invoice->number = $counter->prefix . $counter->value;
            // custom method from app/Helper/HasManyRelation
            $invoice->storeHasMany([
                'items' => $request->items
            ]);
            $counter->increment('value');
            return $invoice;
        });


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

    public function checkInArray($prefix, $start, $increment, $postfix, $array)
    {
        $result = $prefix . strval($start + $increment) . $postfix;
        $newIncrement = $increment + 1;
        if (in_array($result, $array)) {
            return $this->checkInArray($prefix, $start, $newIncrement, $postfix, $array);
        }
        return strval($result);
    }
}
