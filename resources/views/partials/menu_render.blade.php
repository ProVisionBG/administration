@foreach($items as $item)
    <li @lm_attrs($item)
        @if($item->hasChildren())
        class="nav-item has-treeview @if($item->isActive) menu-open @endif"
        @else
        class="nav-item @if($item->isActive) menu-open @endif"
        @endif @lm_endattrs
    >

        @if($item->link)
            <a @lm_attrs($item->link)
                @if($item->hasChildren())
                    class="nav-link @if($item->isActive) active @endif"
                @else
                    class="nav-link @if($item->isActive) active @endif"
                @endif
                @lm_endattrs
                href="{!! $item->url() !!}">

                <i class="nav-icon fas {{\ProVision\Administration\Facades\MenuFacade::getItemIcon($item)}}"></i>

                <p>
                    {!! $item->title !!}
                    @if($item->hasChildren()) <i class="right fas fa-angle-left"></i> @endif
                </p>
            </a>
        @else
            <span class="navbar-text">{!! $item->title !!}</span>
        @endif

        @if($item->hasChildren())
            <ul class="nav nav-treeview">
                @include('administration::partials.menu_render', array('items' => $item->children()))
            </ul>
        @endif

    </li>

    @if($item->divider)
        <li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
    @endif

@endforeach
