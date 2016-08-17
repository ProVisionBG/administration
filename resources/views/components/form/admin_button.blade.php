<button title="{{$name}}" type="{{@$attributes['type']}}" name="{{@$attributes['name']}}" value="{{@$attributes['value']}}" class="{{@$attributes['class']}}">
    @if(!empty($icon))
        <i class="fa fa-{{$icon}}" aria-hidden="true"></i>
    @else
        {{$name}}
    @endif
</button>