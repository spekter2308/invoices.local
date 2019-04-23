@extends('layouts.app')

@section('content')
    @foreach ($object as $i)
        <li>
            $i;
        </li>
    @endforeach
@endsection