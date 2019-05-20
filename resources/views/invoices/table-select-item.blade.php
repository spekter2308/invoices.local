@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <form method="POST" action="/items">
                            @csrf
                            <div class="row p-3">
                                <div class="col-md-10">
                                    <div class="">
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has ('name') ? 'error' : '' }}"
                                               placeholder="Item name..."
                                               value="{{old('name')}}">
                                    </div>
                                </div>
                                <div class="col-md-2 text-right">
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('errors')
                @include('success')
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col" class="text-right pr-5">Delete</th>
                        {{--<th scope="col">Mark Paid</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">
                                <form method="POST" action="/items/destroy/{{$item->id}}">
                                    @method('DELETE')
                                    @csrf
                                    <span class="btn-save-edit"><edit-item name="{{$item->name}}" id="{{$item->id}}"></edit-item></span>
                                    <button type="submit" class="btn-danger btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    </div>
@endsection