@if ($breadcrumbs)
    <pl class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <li>
                    <a href="{{ $breadcrumb->url }}">
                        @if(!empty($breadcrumb->icon))
                            <i class="fa {{ $breadcrumb->icon }}" aria-hidden="true"></i>
                        @endif
                        {{ $breadcrumb->title }}
                    </a>
                </li>
            @else
                <li class="active">
                    @if(!empty($breadcrumb->icon))
                        <i class="fa {{ $breadcrumb->icon }}" aria-hidden="true"></i>
                    @endif
                    {{ $breadcrumb->title }}
                </li>
            @endif
        @endforeach
    </pl>

@endif