<?php
$elementID = 'adminMediaButton-' . str_random(20);
?>
<button class="btn btn-sm btn-default" data-toggle="modal" data-target="#{{$elementID}}">
    <i class="fa fa-picture-o" aria-hidden="true"></i> {{trans('administration::media.button_label')}}
</button>

<div class="modal modal-default modal-media"
     id="{{$elementID}}"
     data-route-index="{{route('provision.administration.media.index')}}"
     data-route-store="{{route('provision.administration.media.store')}}"
     data-item-id="{{$id}}"
     data-module-name="{{$module}}"
     data-module-sub-name="{{$sub_module}}"
     tabindex="-1" role="dialog"
     aria-labelledby="myMediaModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-picture-o" aria-hidden="true"></i> {{trans('administration::media.title')}}</h4>
            </div>
            <div class="modal-body">
                <div class="media-items-container">
                    {{-- show items --}}
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary pull-left upload-button"><i class="fa fa-upload" aria-hidden="true"></i> {{trans('administration::media.upload_items')}}</button>
                <input class="upload-input" type="file" name="file" multiple>
                {{--<button type="button" class="btn btn-sm btn-primary pull-left upload-button" data-dismiss="modal"><i class="fa fa-upload" aria-hidden="true"></i> {{trans('administration::media.upload_items')}}</button>--}}
                {{--<button type="button" class="btn btn-outline btn-ok">{{trans('administration::index.delete')}}</button>--}}
            </div>
        </div>
    </div>
</div>


<script>
    $('#{{$elementID}}').on('show.bs.modal', function (e) {
        runMedia('{{$elementID}}');
    });
</script>
