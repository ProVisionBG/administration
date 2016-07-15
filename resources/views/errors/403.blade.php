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
                    {{--We could not find the page you were looking for.--}}
                    {{--Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search form.--}}
                </p>

            </div>
        </div>
    </section>
@stop