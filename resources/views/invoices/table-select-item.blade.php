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
                        <th scope="col">Actions</th>
                        {{--<th scope="col">Mark Paid</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                <form method="POST" action="{{route('delete-select-item', ['id' => $item->id])}}">
                                    @method('DELETE')
                                    @csrf
                                    <a class="btn-link btn" href="{{route('create-select-item', ['id' => $item->id])}}">Edit</a>
                                    <button type="submit" class="text-danger btn btn-link">Delete</button>
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