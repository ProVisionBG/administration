<?php
$elementID = 'adminMediaButton-' . str_random(20);
?>
<button title="{{trans('administration::media.button_label')}}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#{{$elementID}}">
    <i class="fa fa-picture-o" aria-hidden="true"></i>
</button>
<div class="modal modal-default modal-media"
     id="{{$elementID}}"
     data-route-index="{{route('provision.administration.media.index')}}"
     data-route-store="{{route('provision.administration.media.store')}}"
     data-item-id="{{$model->id}}"
     data-module-name="{{$model->module}}"
     data-module-sub-name="{{$model->sub_module}}"
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

                <div class="btn-group btn-group-sm pull-left actions-group" role="group">
                    <button type="button" class="btn btn-danger btn-delete-selected"><i class="fa fa-trash-o"></i> {{trans('administration::index.delete_selected')}}</button>
                    <button type="button" class="btn btn-default btn-select-all" data-status="true"><i class="fa fa-check-square-o" aria-hidden="true"></i> {{trans('administration::index.select_all')}}</button>
                    <button type="button" class="btn btn-default btn-select-all" data-status="false"><i class="fa fa-square-o" aria-hidden="true"></i> {{trans('administration::index.deselect_all')}}</button>
                </div>

                 <span class="btn btn-sm btn-primary pull-right upload-button fileinput-button">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                     <span>{{trans('administration::media.upload_items')}}</span>
                     <input class="upload-input" type="file" name="file" multiple>
                </span>

            </div>
        </div>
    </div>
</div>


<script>
    $('#{{$elementID}}').on('show.bs.modal', function (e) {
        runMedia('{{$elementID}}');
    });
</script>
