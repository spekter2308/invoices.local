@extends('layouts.app')

@section('content')
    @if(isset($item->id))
        <form method="POST" enctype="multipart/form-data"
              action="{{route('save-select-item', ['id' => $item->id])}}">
    @else
        <form method="POST" action="{{route('save-select-item')}}">
    @endif

                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7">

                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                           role="tab" aria-controls="home" aria-selected="true">Item</a>
                                    </li>
                                </ul>
                                <br>

                                @include('errors')
                                @include('success')

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                         aria-labelledby="home-tab">

                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Item Name</label>
                                            <input type="text" name="name"
                                                   class="form-control {{ $errors->has ('name') ? 'error' : '' }}"
                                                   placeholder="Item name..."
                                                   value="{{old('name') ?? $item->name}}">
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="col text-center" style="margin-top: 71px;">
                                <br>
                                <button class="btn btn-primary" type="submit">Save</button>
                                <br>
                            </div>
                        </div>
                    </div>
                </form>
@endsection