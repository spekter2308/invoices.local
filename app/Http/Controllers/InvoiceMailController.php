<?php

namespace App\Http\Controllers;

use App\InvoiceMail;
use App\Mail\InvoiceSendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Invoice;

class InvoiceMailController extends Controller
{
    public function create($id)
    {
        $invoice = Invoice::findOrFail($id);

        $tax = $invoice->total - $invoice->subtotal;

        if ($invoice->settings->show_tax) {
            return view('invoices.send_mail-with-tax', compact('invoice', 'tax'));
        } else {
            $total = $subtotal = $invoice->total - $tax;
            $balance = $invoice->balance - $tax;

            return view('invoices.send_mail', compact('invoice', 'total', 'subtotal', 'balance'));
        }
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
