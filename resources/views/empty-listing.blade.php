@extends('administration::layouts.master')

@section('content')

    @if(!empty($filterForm))
        <div class="box box-default collapsed-box" id="box-filter">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter" aria-hidden="true"></i> {{trans('administration::index.filter')}}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <?php
                $filterForm->setFormOption('template', 'empty_form');
                $filterForm->setFormOption('class', 'form-inline');

                //set submit button
                $filterForm->add('filter', 'button', [
                        'label' => '<i class="fa fa-search" aria-hidden="true"></i> ' . trans('administration::index.filter'),
                        'attr' => [
                                'type' => 'submit',
                                'class' => 'btn btn-sm btn-primary'
                        ]
                ]);
                ?>
                {!! form($filterForm) !!}
            </div>
        </div>
    @endif

    <div class="box box-primary">
        {{--<div class="box-header with-border">--}}
        {{--<h3 class="box-title">{{trans('administration::index.administrators')}}</h3>--}}
        {{--</div>--}}
        <div class="box-body">
            <div class="panel-body">
                <?php
                $table->addAction([
                        'title' => trans('administration::index.actions')
                ]);
                ?>

                {!! $table->table() !!}
            </div>
        </div>
    </div>

@stop

@push('js_scripts')
{{--{!! $table->scripts() !!}--}}
<script>
    $(function () {
        var administrationTable = $('#dataTableBuilder').DataTable({
            "lengthChange": false,
            "responsive": true,
            "processing": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "ajax": {
                url: '{{route(\Request::route()->getName())}}',
                data: function (d) {
                    //console.log(d);
                    $('#box-filter form input').each(function () {
                        if ($(this).is(':checkbox')) {
                            d[$(this).attr('name')] = $(this).prop("checked");
                        } else {
                            d[$(this).attr('name')] = $(this).val();
                        }
                    })
                }
            },
            "autoWidth": true,
            "serverSide": true,
            "columns": {!! json_encode($table->columns([])->collection) !!}
        });

        $('#box-filter form').on('submit', function (e) {
            e.preventDefault();
            administrationTable.draw();
        });
    });
</script>

@endpush