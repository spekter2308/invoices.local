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
                        <a href="/invoices/{{ $invoice->id }}" class="btn btn-secondary">View</a>
                        <a href="#" class="btn btn-secondary">Print</a>
                        <a href="#" class="btn btn-secondary">PDF</a>
                        <a href="/invoice-mail/create/{{ $invoice->id }}" class="btn btn-secondary">Send</a>
                        <a href="#" class="btn btn-secondary">Mark as Paid</a>
                        <a href="#" class="btn btn-secondary">Record Payment</a>
                        <a href="#" class="btn btn-secondary">Duplicate</a>
                    </div>
                    <button form="createInvoice" class="btn btn-primary send-btn" type="submit">Save</button>
                </div>
                
                <div class="mt-3 invoice-create-body">
                    <invoice-form
                            :invoice-company="{{ $invoiceCompany }}"
                            :invoice-customer="{{ $invoiceCustomer }}"
                            :invoice-items="{{ $invoiceItems }}"
                            invoice-number= "{{ ($invoiceNumber)  }}"
                            :format-number="{{ $invoiceFormatNumber }}"
                            :invoice-numbers="{{ json_encode($invoiceNumbers) }}"
                            :customers="{{ $customers  }}"
                            :companies="{{ $companies }}"
                            mode="{{ $mode }}"
                    >
                        {{ csrf_field() }}
    
                    </invoice-form>
                </div>
            </div>
        </div>
    </div>

@endsection