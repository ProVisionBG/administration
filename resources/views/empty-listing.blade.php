@extends('administration::layouts.master')

@section('content')

    @if(!empty($filterForm))
        <div class="box box-default @if($filterForm->getFormOption('collapsed')===true) collapsed-box @endif" id="box-filter">
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
        <div class="box-body no-padding">

            <?php
            $table->addAction([
                    'title' => trans('administration::index.actions')
            ]);
            $table->setTableAttributes([
                    'class' => 'table table-hover'
            ]);
            ?>

            {!! $table->table() !!}

        </div>
    </div>

@stop

@push('js_scripts')
{{--{!! $table->scripts() !!}--}}
<script>
    $(function () {

        var datatableConfig = {
            "aaSorting": [[0, "desc"]], // @todo: да може да се сменя от контролера дефоутлният ордер
            "stateSave": true,
            "lengthChange": true,
            "responsive": true,
            "processing": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "ajax": {
                url: '{{route(\Request::route()->getName())}}',
                data: function (d) {
                    //console.log(d);
                    $('#box-filter form input, #box-filter form select').each(function () {
                        if ($(this).is(':checkbox')) {
                            d[$(this).attr('name')] = $(this).prop("checked");
                        } else {
                            d[$(this).attr('name')] = $(this).val();
                        }
                    })
                }
            },
            "autoWidth": false, //има проблем при firefox - таблицата минава в responsive при true стойност!
            "serverSide": true,
            rowReorder: {
                selector: 'button.btn-row-reorder',
                snapX: 10,
                update: false
            },
            "columns": {!! json_encode($table->columns([])->collection) !!}
        };

        var administrationTable = $('#dataTableBuilder').DataTable(datatableConfig);

        /*
         order save
         */
        administrationTable.on('row-reorder', function (e, diff, edit) {
            var data = [];
            //console.log(edit.triggerRow.data());
            $.each(diff, function (index, item) {
                buttonData = $(item.node).find('button.btn-row-reorder').data();
                if (buttonData.id == edit.triggerRow.data().id) {
                    data[index] = buttonData;
                    data[index].oldPosition = item.oldPosition;
                    data[index].newPosition = item.newPosition;
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('provision.administration.ajax.save-order')}}',
                data: {
                    'data': data
                },
                success: function (response) {
                    //няма нужда да ги зарежда отново...
                    //administrationTable.ajax.reload();
                },
                dataType: 'json'
            });
        });

        $('#box-filter form').on('submit', function (e) {
            e.preventDefault();
            administrationTable.draw();
        });
    });
</script>

@endpush