@foreach($items as $item)

    @if($item->header)
        <li class="header">{{$item->title}}</li>
    @else
        <li@lm-attrs($item) @if($item->hasChildren())class ="treeview"@endif @lm-endattrs>
    @if($item->link)
        <a@lm-attrs($item->link) @if($item->hasChildren()) class="dropdown-toggle" data-toggle="dropdown" @endif @lm-endattrs href="{!! $item->url() !!}">
    <i class="fa fa-{{$item->icon}}"></i>
    <span>{!! $item->title !!}</span>
    @if($item->hasChildren()) <i class="fa fa-angle-left pull-right"></i> @endif
    </a>
    @else
        <span>{!! $item->title !!}</span>
    @endif
    @if($item->hasChildren())

        <ul class="treeview-menu">
            @include('administration::partials.navigation',['items' => $item->children()])
        </ul>
        @endif
        </li>
        @if($item->divider)
            <li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
        @endif
    @endif
@endforeach
