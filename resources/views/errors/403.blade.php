@extends('administration::layouts.error')

@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red"> 403</h2>

            <div class="error-content">
                <br/>
                <h3><i class="fa fa-warning text-red"></i> You don't have permissions.</h3>

                <p>
                    Required permission: <b>{{$permission}}</b>
                    Meanwhile, you may <a href="{{Administration::route('dashboard')}}">return to home</a>.
                </p>

            </div>
        </div>
    </section>
@stop