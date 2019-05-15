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
                        <button class="btn btn-outline-secondary" disabled>View</button>
                        <button class="btn btn-outline-secondary" disabled>Print</button>
                        <button class="btn btn-outline-secondary" disabled>PDF</button>
                        <button class="btn btn-outline-secondary" disabled>Send</button>
                        <button class="btn btn-outline-secondary" disabled>Mark as Paid</button>
                        <button class="btn btn-outline-secondary" disabled>Record Payment</button>
                        <button class="btn btn-outline-secondary" disabled>Duplicate</button>
                    </div>
                    <button form="createInvoice" class="btn btn-primary send-btn" type="submit">Save</button>
                </div>
                
                <div class="mt-3 invoice-create-body">
                    <invoice-form
                            :invoice-company="{{ $invoiceCompany ?? '{}' }}"
                            :invoice-customer="{{ $invoiceCustomer ?? '{}'}}"
                            :invoice-items="{{ $invoiceItems ?? '[]'}}"
                            :invoice-numbers="{{ json_encode($invoiceNumbers) }}"
                            invoice-number= "{{ ($invoiceNumber)  }}"
                            :format-number="{{ $invoiceFormatNumber }}"
                            :companies="{{ $companies }}"
                            :customers="{{ $customers  }}"
                            mode="{{ $mode }}"
                    >
                        {{ csrf_field() }}
                    
                    </invoice-form>
                </div>
            </div>
        </div>
    </div>

@endsection