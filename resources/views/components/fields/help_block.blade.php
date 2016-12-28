@if ($options['help_block']['text'] && !$options['is_child'])
    <{{$options['help_block']['tag']}}
    @if(!empty($options['help_block']['attr']))
        @foreach ($options['help_block']['attr'] as $atr => $val)
            {{$atr}}="{{$val}}"
        @endforeach
    @endif
    >
    {{$options['help_block']['text']}}
    </{{$options['help_block']['tag']}}>
@endif
