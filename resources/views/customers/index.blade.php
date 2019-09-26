@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
    
                <ul class="nav nav-tabs" id="customreTabs" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a class="nav-link @if(!session('flash')) active @endif" href="#contact-info" aria-controls="home" role="tab"
                                                              data-toggle="tab">Contact Information</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a class="nav-link @if(session('flash')) active @endif" href="#finance-info" aria-controls="finance-info" role="tab"
                                               data-toggle="tab">Financial Information</a>
                    </li>
                </ul>
    
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="contact-info">
                        <table class="table" style="width: 100%">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th class="pb-2 pt-2 pl-5">Customer</th>
                                <th class="pb-2 pt-2 pl-5">Address</th>
                                <th class="pb-2 pt-2 pl-5">Email</th>
                                <th class="pb-2 pt-2 pl-5">Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customers as $customer)
                                @php
                                    /** @var \App\Customer $customer */
                                @endphp
                                <tr>
                                    <td><a href="/customers/{{ $customer->id }}/edit">{{ $customer->name }}</a></td>
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    {{--<td>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.M H:i') : '' }}</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($customers->total() or $customers->count())
                            <br>
                            <nav class="pagination" role="navigation" aria-label="pagination">
                                {{ $customers->links() }}
                            </nav>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="finance-info">
                        <table class="table" style="width: 100%">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th class="pb-2 pt-2 pl-5">Customer</th>
                                <th class="pb-2 pt-2 pl-5"></th>
                                <th class="pb-2 pt-2 pl-5">Total</th>
                                <th class="pb-2 pt-2 pl-5">Payment</th>
                                <th class="pb-2 pt-2 pl-5">Balance</th>
                                <th class="pb-2 pt-2 pl-5 pr-0">Statements</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customers as $customer)
                                @php
                                    /** @var \App\Customer $customer */
                                @endphp
                                <tr>
                                    <td><a href="/customers/{{ $customer->id }}/edit">{{ $customer->name }}</a></td>
                                    <td></td>
                                    <td class="pb-2 pt-2 pl-5">{{ $customer->total }}</td>
                                    <td class="pb-2 pt-2 pl-5">{{ $customer->amount_paid }}</td>
                                    <td class="pb-2 pt-2 pl-5">{{ $customer->balance }}</td>
                                    <td class="pb-2 pt-2 pl-5">
                                        <a href="/customers/statement-excel/{{ $customer->id }}" class="btn btn-sm btn-primary">xlsx</a>
                                        <a href="/customers/statement-pdf/{{ $customer->id }}" class="btn btn-sm btn-primary">pdf</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($customers->total() or $customers->count())
                            <br>
                            <nav class="pagination" role="navigation" aria-label="pagination">
                                {{ $customers->links() }}
                            </nav>
                        @endif
                    </div>
                </div>


            </div>
        </div>
        
        
        
    </div>
@endsection
