@extends('layouts.app')

@section('content')
    @php
        /** @var \App\Customer $customer*/
    @endphp

    @if ($customer->exists)
        <form method="POST" action="/customers/{{ $customer->id }}">
            @method('PATCH')
    @else
        <form method="POST" action="/customers">
    @endif
                    
                @csrf
                <div class="container">
                    <div class="row">
                    	<div class="col-md-7">
                    		
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                       role="tab" aria-controls="home" aria-selected="true">Cutomer</a>
                                </li>
                            </ul>
                            <br>
                            
                            @include('errors')
                            @include('success')
                            
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Customer Name</label>
                                        <input type="text" name="name"
                                               class="form-control {{ $errors->has ('name') ? 'error' : '' }}"
                                               placeholder="Customer name..."
                                               value="{{ old('name') ?? $customer->name }}">
                                    </div>
    
                                    <div class="form-group">
                                        <label for="address" class="font-weight-bold">Address</label>
                                        <textarea type="text" name="address"
                                                  class="form-control {{ $errors->has ('address') ? 'error' : '' }}"
                                                  rows="6" placeholder="Customer address...">
                                            {{ old('address') ?? $customer->address }}
                                        </textarea>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Contacts</label>
                                        <input type="email" name="email"
                                               class="form-control {{ $errors->has ('email') ? 'error' : '' }}"
                                               placeholder="E-mail address..."
                                               value="{{ old('email') ?? $customer->email }}">
                                    </div>
    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="firstname"
                                                       class="form-control {{ $errors->has ('firstname') ? 'error' : '' }}"
                                                       placeholder="First name"
                                                       value="{{ old('firstname') ?? $customer->firstname }}">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="lastname"
                                                       class="form-control {{ $errors->has ('lastname') ? 'error' : '' }}"
                                                       placeholder="Last name"
                                                       value="{{ old('lastname') ?? $customer->lastname }}">
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="phone"
                                                       class="form-control {{ $errors->has ('phone') ? 'error' : '' }}"
                                                       placeholder="Phone #"
                                                       value="{{ old('phone') ?? $customer->phone }}">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="additional_phone"
                                                       class="form-control {{ $errors->has ('additional_phone') ? 'error' : '' }}"
                                                       placeholder="Phone #"
                                                       value="{{ old('additional_phone') ?? $customer->additional_phone }}">
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
    
                        </div>
           

                        <div class="col text-center" style="margin-top: 100px;">
                        <br>
                            <button class="btn btn-primary" type="submit">Save</button>
                            <br>

                        @if($customer->exists)
        
                                <div class="form-group" style="margin: 10px auto; width: 60%;">
                                    <label for="name" class="font-weight-bold">Created at</label>
                                    <input type="text" name="created_at" class="form-control text-center"
                                           value="{{ $customer->created_at }}">
                                </div>
        
                                <div class="form-group" style="margin: 10px auto; width: 60%;">
                                    <label for="name" class="font-weight-bold">Updated at</label>
                                    <input type="text" name="udpated_at" class="form-control text-center"
                                           value="{{ $customer->updated_at }}">
                                </div>
                            <br>
                                
                                <button class="btn btn-danger" form="deleteCustomer" type="submit">Delete</button>
                                
                                
                        @endif
                </div>
            </div>
                </div>
                   
            </form>

            <form method="POST" id="deleteCustomer" action="/customers/{{ $customer->id }}">
            @csrf
            @method('DELETE')
            </form>
@endsection