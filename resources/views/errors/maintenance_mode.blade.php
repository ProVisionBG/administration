@extends('administration::layouts.error')

@section('content')
    <section class="content">
        <div class="error-page">
            {{--<h2 class="headline text-red"> Maintenance Mode</h2>--}}

            <div class="error-content" style="margin-left:0;">

                <h3><i class="fa fa-warning text-red"></i>

                    @if(empty($data['message']))
                        Web site is in Maintenance Mode!
                    @else
                        {{$data['message']}}
                    @endif

                </h3>

            </div>
        </div>
    </section>
@stop