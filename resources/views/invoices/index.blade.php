@extends('layouts.app')

@section('content')
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

               <div class="card">
                   <div class="card-header">
                       <div class="level">
                           <span class="flex">
                               <h1>List of Invoices</h1>
                               <button class="btn btn-link">Show filter</button>
                           </span>

                           <a href="/invoices/create" class="btn btn-primary">New Invoice</a>
                       </div>
                   </div>
               </div>

                <div class="card-body">
                    <div class="level">
                        <h5 class="mr-3">Status:</h5>
                        <a href="#" class="pd-1 border-right">All</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Draft</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Sent</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Late</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Paid</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Partial</a>
                        <a href="#" class="pd-1 pdl-1 border-right">Archived</a>
                    </div>
                    <table class="table">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">
                                <div class="dropdown">
                                    <button class="btn-select dropdown-toggle bg-primary text-white" type="button"
                                            id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                    </div>

                                </div>
                            </th>
                            <th scope="col">
                                Invoices
                            </th>
                            <th scope="col">Customer</th>
                            <th scope="col">Company</th>
                            <th scope="col">Date</th>
                            <th scope="col">Days</th>
                            <th scope="col">Toral</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Status</th>
                            <th class="bg-white"></th>
                            {{--<th scope="col">Mark Paid</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        
                            @include('invoices/includes/invoice_table_body')

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection