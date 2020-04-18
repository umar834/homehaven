@extends('layouts.mylayout')

@section('content')
    @if(count($data) > 1)
        @foreach ($data as $i)
            <div class="container">
            <h3>{{$i->id}}</h3>
            <h3>{{$i->name}}</h3>
            <h3>{{$i->age}}</h3>
            </div>
        @endforeach
    @endif
@endsection