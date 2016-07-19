@if(!empty($items) && !$items->isEmpty())
    @if(\Request::has('mode') && \Request::input('mode')=='ckeditor')
        @each('administration::media.ckeditor_item', $items, 'item')
    @else
        @each('administration::media.item', $items, 'item')
    @endif
@else
    <div class="callout callout-info">
        <p>{{trans('administration::media.not_found_items')}}</p>
    </div>
@endif