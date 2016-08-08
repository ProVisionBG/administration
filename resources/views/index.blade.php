@extends('administration::layouts.master')

@section('content')
    <div id="dashboard-container" class="row">
        {!! \ProVision\Administration\Dashboard::render() !!}
    </div>
@endsection