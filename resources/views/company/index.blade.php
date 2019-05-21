@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>List of Company</h1>
                           </span>

                            <a href="{{route('company-create')}}" class="btn btn-primary">Create New Profile </a>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">Company</th>
                            <th scope="col" style="white-space: nowrap">Short name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Invoice notes</th>
                            <th scope="col">logo</th>
                            {{--<th scope="col">Mark Paid</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($company as $item)
                            <tr>
                                <td><a href="{{route('company-update', ['id' => $item->id])}}">{{ $item->name }}</a></td>
                                <td>{{ $item->short_name }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->invoice_notes }}</td>
                                <td><img width="100" src="{{($item->logo_img) ? 'upload/company/' . $item->logo_img : '/img/no_img.png'}}" alt="{{$item->short_name}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection