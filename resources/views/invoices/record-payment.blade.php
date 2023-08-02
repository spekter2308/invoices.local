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