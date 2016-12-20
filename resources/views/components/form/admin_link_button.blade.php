<a title="{{$name}}" href="{{@$attributes['href']}}" class="{{@$attributes['class']}}" target="{{@$attributes['target']}}">
    @if(!empty($icon))
        <i class="fa fa-{{$icon}}" aria-hidden="true"></i>
    @else
        {{$name}}
    @endif
</a>