@extends('administration::layouts.error')

@section('content')
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red"> 500</h2>

            <div class="error-content">
                <br/>
                <h3><i class="fa fa-warning text-red"></i> Internal Server Error.</h3>

                @if(!empty($exception->getMessage()))
                    <p>
                        {{$exception->getMessage()}}
                    </p>
                @endif

            </div>
        </div>
    </section>
@stop