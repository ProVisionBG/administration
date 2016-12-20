<?php
(empty($attributes['class']) ? $attributes['class'] = 'btn-primary' : null);
(empty($attributes['target']) ? $attributes['target'] = '_self' : null);
?>


<a title="{{$name}}" href="{{$href}}" class="admin-link-button btn btn-sm {{@$attributes['class']}}" target="{{@$attributes['target']}}">
    @if(!empty($attributes['icon']))
        <i class="fa fa-{{$attributes['icon']}}" aria-hidden="true"></i>
    @else
        {{$name}}
    @endif
</a>