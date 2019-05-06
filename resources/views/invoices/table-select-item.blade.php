@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>List of items</h1>
                           </span>

                            <a href="{{route('create-select-item')}}" class="btn btn-primary">Create new item </a>
                        </div>
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
                        <th scope="col">Delete</th>
                        {{--<th scope="col">Mark Paid</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><a href="{{route('create-select-item', ['id' => $item->id])}}">{{ $item->name }}</a></td>
                            <td class="text-center">
                                <form method="POST" action="{{route('delete-select-item', ['id' => $item->id])}}">
                                    @method('DELETE')
                                    @csrf

                                    <button type="submit" class="text-danger btn">x</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            {{ $items->links() }}

        </div>
    </div>
    </div>
@endsection