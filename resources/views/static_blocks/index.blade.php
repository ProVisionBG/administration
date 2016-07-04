@extends('administration::layouts.master')

@section('content')

    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter" aria-hidden="true"></i> {{trans('administration::index.filter')}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="box-body" style="display: none;">
            <form method="POST" id="search-form" class="form-inline" role="form">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{trans('administration::index.name')}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="email" id="email" placeholder="{{trans('administration::index.email')}}">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" class="minimal" name="delete" id="delete">
                        {{trans('administration::index.only-deleted')}}
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">{{trans('administration::index.go_search')}}</button>

            </form>
        </div>
    </div>

    <div class="box box-primary">
        {{--<div class="box-header with-border">--}}
        {{--<h3 class="box-title">{{trans('administration::index.administrators')}}</h3>--}}
        {{--</div>--}}
        <div class="box-body">
            <div class="panel-body">
                <table id="datatable" class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        {{--<th>№</th>--}}
                        <th class="select-filter">{{trans('administration::static_blocks.key')}}</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div><!-- /.box-body -->
    </div>

@stop

@push('js_scripts')
<script>
    $(function () {
        var oTable = $('#datatable').DataTable({
            "lengthChange": false,
            "responsive": true,
            "processing": true,
            "ordering": true,
            "ajax": {
                url: '{{route('provision.administration.static-blocks.index')}}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.email = $('input[name=email]').val();
                    d.delete = $('input[name=delete]').is(':checked');
                }
            },
            "autoWidth": false,
            "serverSide": true,
            "columns": [
//                {data: 'id', name: 'id'},
                {data: 'key', name: 'key'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'actions'}
            ]
        });
        $('#search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@endpush