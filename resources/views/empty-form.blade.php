@extends('administration::layouts.master')

@section('content')
    @if(!empty($before))
        {!! $before !!}
    @endif

    {!! form($form) !!}

    @if(!empty($after))
        {!! $after !!}
    @endif
@stop

@if(!empty($js_scripts))
    @push('js_scripts')
    {!! $js_scripts !!}
    @endpush
@endif

@if(!empty($css))
    @push('top_css')
    {!! $css !!}
    @endpush
@endif