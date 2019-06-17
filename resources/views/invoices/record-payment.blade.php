@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>Record Payment</h1>
                           </span>

                        </div>
                    </div>
                    @include('errors')
                    @include('success')
                </div>
                <payment-details :invoice="{{json_encode($invoice)}}"
                                 :payment-history="{{$paymentHistory}}"></payment-details>
    
                <div class="mt-3 invoice-create-body">
                    <div class="wrapper-invoice-create">
        
                    <!-- {{--Company and Customer part--}} -->
                        <div class="invoice-box invoice-from-to-customer-box">
                            <div class="container">
                                <div class="row justify-content">
                                    <div class="col-md-12">
                                        <div class="company-data-show">
                                            <span>{{ $invoice->company->name }}</span>
                                            <span>{{ $invoice->company->address }}</span>
                                        </div>
                                        <div class="customer-data-show">
                                            <div><a href="/invoices?byuser={{ $invoice->customer->id }}">{{ $invoice->customer->name }}</a></div>
                                            <div>{{ $invoice->customer->address }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                    <!-- {{--Logo part--}} -->
                        <div class="invoice-box invoice-logo-box">
                            <div class="company-logo">
                                <a href="#">
                                    <img src="http://placehold.it/200x50?text=Logo" alt="">
                                </a>
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
                                            <p> NOTES: {{ $invoice->company->invoice_notes }}</p>
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