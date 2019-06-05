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
                             <customization-options
                                     @sendinvoicesettings="getInvoicesSettings">
                               </customization-options>
                           </span>
                        
                        </div>
                    </div>
                </div>
                {{--Top buttons--}}
                <div class="level mt-2">
                    <div class="flex">
               @if ($mode === 'edit')
                    <a href="/invoices/{{ $invoice->id }}" class="btn btn-secondary">View</a>
                    <a href="#" class="btn btn-secondary">Print</a>
                    <a href="#" class="btn btn-secondary">PDF</a>
                    <a href="/invoice-mail/create/{{ $invoice->id }}" class="btn btn-secondary">Send</a>
                            <a href="{{route('record-payment', ['invoice' => $invoice->id])}}" class="btn btn-secondary">Record Payment</a>
                    <a href="/invoices/mark-as-paid/{{$invoice->id}}" class="btn btn-secondary">Mark as Paid</a>
                    <a href="/invoices/duplicate/{{ $invoice->id }}" class="btn btn-secondary">Duplicate</a>
               @else
                    <button class="btn btn-outline-secondary" disabled>View</button>
                    <button class="btn btn-outline-secondary" disabled>Print</button>
                    <button class="btn btn-outline-secondary" disabled>PDF</button>
                    <button class="btn btn-outline-secondary" disabled>Send</button>
                    <button class="btn btn-outline-secondary" disabled>Mark as Paid</button>
                    <button class="btn btn-outline-secondary" disabled>Duplicate</button>
               @endif
                    </div>
                    <button form="createInvoice" class="btn btn-primary send-btn" type="submit">Save</button>
                </div>
        
                <div class="mt-3 invoice-create-body">
                    <invoice-form
                            :settings="invoiceSettings"
                            :current-invoice="{{ $invoice }}"
                            invoice-paid="{{ $invoice->amount_paid ?? 0 }}"
                            :invoice-company="{{ $invoiceCompany }}"
                            :invoice-customer="{{ $invoiceCustomer }}"
                            :invoice-items="{{ json_encode($invoiceItems) }}"
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
            
            @if ($mode === 'edit')
    
            <div class="col-md-12">
                <hr>
                <h4>Invoice history</h4>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">User</th>
                        <th scope="col">Changes</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->histories as $history)
                            <tr>
                                <td>{{ $history->created_at }}</td>
                                <td>{{ $history->user->name }}</td>
                                <td>{{ $history->changes  }}</td>
                            </tr>
                        @empty
                            <p>History is empty</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>

@endsection