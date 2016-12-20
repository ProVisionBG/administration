<a title="{{$name}}" href="{{$href}}" class="btn btn-sm {{@$attributes['class']}}" target="{{@$attributes['target']}}">
    @if(!empty($attributes['icon']))
        <i class="fa fa-{{$attributes['icon']}}" aria-hidden="true"></i>
    @else
        {{$name}}
    @endif
</a>