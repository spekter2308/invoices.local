@extends('layouts.app')

@section('content')

    <div class="container">
        <form method="POST" action="/invoice-mail/{{ $invoice->id }}">
            @csrf

            @include('errors')
            @include('success')

            <a href="/invoices/{{ $invoice->id }}" class="btn btn-primary">Back</a>

            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_email">To *</label>
                        <input id="customer_email" type="email" name="customer_email"
                               class="form-control {{ $errors->has ('customer_email') ? 'error' : '' }}"
                               placeholder="Please enter customer email *"
                               value="{{ $invoice->customer->email }}"
                              >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="invoice_subject">Subject *</label>
                        <input id="invoice_subject" type="text" name="invoice_subject"
                               class="form-control {{ $errors->has ('invoice_subject') ? 'error' : '' }}"
                               value="Invoice №:{{ $invoice->number }} from {{ $invoice->company->name }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="invoice_message">Message *</label>
                        <textarea id="invoice_message" name="invoice_message"
                                  class="form-control {{ $errors->has ('invoice_message') ? 'error' : '' }}"
                                  rows="8">You have received an invoice from {{ $invoice->company->name }} for {{ $invoice->balance }} $.
To view, print or download a PDF copy of your invoice, click the link below:

http://invoices.local/invoice/invoice-show?link={{ $encrypt }}

Best regards,
{{ $invoice->company->name }}
                        </textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="btn btn-success btn-send" value="Send message">
                </div>
            </div>
        </form>

        <div class="row mt-5">
            <div class="col-md-12">
                <table class="table">
                    <thead class="bg-primary">
                    <tr>
                        <th scope="col">Time Sent</th>
                        <th scope="col">To E-mail Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($invoice->sentMails()->count())
                        @foreach ($invoice->sentMails as $mail)
                            <tr>
                                <td>{{ $mail->created_at }}</td>
                                <td>{{ $mail->email }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>The history of sent mails will be here</td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--Top buttons--}}
                <div class="level mt-2">
                    <div class="flex">
                        <a class="btn btn-secondary" href="/invoices/{{ $invoice->id }}">View</a>
                        <a href="/invoices/{{ $invoice->id }}/edit" class="btn btn-secondary">Edit</a>
                        <a href="{{route('generate-pdf', ['invoice' => $invoice->id, 'print' => 'print'])}}" target="_blank" class="btn btn-secondary">Print</a>
                        <a href="{{route('generate-pdf', ['invoice' => $invoice->id])}}" class="btn btn-secondary">PDF</a>
                        <a href="/invoices/mark-as-paid/{{$invoice->id}}" class="btn btn-secondary">Mark as Paid</a>
                    </div>
                </div>
    
                <invoice-show
                        :default-options="{{ $settings }}"
                        :current-invoice="{{ $invoice }}"
                        :invoice-items="{{ $invoiceItems }}"
                        date-from="{{$invoiceDate}}"
                        date-to="{{$dueDate}}"
                >
    
                </invoice-show>
            </div>
        </div>
    </div>



@endsection