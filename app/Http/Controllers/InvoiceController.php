<?php

namespace App\Http\Controllers;

use App\Helper\HasManyRelation;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Invoice;
use App\Customer;
use App\Company;
use App\Item;
use Http\Env\Response;
use App\Http\Controllers\Controller;
use App\Counter;
use App\InvoiceItemName;
use DB;
use App\Filters\InvoiceFilters;
use PhpParser\Node\Expr\Cast\Object_;

class InvoiceController extends Controller
{
    use HasManyRelation;


    public function index(Request $request, InvoiceFilters $filters)
    {
        $invoices = $this->getInvoices($filters);

        if ($request->has(['from', 'to'])) {

            $from = Carbon::createFromTimeString($request->from)->format('Y-m-d');
            $to = Carbon::createFromTimeString($request->to)->format('Y-m-d');

            $invoices = $invoices->where('invoice_date', '>=', $from)->where('invoice_date', '<=', $to);
        }

        if (\request()->wantsJson()) {
            return $invoices;
        }

        return view('invoices.index', compact('invoices'));
    }

    protected function getInvoices($filters)
    {
        $invoices = Invoice::latest()->filter($filters);

        $invoices = $invoices->get();
        return $invoices;
    }

    public function create()
    {
        if (auth()->check()) {
            //check for unique invoice number
            $invoiceNumbers = Invoice::all()->sortBy('number')->pluck('number')->toArray();
            $counter = Counter::where(['user_id' => auth()->id()])->first();
            $prefix = $counter->prefix;
            $start = $counter->start;
            $increment = $counter->increment;
            $postfix = $counter->postfix;

            $invoiceNumber = $this->checkInArray($prefix, $start, $increment, $postfix, $invoiceNumbers, $increment);

            $customers = Customer::latest()->get();
            $companies = Company::latest()->get();

            return view('invoices.edit', [
                'invoiceId' => '',
                'invoiceCustomer' => '{}',
                'invoiceCompany' => '{}',
                'invoiceItems' => [
                        [
                        'item' => '',
                        'description' => '',
                        'quantity' => 1,
                        'unitprice' => 1,
                        'dirty' => false,
                        'correct' => false
                    ]
                ],
                'invoiceNumber' => $invoiceNumber,
                'invoiceFormatNumber' => $counter,
                'invoiceNumbers' => $invoiceNumbers,
                'customers' => $customers,
                'companies' => $companies,
                'mode' => 'create'
            ]);
        }

        return view('auth.login', ['registerMessage' => 'If tou does not have account, please Register']);
    }

    public function store(CreateInvoiceRequest $request)
    {
        $data = $request->input();

        //check for unique invoice number
        $data['selectedInvoiceNumber'] = $this->checkInvoiceNumber($data['selectedInvoiceNumber']);

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
            $invoice->storeHasMany([
                'items' => $request->selectedItems
            ]);

            return $invoice;
        });

        return $invoice;
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoiceNumbers = Invoice::all()->sortBy('number')->pluck('number')->toArray();
        $counter = Counter::where(['user_id' => auth()->id()])->first();

        $customers = Customer::latest()->get();
        $companies = Company::latest()->get();

        $invoiceItems = collect($invoice->items);

        return view('invoices.edit', [
            'invoice' => $invoice,
            'invoiceId' => $invoice->id,
            'invoiceCustomer' => $invoice->customer,
            'invoiceCompany' => $invoice->company,
            'invoiceItems' => $invoiceItems,
            'invoiceNumber' => $invoice->number,
            'invoiceFormatNumber' => $counter,
            'invoiceNumbers' => $invoiceNumbers,
            'customers' => $customers,
            'companies' => $companies,
            'mode' => 'edit'
        ]);
    }

    public function update($id, UpdateInvoiceRequest $request)
    {
        $invoice = Invoice::findOrFail($id);

        $data = $request->input();

        //check for unique invoice number
        $data['selectedInvoiceNumber'] = $this->checkInvoiceNumber($data['selectedInvoiceNumber']);

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

        $invoice->update([
            'number' => $data['selectedInvoiceNumber'],
            'customer_id' => $customerId,
            'company_id' => \request('selectedCompany'),
            'invoice_date' => \request('selectedDateFrom'),
            'due_date' => \request('selectedDateTo'),
            'amount_paid' => 0,
            'subtotal' => $total,
            'total' => $total,
            'balance' => $total,
        ]);

        $invoice = DB::transaction(function () use ($invoice, $request) {
            $invoice->updateHasMany([
                'items' => $request->selectedItems
            ]);

            return $invoice;
        });

        return $invoice;
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'status' => 'Paid',
            'balance' => 0,
            'amount_paid' => $invoice->total
        ]);

        return redirect()->back();
    }

    public function selectItem()
    {
        $items = InvoiceItemName::latest()->get();

        return $items;
    }

    public function getSelectItem(InvoiceItemName $invoiceItem)
    {
        $items = $invoiceItem->latest()->paginate(15);
        return view('invoices.table-select-item', ['items' => $items]);
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

        if ($validator->fails()) {
            $request->flash();
            return redirect(route('create-select-item'))->withErrors($validator);
        }

        $status = (!$id) ? $invoiceItem->create($request->all()) : $invoiceItem->find($id)->update($request->all());

        if (!$status) {
            abort(500);
        }

        return redirect(route('get-select-item'))->with(['success' => 'Item has been save']);

    }

    public function deleteSelectItem($id, InvoiceItemName $invoiceItem)
    {
        $status = $invoiceItem->destroy($id);

        if (!$status) {
            abort(500);
        }

        return redirect(route('select-item'))->with(['success' => 'Item has been delete']);
    }

    protected function checkInvoiceNumber($value)
    {
        $invoiceNumbers = Invoice::all()->sortBy('number')->pluck('number')->toArray();
        $counter = Counter::where(['user_id' => auth()->id()])->first();
        $prefix = $counter->prefix;
        $start = $counter->start;
        $increment = $counter->increment;
        $postfix = $counter->postfix;

        if(in_array($value, $invoiceNumbers)) {
            $value = $this->checkInArray($prefix, $start, $increment, $postfix, $invoiceNumbers, $increment);
        }

        return $value;
    }

    protected function checkInArray($prefix, $start, $increment, $postfix, $array, $old_increment)
    {
        $result = $prefix . strval($start + $increment) . $postfix;
        if (in_array($result, $array)) {
            return $this->checkInArray($prefix, $start, $increment + $old_increment, $postfix, $array, $old_increment);
        }
        return strval($result);
    }

    public function getDate(Request $request, Invoice $invoice)
    {
        $response = [];

        $validator = \Validator::make($request->all(), [
            'selected' => 'required|numeric'
        ]);

        if ($validator->fails())
            abort(500);

        $selected = $request->selected;

        switch ($selected) {
            case 1 :
                $response['min_date'] = $invoice->min('invoice_date');
                $response['max_date'] = $invoice->max('invoice_date');
                break;
            case 2 :
                $response['min_date'] = Carbon::now()->startOfMonth();
                $response['max_date'] = Carbon::now()->endOfMonth();
                break;
            case 3 :
                $response['min_date'] = Carbon::now()->subMonth()->startOfMonth();
                $response['max_date'] = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 4 :
                $response['min_date'] = Carbon::now()->startOfYear();
                $response['max_date'] = Carbon::now()->endOfYear();
                break;
            default:
        }

        return \response()->json($response);
    }
}
