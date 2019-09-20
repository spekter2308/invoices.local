@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
    
                @include('access')

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
                        </div>
                        <div class="js-invoice-filter">
                            <invoices-filter uri="{{route('get-date-for-filter')}}"></invoices-filter>
                        </div>

                    </div>
                    @include('errors')
                    @include('success')
                </div>
                <div class="card-body">
                    <div class="level">
                        <h5 class="mr-3">Status:</h5>
                        <a href="/invoices?status=" class="pd-1 border-right">All</a>
                        <a href="/invoices?status=Late" class="pd-1 pdl-1 border-right">Late</a>
                        <a href="/invoices?status=Draft" class="pd-1 pdl-1 border-right">Draft</a>
                        <a href="/invoices?status=Sent" class="pd-1 pdl-1 border-right">Sent</a>
                        <a href="/invoices?status=Viewed" class="pd-1 pdl-1 border-right">Viewed</a>
                        <a href="/invoices?status=Paid" class="pd-1 pdl-1 border-right">Paid</a>
                        <a href="/invoices?status=Partial" class="pd-1 pdl-1 border-right">Partial</a>
                        <a href="/invoices?status=Archive" class="pd-1 pdl-1 border-right">Archived</a>
                    </div>
                    <table class="table">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th>
                                <change-status></change-status>
                            </th>
                            <th scope="col">
                                Invoices
                            </th>
                            <th scope="col">Customer</th>
                            <th scope="col">Company</th>
                            <th scope="col">Date</th>
                            <th scope="col">Days</th>
                            <th scope="col">Total</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Status</th>
                            <th class="bg-white"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @include('invoices/includes/invoice_table_body')

                        <tr>
                            <td colspan="3">
                                @if($invoices->total() > $invoices->count())
                                    <br>
                                    <nav class="pagination" role="navigation" aria-label="pagination">
                                        {{ $invoices->onEachSide(1)->appends($filters)->links() }}
                                    </nav>
                                @endif
                            </td>
                            <td colspan="3">
                                {{--<items-per-page></items-per-page>--}}
                            </td>
                            <td>
                                <div>{{ $allTotalUsd }}</div>
                                <div>{{ $allTotalEuro }}</div>
                                <div>{{ $allTotalPound }}</div>
                            </td>
                            <td>
                                <div>{{ $allBalanceUsd }}$</div>
                                <div>{{ $allBalanceEuro }}€</div>
                                <div>{{ $allBalancePound }}£</div>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection