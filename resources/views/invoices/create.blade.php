@extends('layouts.app')

@section('content')
    
    @php
        /** @var \App\Company $company */
        /** @var \App\Customer $customer */
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
                    <button form="createInvoice" class="btn btn-primary" type="submit">Save</button>
                </div>

                {{ session('message') }}
                
                <div class="card-header mt-3">
                    <invoice-form
                            :invoice-number=" {{ $invoiceNumber  }}  "
                            :companies="{{ $companies }}  "
                            :customers="{{ $customers  }} "
                    >
                        {{ csrf_field() }}

                    </invoice-form>
                </div>
            </div>
        </div>
    </div>

@endsection