@extends('layouts.app')

@section('content')
    @php
        /** @var \App\Customer $customer*/
    @endphp
    
    {{--@include('errors')
    @include('success')--}}
    
    @if ($customer->exists)
        <form method="POST" action="/customer/{{ $customer->id }}/edit">
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
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Customer Name</label>
                                        <input type="text" name="name" class="form-control"
                                               placeholder="Customer name..."
                                               value="{{ $customer->name }}">
                                    </div>
    
                                    <div class="form-group">
                                        <label for="address" class="font-weight-bold">Address</label>
                                        <textarea type="text" name="address" class="form-control"
                                                  rows="6" placeholder="Customer address...">
                                        </textarea>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Contacts</label>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="E-mail address...">
                                    </div>
    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="firstname" class="form-control"
                                                       placeholder="First name">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="lastname" class="form-control"
                                                       placeholder="Last name">
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="phone" class="form-control"
                                                       placeholder="Phone #">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="additional_phone" class="form-control"
                                                       placeholder="Phone #">
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
                                    <input type="text" name="name" class="form-control text-center"
                                           value="{{ $customer->created_at }}">
                                </div>
        
                                <div class="form-group" style="margin: 10px auto; width: 60%;">
                                    <label for="name" class="font-weight-bold">Updated at</label>
                                    <input type="text" name="name" class="form-control text-center"
                                           value="{{ $customer->updated_at }}">
                                </div>
                            <br>
                        @endif
                </div>
            </div>
                </div>
                   
            </form>
@endsection