<?php

namespace App\Http\Controllers;

use App\InvoiceMail;
use App\Mail\InvoiceSendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Invoice;
use Carbon\Carbon;

class InvoiceMailController extends Controller
{
    public function create($id)
    {
        $invoice = Invoice::findOrFail($id);

        $encrypt = \Crypt::encryptString($id);

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

            return view('invoices.send_mail', [
                'encrypt' => $encrypt,
                'invoice'=> $invoice,
                'settings' => $invoice->settings,
                'invoiceItems' => collect($invoice->items),
                'invoiceDate' => Carbon::parse($invoice->invoice_date)->format($format),
                'dueDate' => Carbon::parse($invoice->due_date)->format($format),
            ]);
    }

    public function store($id)
    {
        $data = \request()->validate([
            'customer_email' => 'required|email',
            'invoice_subject' => 'required',
            'invoice_message' => 'required'
        ]);

        //$data['url'] = \request()->getHost() . '/invoices/' . $id;

        Mail::to($data['customer_email'])->send(new InvoiceSendMail($data));

        InvoiceMail::create([
            'invoice_id' => $id,
            'email' => $data['customer_email']
        ]);

        return redirect()->back()->with('success', 'Email has been sent succesfully');
    }
}
