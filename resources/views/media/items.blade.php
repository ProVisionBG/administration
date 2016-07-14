@if(!empty($items) && !$items->isEmpty())
    @each('administration::media.item', $items, 'item')
@else
    <div class="callout callout-info">
        <p>{{trans('administration::media.not_found_items')}}</p>
    </div>
@endif