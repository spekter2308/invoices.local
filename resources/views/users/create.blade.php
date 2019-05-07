@extends('layouts.app')

@section('content')
    @if(isset($user->id))
        <form method="POST"
              action="{{route('users-update-save', ['id' => $user->id])}}">

            @else
                <form method="POST" action="{{route('users-create-save')}}">

                    @endif

                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7">

                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                           role="tab" aria-controls="home" aria-selected="true">User</a>
                                    </li>
                                </ul>
                                <br>

                                @include('errors')
                                @include('success')

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                         aria-labelledby="home-tab">

                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Name</label>
                                            <input type="text" name="name"
                                                   class="form-control {{ $errors->has ('name') ? 'error' : '' }}"
                                                   placeholder="User name..."
                                                   value="{{ old('name') ?? $user->name }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold">E-mail \ Login</label>
                                            <input type="text" name="email"
                                                   class="form-control {{ $errors->has ('email') ? 'error' : '' }}"
                                                   placeholder="E-mail \ Login"
                                                   value="{{ old('email') ?? $user->email }}"/></div>

                                        <div class="form-group">
                                            <label for="password" class="font-weight-bold">Password</label>
                                            <input type="password" name="password"
                                                   class="form-control {{ $errors->has ('password') ? 'error' : '' }}"
                                                   placeholder="Password"/></div>

                                        <div class="form-group">
                                            <label for="password" class="font-weight-bold">Password confirmation</label>
                                            <input type="password" name="password_confirmation"
                                                   class="form-control {{ $errors->has ('password') ? 'error' : '' }}"
                                                   placeholder="Password"/></div>

                                        <div class="form-group">
                                            <label for="role" class="font-weight-bold">Role</label>
                                            <select class="form-control {{ $errors->has ('role') ? 'error' : '' }}" name="role">
                                                @foreach($roles as $role)
                                                    <option {{$role->user_id == $user->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                </div>

                            </div>


                            <div class="col text-center" style="margin-top: 100px;">
                                <br>
                                <button class="btn btn-primary" type="submit">Save</button>
                                <br>
                            </div>
                        </div>
                    </div>
                </form>
@endsection