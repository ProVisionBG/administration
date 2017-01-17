<?php
$elementID = 'adminDeleteButton-' . str_random(20);
?>
<button title="{{$name}}" class="btn btn-sm btn-danger" data-href="{{$href}}"
        id="{{$elementID}}">
    <i class="fa fa-trash-o" aria-hidden="true"></i>
</button>

<script>
    $('#{{$elementID}}').on('click', function (e) {
        console.log('click');
        e.preventDefault();

        $.confirm({
            title: '',
            content: '{{trans('administration::index.confirm_title')}}',
            buttons: {
                Yes: {
                    text: '{{trans('administration::index.delete')}}',
                    //btnClass: 'btn-warning',
                    action: function () {
                        $.ajax({
                            url: $(e.relatedTarget).data('href'),
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
                Close: {
                    text: '{{trans('administration::index.cancel')}}'
                }
            }
        });

    });
</script>
