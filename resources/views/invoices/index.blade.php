@extends('layouts.app')

@section('content')

    <div class="container" style="min-width: 1350px; max-width: 1250px;">
        <div class="row justify-content-center">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>List of Invoices</h1>
                               <button class="btn btn-link js-show-invoice-filter">Show filter</button>
                           </span>
                            <a href="/invoices/create" class="btn btn-primary">New Invoice</a>
                        </div>
                    </div>
                </div>
                        <invoice-index

                        ></invoice-index>


            </div>
        </div>
    </div>
@endsection