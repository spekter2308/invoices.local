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
use App\InvoiceSettings;
use PDF;
use App;


class InvoiceController extends Controller
{
    use HasManyRelation;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showForCustomer', 'generatePdf']]);
    }

    public function search(Request $request) {
        $searchParam = $request->input('search');

        $invoices = Invoice::query()
            ->where('number', 'LIKE', "{$searchParam}%")
            ->pluck('number')->toArray();

        $customers = Customer::query()
            ->where('name', 'LIKE', "%{$searchParam}%")
            ->pluck('name')->toArray();

        $companies = Company::query()
            ->where('name', 'LIKE', "%{$searchParam}%")
            ->orWhere('short_name', 'LIKE', "%{$searchParam}%")
            ->pluck('name', 'short_name')->toArray();

        $searched_companies = [];
        foreach ($companies as $short_name => $name) {
            $searched_companies[] = $short_name;
            $searched_companies[] = $name;
        }

        $result = array_merge($invoices, $customers, $searched_companies);

        return \response()->json($result);
    }

    public function getSearch($searchParam)
    {
        $invoices = Invoice::query()
            ->where('number', 'LIKE', "{$searchParam}%")
            ->pluck('id')->toArray();

        $customers = Customer::query()
            ->where('name', 'LIKE', "%{$searchParam}%")
            ->pluck('id')->toArray();

        $companies = Company::query()
            ->where('name', 'LIKE', "%{$searchParam}%")
            ->orWhere('short_name', 'LIKE', "%{$searchParam}%")
            ->pluck('id', 'short_name')->toArray();

        $result = [];
        if (count($invoices)) {
            $result['id'] = implode(',',$invoices);
            return $result;
        }
        if (count($customers)) {
            $result['customer_id'] = implode(',',$customers);
            return $result;
        }
        if (count($companies)) {
            $result['company_id'] = implode(',',$companies);
            return $result;
        }
    }

    public function index(Request $request, InvoiceFilters $filters)
    {
        $getFilters = [];

            if ($request->query->count()) {

                $invoices = $this->getInvoices($filters, $request);

                foreach ($request->query as $key => $filter) {
                    $getFilters[$key] = $filter;
                }
            } else {
                $invoices = Invoice::orderByRaw('CAST(number as UNSIGNED) DESC')->where('status', '!=', 'Archive');
            }

            if ($request->has(['from', 'to'])) {

                $from = Carbon::createFromTimeString($request->from)->setTimezone('Europe/Kiev')->format('Y-m-d H:i:s');
                $to = Carbon::createFromTimeString($request->to)->setTimezone('Europe/Kiev')->format('Y-m-d H:i:s');

                $invoices = $invoices->where('invoice_date', '>=', $from)->where('invoice_date', '<=', $to);
            }



        $invoices_usd = $invoices->get()->where('settings.currency', '=', '$');
        $invoices_euro = $invoices->get()->where('settings.currency', '=', '€');
        $invoices_pound = $invoices->get()->where('settings.currency', '=', '£');

        $allBalanceUsd = $this->getAllBalance($invoices_usd->all());
        $allTotalUsd= $this->getAllTotal($invoices_usd->all());

        $allTotalEuro= $this->getAllTotal($invoices_euro->all());
        $allBalanceEuro= $this->getAllTotal($invoices_euro->all());

        $allTotalPound= $this->getAllTotal($invoices_pound->all());
        $allBalancePound= $this->getAllTotal($invoices_pound->all());

        $perPage = $request->per_page ?: 100;
        $invoices = $invoices->paginate($perPage);
        $finance = [
            'allBalanceUsd' => round($allBalanceUsd, 2),
            'allTotalUsd' => round($allTotalUsd, 2),
            'allBalanceEuro' => round($allBalanceEuro, 2),
            'allTotalEuro' => round($allTotalEuro, 2),
            'allBalancePound' => round($allBalancePound, 2),
            'allTotalPound' => round($allTotalPound, 2)
        ];
        $invoices->allBalanceUsd = $allBalanceUsd;
        $invoices->allTotalUsd = $allTotalUsd;
        $invoices->allBalanceEuro = $allBalanceEuro;
        $invoices->allTotalEuro = $allTotalEuro;
        $invoices->allBalancePound = $allBalancePound;
        $invoices->allTotalPound = $allTotalPound;

        return \response()->json(array('invoices' => $invoices, 'filters' => $getFilters, 'finance' => $finance));
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

            $settings = $invoice->settings;

            $invoiceCustomer = $invoice->customer;
            $invoiceCompany = $invoice->company;
            foreach ($invoice->items as $item) {
                $items = [
                    'item' => $item->item,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unitprice' => $item->unitprice,
                    'itemtax' => $item->itemtax,
                    'dirty' => $item->dirty,
                    'correct' => $item->correct
                ];
                $invoiceItems[] = $items;
            }

            $invoice->duplicateDateFrom = Carbon::now();
            $diffInDays = Carbon::parse($invoice->invoice_date)->diffInDays(Carbon::parse($invoice->due_date));
            $invoice->duplicateDateTo = Carbon::now()->addDays($diffInDays);

            session()->forget('id');
        } else {
            $invoice = '{}';
            $settings = '{}';
            $invoiceCustomer = '{}';
            $invoiceCompany = '{}';
            $invoiceItems = [['item' => '', 'description' => '', 'quantity' => 1, 'unitprice' => 1, 'itemtax' => 0, 'dirty' =>
                false, 'correct' => false]];
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
            'invoice' => $invoice,
            'invoiceCustomer' => $invoiceCustomer,
            'invoiceCompany' => $invoiceCompany,
            'invoiceItems' => $invoiceItems,
            'invoiceNumber' => $invoiceNumber,
            'invoiceFormatNumber' => $counter,
            'invoiceNumbers' => $invoiceNumbers,
            'customers' => $customers,
            'companies' => $companies,
            'mode' => 'create',
            'settings' => $settings
        ]);
    }

    public function store(CreateInvoiceRequest $request)
    {
        $data = $request->input();

        //check for unique invoice number
        $data['selectedInvoiceNumber'] = $this->checkInvoiceNumber($data['selectedInvoiceNumber']);

        $data['selectedDateFrom'] = Carbon::parse($data['selectedDateFrom']);
        $data['selectedDateTo'] = Carbon::parse($data['selectedDateTo']);

        if (is_array($data['selectedCustomer'])) {
            $customer = (new Customer())->create([
                'name' => $data['selectedCustomer']['name'],
                'address' => $data['selectedCustomer']['address']
            ]);
            $customerId = $customer->id;
        } else {
            $customerId = $data['selectedCustomer'];
        }

        $subtotal = collect($data['selectedItems'])->sum(function ($item) {
            return $item['quantity'] * $item['unitprice'];
        });

        $total = collect($data['selectedItems'])->sum(function ($item) {
            return $item['quantity'] * $item['unitprice'] + $item['quantity'] * $item['unitprice']*$item['itemtax']/100;
        });

        $invoice = Invoice::create([
            'number' => $data['selectedInvoiceNumber'],
            'user_id' => auth()->id(),
            'customer_id' => $customerId,
            'company_id' => \request('selectedCompany'),
            'invoice_date' => $data['selectedDateFrom'],
            'due_date' => $data['selectedDateTo'],
            'invoice_notes' => $data['selectedNotes'],
            'amount_paid' => 0,
            'subtotal' => $subtotal,
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

        foreach ($data['selectedSettings'] as $setting) {
            $settings = InvoiceSettings::create([
                'invoice_id' => $invoice->id,
                'currency' => $setting['currency'],
                'show_payment' => $setting['payment'],
                'date_format' => $setting['format'],
                'language' => $setting['language'],
                'show_tax' => $setting['tax']
            ]);
        }

        $tax = $invoice->total - $invoice->subtotal;
        $totalWithoutTax = $invoice->total - $tax;
        if ($invoice->settings->show_tax) {
            $changes = "Invoice created, amount $invoice->balance with tax ($tax)";
        } else {
            $invoice->update([
                'balance' => $invoice->subtotal
            ]);
            $changes = "Invoice created, amount $totalWithoutTax without tax";
        }

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => $changes
        ]);

        return [
            'invoice' => $invoice,
            'settings' => $settings
        ];

    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->settings->date_format == 'dd.MM.yyyy') {
            $format = 'd.m.Y';
        } elseif ($invoice->settings->date_format == 'dd/MM/yyyy') {
            $format = 'd/m/Y';
        } elseif ($invoice->settings->date_format == 'MM/dd/yyyy') {
            $format = 'm/d/Y';
        } elseif ($invoice->settings->date_format == 'dd-MM-yyyy') {
            $format = 'd-m-Y';
        } else {
            $format = 'Y-m-d';
        }
        return view('invoices.show', [
            'invoice'=> $invoice,
            'settings' => $invoice->settings,
            'invoiceItems' => collect($invoice->items),
            'invoiceDate' => Carbon::parse($invoice->invoice_date)->format($format),
            'dueDate' => Carbon::parse($invoice->due_date)->format($format),
        ]);

    }

    public function showForCustomer()
    {
        if (\request()->has('link')) {
            $link = \request()->input('link');
            $id = \Crypt::decryptString($link);
        }

        $invoice = Invoice::find($id);

        if ($invoice->settings->date_format == 'dd.MM.yyyy') {
            $format = 'd.m.Y';
        } elseif ($invoice->settings->date_format == 'dd/MM/yyyy') {
            $format = 'd/m/Y';
        } elseif ($invoice->settings->date_format == 'MM/dd/yyyy') {
            $format = 'm/d/Y';
        } elseif ($invoice->settings->date_format == 'dd-MM-yyyy') {
            $format = 'd-m-Y';
        } else {
            $format = 'Y-m-d';
        }

        return view('invoices.show', [
            'invoice'=> $invoice,
            'settings' => $invoice->settings,
            'invoiceItems' => collect($invoice->items),
            'invoiceDate' => Carbon::parse($invoice->invoice_date)->format($format),
            'dueDate' => Carbon::parse($invoice->due_date)->format($format),
        ]);
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
            'invoiceCustomer' => $invoice->customer,
            'invoiceCompany' => $invoice->company,
            'invoiceItems' => $invoiceItems,
            'invoiceNumber' => $invoice->number,
            'invoiceFormatNumber' => $counter,
            'invoiceNumbers' => $invoiceNumbers,
            'customers' => $customers,
            'companies' => $companies,
            'mode' => 'edit',
            'settings' => $invoice->settings
        ]);
    }

    public function update($id, UpdateInvoiceRequest $request)
    {
        $invoice = Invoice::findOrFail($id);

        $old_total = $invoice->total;
        $previous_show_tax = $invoice->settings->show_tax;
        $old_total_without_tax = $invoice->subtotal;
        $old_invoice_date = Carbon::parse($invoice->invoice_date)->setTimezone('Europe/Kiev')->format('Y-m-d');
        $old_due_date = Carbon::parse($invoice->due_date)->setTimezone('Europe/Kiev')->format('Y-m-d');

        $data = $request->input();

        //check for unique invoice number
        if ($invoice->number != $data['selectedInvoiceNumber']) {
            $data['selectedInvoiceNumber'] = $this->checkInvoiceNumber($data['selectedInvoiceNumber']);
        }

        $data['selectedDateFrom'] = Carbon::parse($data['selectedDateFrom'])->setTimezone('Europe/Kiev')->format('Y-m-d');
        $data['selectedDateTo'] = Carbon::parse($data['selectedDateTo'])->setTimezone('Europe/Kiev')->format('Y-m-d');

        if (is_array($data['selectedCustomer'])) {
            $customer = (new Customer())->create([
                'name' => $data['selectedCustomer']['name'],
                'address' => $data['selectedCustomer']['address']
            ]);
            $customerId = $customer->id;
        } else {
            $customerId = $data['selectedCustomer'];
        }

        $subtotal = collect($data['selectedItems'])->sum(function ($item) {
            return $item['quantity'] * $item['unitprice'];
        });

        $total = collect($data['selectedItems'])->sum(function ($item) {
            return $item['quantity'] * $item['unitprice'] + $item['quantity'] * $item['unitprice']*$item['itemtax']/100;
        });

        $invoice = DB::transaction(function () use ($invoice, $request) {
            $invoice->updateHasMany([
                'items' => $request->selectedItems
            ]);

            return $invoice;
        });

        $settings = $invoice->settings;
        foreach ($data['selectedSettings'] as $setting) {
            $settings->update([
                'invoice_id' => $invoice->id,
                'currency' => $setting['currency'],
                'show_payment' => $setting['payment'],
                'date_format' => $setting['format'],
                'language' => $setting['language'],
                'show_tax' => $setting['tax']
            ]);
        }

        if ($invoice->settings->show_tax) {
            $balance = $total - $invoice->amount_paid;
        } else {
            $balance = $subtotal - $invoice->amount_paid;
        }

        $invoice->update([
            'number' => $data['selectedInvoiceNumber'],
            'customer_id' => $customerId,
            'company_id' => \request('selectedCompany'),
            'invoice_date' => $data['selectedDateFrom'],
            'due_date' => $data['selectedDateTo'],
            'invoice_notes' => $data['selectedNotes'],
            'subtotal' => $subtotal,
            'total' => $total,
            'balance' => $balance,
        ]);

        $tax = $invoice->total - $invoice->subtotal;
        $totalWithoutTax = $invoice->subtotal;

        $date_changes = '';
        if ($old_invoice_date != $data['selectedDateFrom']) {
            $date_changes .= " Invoice Date has been changed from $old_invoice_date to " . $data['selectedDateFrom'];
        }

        if ($old_due_date != $data['selectedDateTo']) {
            $date_changes .= " Due Date has been changed from $old_due_date to " . $data['selectedDateTo'];
        }

        $wasChanged = true;
        if ($invoice->settings->show_tax) {
            if ($previous_show_tax == $invoice->settings->show_tax) {
                if ($old_total == $invoice->total)
                    $wasChanged = false;
                $changes = "Total changed from $old_total to $invoice->total";
            } else {
                $changes = "Total changed from $old_total_without_tax to $invoice->total. Tax was included ($tax)";
            }
        } else {
            if ($previous_show_tax == $invoice->settings->show_tax) {
                if ($old_total_without_tax == $totalWithoutTax)
                    $wasChanged = false;
                $changes = "Total changed from $old_total_without_tax to $totalWithoutTax";
            } else {
                $wasChanged = true;
                $changes = "Total changed from $old_total to $totalWithoutTax. Tax($tax) was deleted";
            }
        }

        if ($wasChanged) {
            InvoiceHistory::create([
                'invoice_id' => $invoice->id,
                'user_id' => auth()->id(),
                'changes' => $changes . $date_changes
            ]);
        } elseif ($date_changes) {
            InvoiceHistory::create([
                'invoice_id' => $invoice->id,
                'user_id' => auth()->id(),
                'changes' => $date_changes
            ]);
        }

        return [
            'invoice' => $invoice,
            'settings' => $settings
        ];
    }

    public function multiDelete()
    {
        $ids = \request()->all();

        foreach ($ids['parameters'] as $id) {
            $invoice = Invoice::find($id);
            if(\Gate::denies('delete', $invoice)){
                session()->flash('flash', 'Access denied. You cann\'t delete selected invoices.');
                return \response('success', 204);
            }
        }

        DB::table('invoices')->whereIn('id', $ids['parameters'])->delete();

        if (\request()->wantsJson()) {
            session()->flash('show-success', 'Invoices has been deleted success.');
            return response(['success'], 204);
        }
        return redirect()
            ->back()
            ->with(['show-success' => 'Invoices has been deleted success.']);
    }

    public function destroy($id)
    {
        if(\Gate::denies('delete', Invoice::find($id))){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t delete invoice.']);
        }

        DB::table('invoices')->where('id', '=', $id)->delete();

        return redirect()
            ->back()
            ->with(['show-success' => 'Invoice has been deleted success.']);
    }

    public function multiChangeInvoicesStatus()
    {
        $attributes = \request()->input('params');

        foreach ($attributes['ids'] as $id) {
            $invoice = Invoice::find($id);

            if(\Gate::denies('update', $invoice)){
                session()->flash('flash', 'Access denied. You cann\'t change statuses for selected invoices.');
                return \response('success', 204);
            }

            $old_status = $invoice->status;
            $invoice->update(['status' => $attributes['status']]);

            if ($old_status != $invoice->status) {
                InvoiceHistory::create([
                    'invoice_id' => $id,
                    'user_id' => auth()->id(),
                    'changes' => "Status changed from $old_status to $invoice->status"
                ]);
            }
        }

        if (\request()->wantsJson()) {
            session()->flash('show-success', 'Statuses changed');
            return \response()->json(['success' => true]);
        }
        return redirect()
            ->back()
            ->with(['show-success' => 'Status changed']);
    }

    public function unitChangeInvoiceStatus($id)
    {
        $attributes = \request()->input();
        $invoice = Invoice::find($id);

        if(\Gate::denies('update', $invoice)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t change payment history.']);
        }

        $old_status = $invoice->status;

        $invoice->update([
            'status' => $attributes['status']
        ]);

        if ($old_status != $invoice->status) {
            InvoiceHistory::create([
                'invoice_id' => $id,
                'user_id' => auth()->id(),
                'changes' => "Status changed from $old_status to $invoice->status"
            ]);
        }

        return redirect()
            ->back()
            ->with(['show-success' => 'Status changed']);
    }

    public function recordPayment($id)
    {
        $invoice = Invoice::findOrFail($id);

        if(\Gate::denies('update', $invoice)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t change payment history.']);
        }

        if ($invoice->settings->date_format == 'dd.MM.yyyy') {
            $format = 'd.m.Y';
        } elseif ($invoice->settings->date_format == 'dd/MM/yyyy') {
            $format = 'd/m/Y';
        } elseif ($invoice->settings->date_format == 'MM/dd/yyyy') {
            $format = 'm/d/Y';
        } elseif ($invoice->settings->date_format == 'dd-MM-yyyy') {
            $format = 'd-m-Y';
        } else {
            $format = 'Y-m-d';
        }
        return view('invoices.record-payment', [
            'invoice'=> $invoice,
            'settings' => $invoice->settings,
            'invoiceItems' => collect($invoice->items),
            'invoiceDate' => Carbon::parse($invoice->invoice_date)->format($format),
            'dueDate' => Carbon::parse($invoice->due_date)->format($format),
            'paymentHistory' => $invoice->payments
        ]);
    }

    public function recordPaymentSave($id)
    {
        $attributes = \request()->validate([
            'invoice_id' => 'required|numeric',
            'date' => 'required',
            'amount' => 'required|numeric',
            'receiving_account' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $attributes['date'] = Carbon::parse($attributes['date']);

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
            'changes' => "Amount changed from $old_balance to $invoice->balance, Status: $status"
        ]);

        if (\request()->wantsJson()) {
            return $invoice;
        }

        return redirect('/invoices/' . $invoice->id);
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

        if ($invoice->settings->show_tax) {
            $amount_payment = $invoice->total - $invoice->amount_paid;
        } else {
            $amount_payment = $invoice->subtotal - $invoice->amount_paid;
        }

        $paymentData = PaymentInvoice::create([
            'invoice_id' => $invoice->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'amount' => $amount_payment,
            'receiving_account' => 'cash',
            'notes' => 'Marked as paid',
        ]);

        $old_paid = $invoice->amount_paid;
        $old_balance = $invoice->balance;

        $invoice->update([
            'status' => 'Paid',
            'balance' => $old_balance - $amount_payment,
            'amount_paid' => $amount_payment + $old_paid,
        ]);

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Invoice marked as paid; Amount changed from $old_balance to $invoice->balance, Status: Paid"
        ]);

        return redirect()->back();
    }

    public function markAsUnpaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $old_balance = $invoice->balance;

        if(\Gate::denies('update', $invoice)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t change statuses.']);
        }

        $last_payment = PaymentInvoice::latest()->where('invoice_id', '=', $id)->where('amount', '!=', 0)->first();

        PaymentInvoice::create([
            'invoice_id' => $invoice->id,
            'date' => Carbon::now(),
            'amount' => -$last_payment->amount,
            'receiving_account' => $last_payment->receiving_account,
            'notes' => 'Marked as unpaid'
        ]);

        $invoice->update([
            'status' => 'Partial',
            'balance' => $old_balance + $last_payment->amount,
            'amount_paid' => $invoice->amount_paid - $last_payment->amount,
        ]);

        InvoiceHistory::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'changes' => "Invoice marked as unpaid; Amount changed from $old_balance to $invoice->balance, Status: Partial"
        ]);

        return redirect()->back();
    }

    protected function getInvoices($filters, $request)
    {
        if ($request->input('result')) {
            $searchResult = $this->getSearch($request->input('result'));
            foreach ($searchResult as $type_id => $ids) {
                $invoices = Invoice::whereIn($type_id, explode(',',$ids))->filter($filters);
            }
        } else {
            $invoices = Invoice::filter($filters);
        }

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
            'periodDate' => 'required|numeric'
        ]);

        if ($validator->fails())
            abort(500);

        $selected = $request->periodDate;

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
                $response['min_date'] = $invoice->min('invoice_date');
                $response['max_date'] = $invoice->max('invoice_date');
                break;
        }

        return \response()->json($response);
    }

    public function generatePdf($invoice, $print = false)
    {
        $invoice = Invoice::findOrFail($invoice);

        $tax = $invoice->total - $invoice->subtotal;
        $balance = $invoice->subtotal - $invoice->amount_paid;

        if ($print) {
            if ($invoice->settings->language == 'english') {
                return view('pdf.invoices', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
            } elseif ($invoice->settings->language == 'germany') {
                return view('pdf.invoices-ge', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
            } else {
                return view('pdf.invoices-sp', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
            }
        } else {
            if ($invoice->settings->language == 'english') {
                $pdf = PDF::loadView('pdf.invoices', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
                //return $pdf->stream('document.pdf');
                return $pdf->download('Invoice ' . $invoice->number . '.pdf');
            } elseif ($invoice->settings->language == 'germany') {
                $pdf = PDF::loadView('pdf.invoices-ge', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
                return $pdf->download('Invoice ' . $invoice->number . '.pdf');
            } else {
                $pdf = PDF::loadView('pdf.invoices-sp', ['invoice' => $invoice, 'tax' => $tax, 'balance' => $balance]);
                return $pdf->download('Invoice ' . $invoice->number . '.pdf');
            }
        }
    }

    /**
     * Get result total for invoices
     *
     * @param $invoices
     */
    protected function getAllTotal($invoices)
    {
        $total = 0;
        foreach ($invoices as $invoice) {
            if ($invoice->settings->show_tax) {
                $total += $invoice->total;
            } else {
                $total += $invoice->subtotal;
            }
        }

        return $total;
    }

    /**
     * Get result balance for invoices
     *
     * @param $invoices
     */
    protected function getAllBalance($invoices)
    {
        $balance = 0;
        foreach ($invoices as $invoice) {
            $balance += $invoice->balance;
        }
        return $balance;
    }
}
