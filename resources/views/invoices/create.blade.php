@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                           <span class="flex">
                               <h1>New Invoice</h1>
                               <button class="btn btn-link">
                                   Show Customization Options
                               </button>
                           </span>

                        </div>
                    </div>
                </div>

                    <div class="level mt-2">
                        <div class="flex">
                            <button class="btn btn-outline-secondary">View</button>
                            <button class="btn btn-outline-secondary">Print</button>
                            <button class="btn btn-outline-secondary">PDF</button>
                            <button class="btn btn-outline-secondary">Send</button>
                            <button class="btn btn-outline-secondary">Mark as Paid</button>
                            <button class="btn btn-outline-secondary">Record Payment</button>
                            <button class="btn btn-outline-secondary">Duplicate</button>
                        </div>

                        <button class="btn btn-primary">Save</button>
                    </div>

                <div class="card-header mt-3">

                        <form method="POST" action="/threads">
                            @csrf

                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id"
                                        class="form-control {{ $errors->has('channel_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">Choose One...</option>
                                   {{-- @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}"
                                                {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{
                                        $channel->name }}</option>
                                    @endforeach--}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title"
                                       class="form-control  {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea name="body" id="body" rows="8"
                                          class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}" required>
                                    {{ old('body') }}
                                </textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>

                            {{--@include('layouts.errors')--}}
                        </form>


                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection