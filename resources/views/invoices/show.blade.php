@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>View Invoice</h1>
                               <button class="btn btn-link">
                                   Show Customization Options
                               </button>
                           </span>

                        </div>
                    </div>
                </div>

                {{--Top buttons--}}
                <div class="level mt-2">
                    <div class="flex">
                        <a href="/invoices/{{ $invoice->id }}/edit" class="btn btn-secondary">Edit</a>
                        <a href="{{route('generate-pdf', ['invoice' => $invoice->id, 'print' => 'print'])}}" target="_blank" class="btn btn-secondary">Print</a>
                        <a href="{{route('generate-pdf', ['invoice' => $invoice->id])}}" class="btn btn-secondary">PDF</a>
                        <a href="/invoice-mail/create/{{ $invoice->id }}" class="btn btn-secondary">Send</a>
                        <a href="#" class="btn btn-secondary">Mark as Paid</a>
                        <a href="#" class="btn btn-secondary">Record Payment</a>
                        <a href="#" class="btn btn-secondary">Duplicate</a>
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
                            <div class="row level">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold">Invoice #</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group d-flex">
                                        <span>{{ $invoice->number }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--Invoice Date-->
                            <div class="row level">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold">Invoice Date</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                       <span>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--Due Date-->
                            <div class="row level">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold">Due Date</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <span>{{ Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
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
                                <div class="invoice-notes-result">
                                    <div class="invoice-notes">
                                        <div class="form-group">
                                            <p>{{ $invoice->company->invoice_notes }}</p>
                                        </div>
                                    </div>
                                    <div class="invoice-total">
                                        <div class="level mt-2">
                                            <h6 class="flex" >Subtotal</h6>
                                            <span>{{ $invoice->subtotal }}</span>
                                        </div>
                                        <div class="border-top pb-2"></div>
                                        <div class="level">
                                            <h6 class="flex" >Total</h6>
                                            <span>{{ $invoice->total }}</span>
                                        </div>
                                        <div class="level">
                                            <h6 class="flex" >Amount Paid</h6>
                                            <span>0</span>
                                        </div>
                                        <div class="border-top pb-2"></div>
                                        <div class="level">
                                            <h6 class="flex" >Balance Due</h6>
                                            <span>{{ $invoice->balance }}</span>
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