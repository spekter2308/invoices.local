@extends('layouts.app')

@section('content')
    @if(isset($company->id))
        <form method="POST" enctype="multipart/form-data"
              action="{{route('company-upload-save', ['id' => $company->id])}}">

            @else
                <form method="POST" enctype="multipart/form-data" action="{{route('company-create-save')}}">

                    @endif

                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7">

                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                           role="tab" aria-controls="home" aria-selected="true">Company</a>
                                    </li>
                                </ul>
                                <br>

                                @include('errors')
                                @include('success')

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                         aria-labelledby="home-tab">

                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Company Name*</label>
                                            <input type="text" name="name"
                                                   class="form-control {{ $errors->has ('name') ? 'error' : '' }}"
                                                   placeholder="Company name..."
                                                   value="{{ old('name') ?? $company->name }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="font-weight-bold">Short name</label>
                                            <input type="text" name="short_name"
                                                   class="form-control {{ $errors->has ('short_name') ? 'error' : '' }}"
                                                   placeholder="Short name..."
                                                   value="{{ old('short_name') ?? $company->short_name }}"/></div>

                                        <div class="form-group">
                                            <label for="address" class="font-weight-bold">Address*</label>
                                            <textarea type="text" name="address"
                                                      class="form-control {{ $errors->has ('address') ? 'error' : '' }}"
                                                      rows="6"
                                                      placeholder="Company address...">{{ old('address') ?? $company->address }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="invoice_notes" class="font-weight-bold">Invoice notes</label>
                                            <textarea name="invoice_notes"
                                                      class="form-control {{ $errors->has ('invoice_notes') ? 'error' : '' }}"
                                                      rows="6"
                                                      placeholder="Invoice notes...">{{ old('address') ?? $company->invoice_notes }}</textarea>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 text-center" style="margin-top: 100px;">
                                @empty(!$company->logo_img)
                                    <div class="p-4">
                                        <img src="/upload/company/{{$company->logo_img}}" height="150"
                                             class="rounded mx-auto d-block" alt="{{$company->short_name}}">
                                    </div>
                                @if($company->logo_img != 'no_image.png')
                                    <div class="form-group">
                                        <a href="{{route('company-image-delete', ['id' => $company->id])}}"
                                           class="btn btn-danger">Delete</a>
                                    </div>
                                    @endif
                                @endempty

                                <div class="form-group">
                                    <input type="file" name="logo_img"
                                           class="form-control {{ $errors->has ('logo_img') ? 'error' : '' }}"
                                           placeholder="Logo"
                                           value="{{ old('logo_img') ?? $company->logo_img }}"/>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
@endsection