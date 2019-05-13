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
                               <button class="btn btn-link js-show-invoice-filter">Show filter</button>
                           </span>
                            <a href="/invoices/create" class="btn btn-primary">New Invoice</a>
                        </div>
                        <div class="js-invoice-filter">
                            <invoices-filter uri="{{route('get-date-for-filter')}}"></invoices-filter>
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
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
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
                            {{--<th scope="col">Mark Paid</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse  ($invoices as $invoice)
                            @php
                                /** @var \App\Invoice $invoice */
                            @endphp
                            <tr>

                                <td>
                                    <div class="form-group row">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="check-{{ $invoice->id }}">
                                        </div>
                                    </div>
                                </td>

                                <td scope="row">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                        </div>
                                        {{ str_pad($invoice->number, 7, "0", STR_PAD_LEFT) }}
                                    </div>
                                </td>

                                <td><a href="#">{{ $invoice->customer->name }}</a></td>
                                <td><a href="#">{{ $invoice->company->name }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                                <td
                                        @if(\Carbon\Carbon::parse($invoice->due_date)->greaterThanOrEqualTo(\Carbon\Carbon::now()))
                                        style="color: green;"
                                        @else
                                        style="color: red"
                                        @endif
                                >{{
                                     \Carbon\Carbon::parse($invoice->due_date)->diffInDays(\Carbon\Carbon::now())
                                     }}
                                </td>
                                <td>{{ $invoice->total }}</td>
                                <td>{{ $invoice->balance }}</td>
                                <td>{{ $invoice->status }}</td>
                                <td>
                                    <button>
                                        mark paid
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td><h3>No data</h3></td></tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection