@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>List of users</h1>
                           </span>

                            <a href="{{route('users-create')}}" class="btn btn-primary">Create new user </a>
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
                        <th scope="col">E-mail \ Login</th>
                        <th scope="col">Role</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{$user->roles->first()->name}}
                            </td>
                            <td>

                                <form method="POST" action="{{route('user-destroy', ['id' => $user->id])}}">
                                    @method('DELETE')
                                    @csrf
                                    <a class="btn btn-link"
                                       href="{{route('users-create', ['id' => $user->id])}}">Edit</a>
                                    <button type="submit" class="text-danger btn btn-link">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            {{ $users->links() }}

        </div>
    </div>
    </div>
@endsection