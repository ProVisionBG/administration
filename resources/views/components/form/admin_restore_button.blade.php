<?php
$elementID = 'adminRestoreButton-' . str_random(20);
?>
<button title="{{$name}}" class="btn btn-sm btn-warning" data-href="{{$href}}" data-toggle="modal" data-target="#{{$elementID}}">
    <i class="fa fa-undo" aria-hidden="true"></i>
</button>

<div class="modal modal-warning" id="{{$elementID}}" tabindex="-1" role="dialog" aria-labelledby="myRestoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{trans('administration::index.confirm_title')}}</h4>
            </div>
            <div class="modal-body">
                <p>{{trans('administration::index.confirm_text')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">{{trans('administration::index.cancel')}}</button>
                <button type="button" class="btn btn-outline btn-ok">{{trans('administration::index.restore')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script>
    $('#{{$elementID}}').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').unbind('click').on('click', function () {
            $.ajax({
                url: $(e.relatedTarget).data('href'),
                type: 'DELETE',
                success: function (result) {
                    $('#dataTableBuilder').DataTable().draw();
                    $('#{{$elementID}}').modal('hide');
                }
            });
        })
    });
</script>
