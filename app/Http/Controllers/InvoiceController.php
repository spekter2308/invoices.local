<?php

namespace App\Http\Controllers;

use App\Helper\HasManyRelation;
use App\Http\Requests\CreateInvoiceRequest;
use App\PaymentInvoice;
use App\Http\Requests\UpdateInvoiceRequest;
use App\InvoiceHistory;
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
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use PDF;
use App;

class InvoiceController extends Controller
{
    use HasManyRelation;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'generatePdf']]);
    }

    public function index(Request $request, InvoiceFilters $filters)
    {
        $invoices = $this->getInvoices($filters);

        if ($request->has(['from', 'to'])) {

            $from = Carbon::createFromTimeString($request->from)->setTimezone('Europe/Kiev')->format('Y-m-d H:i:s');
            $to = Carbon::createFromTimeString($request->to)->setTimezone('Europe/Kiev')->format('Y-m-d H:i:s');

            $invoices = $invoices->where('invoice_date', '>=', $from)->where('invoice_date', '<=', $to);
        }

        if (\request()->wantsJson()) {
            return $invoices;
        }

        return view('invoices.index', compact('invoices'));
    }

       public function create()
    {
        if(\Gate::denies('create', Invoice::class)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t create invoice.']);
        }

        if(session()->get('id')) {
            $id = session()->get('id');
            $invoice = Invoice::findOrFail($id);

            $invoiceCustomer = $invoice->customer;
            $invoiceCompany = $invoice->company;
            foreach ($invoice->items as $item) {
                $items = [
                    'item' => $item->item,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unitprice' => $item->unitprice,
                    'dirty' => $item->dirty,
                    'correct' => $item->correct
                ];
                $invoiceItems[] = $items;
            }
            //return $invoiceItems;
            session()->forget('id');
        } else {
            $invoiceCustomer = '{}';
            $invoiceCompany = '{}';
            $invoiceItems = [['item' => '', 'description' => '', 'quantity' => 1, 'unitprice' => 1, 'dirty' => false, 'correct' => false]];
        }

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
            'invoiceCustomer' => $invoiceCustomer,
            'invoiceCompany' => $invoiceCompany,
            'invoiceItems' => $invoiceItems,
            'invoiceNumber' => $invoiceNumber,
            'invoiceFormatNumber' => $counter,
            'invoiceNumbers' => $invoiceNumbers,
            'customers' => $customers,
            'companies' => $companies,
            'mode' => 'create'
        ]);
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
            'user_id' => auth()->id(),
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

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Invoice created, amount $invoice->balance"
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

        //dd($invoice->company->invoice_notes);

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);

        if(\Gate::denies('update', $invoice)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t edit invoice.']);
        }

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

        $old_total = $invoice->total;

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

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Total changed from $old_total to $invoice->total"
        ]);

        $invoice = DB::transaction(function () use ($invoice, $request) {
            $invoice->updateHasMany([
                'items' => $request->selectedItems
            ]);

            return $invoice;
        });

        return $invoice;
    }

    public function duplicate($id)
    {
        return redirect('/invoices/create')->with(['id' => $id]);
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);

        if(\Gate::denies('update', $invoice)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t change statuses.']);
        }

        $invoice->update([
            'status' => 'Paid',
            'balance' => 0,
            'amount_paid' => $invoice->total
        ]);

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Invoice marked as paid"
        ]);

        return redirect()->back();
    }

    protected function getInvoices($filters)
    {
        $invoices = Invoice::latest()->filter($filters);

        $invoices = $invoices->get();
        return $invoices;
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

    public function generatePdf($invoice, $print = false)
    {

        $invoice = Invoice::findOrFail($invoice);

        if ($print) {
            return view('pdf.invoices', ['invoice' => $invoice]);
        } else {
            $pdf = PDF::loadView('pdf.invoices', ['invoice' => $invoice]);
            $pdf->SetWatermarkText("Paid");
            //return $pdf->download('Invoice ' . $invoice->number . '.pdf');
            //$pdf = App::make('dompdf.wrapper');
            //$pdf->loadHTML('pdf.invoices', ['invoice' => $invoice]);
            return $pdf->stream('document.pdf');
        }
    }

    public function changeInvoicesStatus(Request $request, Invoice $invoice)
    {
        if (!$request->has(['status', 'invoices']))
            abort(500);

        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric|min:1|max:3',
            'invoices' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);

        }

        $invoices = $invoice->whereIn('id', json_decode($request->invoices))->get();

        switch ($request->status) {
            case 1 :
                $invoices->map(function ($row) {
                    $row->update([
                        'status' => 'Paid',
                        'balance' => 0,
                        'amount_paid' => $row->total
                    ]);
                });
                break;
            case 2 :
                $invoices->map(function ($row) {
                    $row->update([
                        'status' => 'Sent',
                    ]);
                });
                break;
            default:
        }

        return redirect(route('invoice-index'))->with(['success' => 'Status has been save']);
    }

    public function recordPayment($id)
    {

        $invoice = Invoice::findOrFail($id);

        return view('invoices.record-payment', [
            'invoice' => $invoice,
            'paymentHistory' => $invoice->payments
        ]);
    }

    public function recordPaymentSave($id)
    {
        $attributes = \request()->validate([
            'invoice_id' => 'required|numeric',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'receiving_account' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $paymentData = PaymentInvoice::create($attributes);

        $invoice = Invoice::find($id);

        $old_paid = $invoice->amount_paid;
        $old_balance = $invoice->balance;

        if ($invoice->balance <= $paymentData->amount) {
            $status = 'Paid';
        } else {
            $status = 'Partial';
        }

        $invoice->update([
            'status' => $status,
            'balance' => $old_balance - $paymentData->amount ,
            'amount_paid' => $paymentData->amount + $old_paid,
        ]);

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Amount changed from $old_balance to $invoice->balance"
        ]);

        if (\request()->wantsJson()) {
            return $invoice;
        }

        return redirect('/invoices/' . $invoice->id);
    }
}
