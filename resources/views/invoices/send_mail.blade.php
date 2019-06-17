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

http://invoices.local/invoices/{{ $invoice->id }}

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

                <div class="mt-3 invoice-create-body">
                    <div class="wrapper-invoice-create">

                    <!-- {{--Company and Customer part--}} -->
                        <div class="invoice-box invoice-from-to-customer-box">
                            <div class="container">
                                <div class="row justify-content">
                                    <div class="col-md-12">
                                        <div class="company-data-show">
                                            <span><a href="/invoices?bycompany={{ $invoice->company->id }}">{{ $invoice->company->name }}</a></span>
                                            <span>{!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->address))  !!}</span>
                                        </div>
                                        @if ($invoice->status == 'Paid')
                                            <div class="paid"></div>
                                        @endif
                                        <div class="customer-data-show">
                                            <div><a href="/invoices?byuser={{ $invoice->customer->id }}">{{ $invoice->customer->name }}</a></div>
                                            <div>{!! nl2br(str_replace(" ", " &nbsp;", $invoice->customer->address))  !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- {{--Logo part--}} -->
                        <div class="invoice-box invoice-logo-box">
                            <div class="company-logo">
                                @if ($invoice->company->logo_img)
                                    <img src="/upload/company/{{$invoice->company->logo_img}}" class="logo">
                                @endif
                            </div>
                        </div>

                    <!-- {{--Date and Nubmer part--}} -->
                        <div class="invoice-box invoice-num-date-box">
                            <div class="row level text-right">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Invoice #</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span>{{ $invoice->number }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--Invoice Date-->
                            <div class="row level text-right">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Invoice Date</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span>{{ $invoice->invoice_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--Due Date-->
                            <div class="row level text-right">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Due Date</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span>{{ $invoice->due_date }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- {{--Items part--}} -->
                        <div class="invoice-box invoice-item-box">
                            <div class="items-wrapper">

                                <div class="items-table-header-show">

                                    <div class="item-name">
                                        Item
                                    </div>
                                    <div class="item-description">
                                        Description
                                    </div>
                                    <div class="item-unit-price" style="white-space: nowrap">
                                        Unit Price
                                    </div>
                                    <div class="item-quantity">
                                        Quantity
                                    </div>
                                    <div class="item-amount">
                                        Amount
                                    </div>
                                </div>

                                <div class="items-table-body">
                                    @foreach ($invoice->items as $item)
                                        <div class="items-table-row-show">
                                            <div class="item-name">
                                                <div class="form-group-table">
                                                    {{ $item->item }}
                                                </div>
                                            </div>
                                            <div class="item-description">
                                                <div class="form-group-table">
                                                    {{ $item->description }}
                                                </div>
                                            </div>
                                            <div class="item-unit-price">
                                                <div class="form-group-table">
                                                    {{ $item->unitprice }}
                                                </div>
                                            </div>
                                            <div class="item-quantity">
                                                <div class="form-group-table">
                                                    {{ $item->quantity }}
                                                </div>
                                            </div>
                                            <div class="item-total">
                                                <div class="form-group-table">
                                                    {{ $item->unitprice * $item->quantity }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($invoice->settings->show_payment)
                                        <div class="show-payment-for-invoice" style="margin: 3px;">
                                            <div class="show-payment-head">
                                                Payment
                                            </div>
                                            <div class="show-payment-body">
                                                {{ $invoice->amount_paid }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="invoice-table-row-notes">
                                        <div class="form-group">
                                            <p> <span style="text-decoration: underline">NOTES</span>: {!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->invoice_notes))  !!}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-table-result">
                                    <div class="invoice-empty">
                                    </div>

                                    <div class="invoice-total">
                                        <div class="level mt-2">
                                            <h6 class="flex" >Subtotal</h6>
                                            <span>{{ $subtotal . ' ' . $invoice->settings->currency }}</span>
                                        </div>
                                        <div class="border-top pb-2"></div>
                                        <div class="level">
                                            <h6 class="flex" >Total</h6>
                                            <span>{{ $total . ' ' . $invoice->settings->currency }}</span>
                                        </div>
                                        <div class="level">
                                            <h6 class="flex" >Amount Paid</h6>
                                            <span>{{$invoice->amount_paid . ' ' . $invoice->settings->currency}}</span>
                                        </div>
                                        <div class="border-top pb-2"></div>
                                        <div class="level">
                                            <h6 class="flex" >Balance Due</h6>
                                            <span>{{ $balance . ' ' . $invoice->settings->currency }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection