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
                               <h1>View Invoice</h1>
                           </span>

                        </div>
                    </div>
                </div>

                {{--Top buttons--}}
               @if (auth()->check())
                    <div class="level mt-2">
                        <div class="flex">
                            <a href="/invoices/{{ $invoice->id }}/edit" class="btn btn-secondary">Edit</a>
                            <a href="{{route('generate-pdf', ['invoice' => $invoice->id, 'print' => 'print'])}}" target="_blank" class="btn btn-secondary">Print</a>
                            <a href="{{route('generate-pdf', ['invoice' => $invoice->id])}}" class="btn btn-secondary">PDF</a>
                            <a href="/invoice-mail/create/{{ $invoice->id }}" class="btn btn-secondary">Send</a>
                            <a href="/invoices/mark-as-paid/{{$invoice->id}}" class="btn btn-secondary">Mark as Paid</a>
                            <a href="{{route('record-payment', ['invoice' => $invoice->id])}}" class="btn btn-secondary">Record Payment</a>
                            <a href="/invoices/duplicate/{{ $invoice->id }}" class="btn btn-secondary">Duplicate</a>
                        </div>
                    </div>
               @else
                    <div class="level mt-2">
                        <div class="flex">
                            <a href="{{route('generate-pdf', ['invoice' => $invoice->id, 'print' => 'print'])}}" target="_blank" class="btn btn-secondary">Print</a>
                            <a href="{{route('generate-pdf', ['invoice' => $invoice->id])}}" class="btn btn-secondary">PDF</a>
                        </div>
                    </div>
               @endif

                <invoice-show
                    :default-options="{{ $settings }}"
                    :current-invoice="{{ $invoice }}"
                    :invoice-items="{{ $invoiceItems }}"
                >
                
                </invoice-show>
            </div>
            
            @if (auth()->check())
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
            @endif
            
        </div>
    </div>

@endsection