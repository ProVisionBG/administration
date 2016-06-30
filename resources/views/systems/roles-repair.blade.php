@extends('administration::layouts.master')

@section('content')

    @foreach($responses as $response)
        <div class="callout callout-{{$response['status']}}">
            {{$response['message']}}
        </div>
    @endforeach

@stop
