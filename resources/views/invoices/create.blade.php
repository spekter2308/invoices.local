@extends('layouts.app')

@section('content')

    @php
    	/** @var \App\Company $company */
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>New Invoice</h1>
                               <button class="btn btn-link">
                                   Show Customization Options
                               </button>
                           </span>

                        </div>
                    </div>
                </div>

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

                        <button class="btn btn-primary">Save</button>
                    </div>

                <div class="card-header mt-3">

                    <div class="wrapper-invoice-create">
                        <div class="invoice-box invoice-from-to-customer-box">
                            <div class="container">
                                <div class="row justify-content">
                                    <div class="col-md-8">

                                        <company-select :companies="{{ $companies }}">
                                        </company-select>

                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content">
                                    <div class="col-md-8">

                                        <customer-select :customers="{{ $customers }}"></customer-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-box invoice-logo-box">
                            <form>
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Example file input</label>
                                    <input type="file" class="form-control-file" id="exampleFormControlFile1">
                                </div>
                            </form>
                        </div>
                        <div class="invoice-box invoice-num-date-box">

                           <div class="row level">
                               <div class="col-md-4">
                                   <h5>Invoice #</h5>
                               </div>
                               <div class="col-md-8">
                                   <div class="form-group">
                                       <input name="invoice_number" id="invoice_number" class="form-control">
                                   </div>
                               </div>
                           </div>
                            <div class="row level">
                                <div class="col-md-4">
                                    <h5>Invoice Date</h5>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input name="invoice_number" id="invoice_number" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row level">
                                <div class="col-md-4">
                                    <h5>Due Date</h5>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input name="invoice_number" id="invoice_number" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-box invoice-item-box">

                        </div>
                        <div class="invoice-box invoice-notes-box">

                        </div>
                        <div class="invoice-box invoice-total-box">

                        </div>


                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection