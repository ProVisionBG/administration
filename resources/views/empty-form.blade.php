@extends('administration::layouts.master')

@section('content')
    @if(!empty($before))
        {!! $before !!}
    @endif

    {!! form($form) !!}

    @if(!empty($form->getModel()) && $revData = $form->getModel()->revisionHistory)
        <?php
        /**
         * Load and translate relations
         */
        if (!empty($form->getModel()->translations)) {
            foreach ($form->getModel()->translations as $trans) {
                $transRevData = $trans->revisionHistory;
                foreach ($transRevData as $transRev) {
                    if (!empty($transRevData)) {
                        $revData->push($transRev);
                    }
                }
            }
        }
        //@todo: да се преведат нещата и да се помисли дали да не се покажат в дясно!
        ?>
        @if(!$revData->isEmpty())
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-history"></i>

                    <h3 class="box-title">Revisions</h3>
                </div>
                <div class="box-body">
                    <ul>
                        @foreach($revData->sortBy('created_at')->reverse() as $history)
                            <li>
                                @if($history->key == 'created_at' && !$history->old_value)
                                    <b>{{ $history->userResponsible()->name }}</b> created this resource
                                    at {{ $history->newValue() }}
                                @else
                                    <b>{{ $history->userResponsible()->name }}</b> changed
                                    <b>{{trans($form->getModel()->module.'::admin.'.$history->fieldName())  }}</b>
                                    from <b>{{ $history->oldValue() }}</b> to <b>{{ $history->newValue() }}</b>
                                @endif

                                <small class="label bg-yellow">{{$history->created_at}}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endif

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