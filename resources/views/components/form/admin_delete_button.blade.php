<?php
$elementID = 'adminDeleteButton-' . str_random(20);
?>
<button title="{{$name}}" class="btn btn-sm btn-danger" data-href="{{$href}}"
        id="{{$elementID}}">
    <i class="fa fa-trash-o" aria-hidden="true"></i>
</button>

<script>
    $('#{{$elementID}}').on('click', function (e) {
        e.preventDefault();

        var $this = $(this);

        $.confirm({
            title: '{{trans('administration::index.confirm_delete_title')}}',
            content: '{{trans('administration::index.confirm_delete_description')}}',
            buttons: {
                Yes: {
                    text: '{{trans('administration::index.yes')}}',
                    //btnClass: 'btn-warning',
                    action: function () {
                        $.ajax({
                            url: $this.data('href'),
                            type: 'DELETE',
                            success: function (result) {
                                $('#dataTableBuilder').DataTable().draw();
                            },
                            error: function (result) {
                                $.each(result.responseJSON, function (index, value) {
                                    toastr.error(value);
                                });
                            }
                        });
                    }
                },
                No: {
                    text: '{{trans('administration::index.no')}}'
                }
            }
        });

    });
</script>
