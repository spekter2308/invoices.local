@extends('layouts.app')

@section('content')

    @php
    	/** @var \App\Company $company */
    	/** @var \App\Customer $customer */
    @endphp

    <div class="container" v-cloak>
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        
                        <div class="level">
                           <span class="flex">
                               <a href="/invoices" class="btn btn-primary">Back</a>
                               <h1>New Invoice</h1>
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
                            <button class="btn btn-outline-secondary">View</button>
                            <button class="btn btn-outline-secondary">Print</button>
                            <button class="btn btn-outline-secondary">PDF</button>
                            <button class="btn btn-outline-secondary">Send</button>
                            <button class="btn btn-outline-secondary">Mark as Paid</button>
                            <button class="btn btn-outline-secondary">Record Payment</button>
                            <button class="btn btn-outline-secondary">Duplicate</button>
                        </div>

                        <button class="btn btn-primary mr-2">Save</button>
                        <a href="/invoices" class="btn btn-light">Cancel</a>
                    </div>

                <div class="card-header mt-3">

                    <div class="wrapper-invoice-create">

                        {{--Company and Customer part--}}
                        <div class="invoice-box invoice-from-to-customer-box">
                            <div class="container">
                                <company-select :companies="{{ $companies }}" v-on:invoicenotes="getInvoiceNotes"></company-select>
                                <br>
                                <customer-select :customers="{{ $customers }}"></customer-select>

                                    
                            </div>
                        </div>

                        {{--Logo part--}}
                        <div class="invoice-box invoice-logo-box">
                            <form>
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Example file input</label>
                                    <input type="file" class="form-control-file" id="exampleFormControlFile1">
                                </div>
                            </form>
                        </div>

                        {{--Date and Nubmer part--}}
                        <div class="invoice-box invoice-num-date-box">

                           <div class="row level">
                               <div class="col-md-4">
                                   <h6 class="font-weight-bold">Invoice #</h6>
                               </div>
                               <div class="col-md-8">
                                   <div class="form-group">
                                       <input type="number" name="invoice_number" id="invoice_number"
                                              class="form-control" value="{{ $invoiceNumber }}">
                                   </div>
                               </div>
                           </div>
                            <div class="row level">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold">Invoice Date</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="date" name="invoice_date" id="invoice_date"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row level">
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold">Due Date</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="date" name="due_date" id="due_date"
                                               class="form-control"
                                               value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Items part--}}
                        <div class="invoice-box invoice-item-box">

                            @include('invoices.items_table')

                        </div>

                        <div class="invoice-box invoice-notes-box">
                            <div class="form-group">
                               <invoice-notes :notes="notes"></invoice-notes>
                            </div>
                        </div>

                        <div class="invoice-box invoice-total-box">
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h5 class="flex" >Subtotal</h5>
                                <span>0.00</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h5 class="flex" >Total</h5>
                                <span>0.00</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h5 class="flex" >Amount Paid</h5>
                                <span>0.00</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h5 class="flex" >Balance Due</h5>
                                <span>0.00</span>
                            </div>
                            <div class="border-top"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection