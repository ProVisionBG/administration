<?php
$elementID = 'submitButton-' . str_random(20);
?>
@if ($options['wrapper'] !== false)
    <div {{$options['wrapperAttrs']}} >
        @endif

        <?php
        if (empty($options['attr']['class']) || $options['attr']['class'] == 'form-control') {
            $options['attr']['class'] = 'btn btn-primary';
        }
        if (empty($options['attr']['id'])) {
            $options['attr']['id'] = $elementID;
        }
        ?>

        <?= Form::button($options['label'], $options['attr']) ?>
        @include('administration::components.fields.help_block')

        @if(!empty($options['confirmation']))

            @if(empty($options['confirmation']['title']) || empty($options['confirmation']['content']))
                <?php
                throw new Exception('Submit button confirmation title/content is empty!');
                ?>
            @endif

            @push('js_scripts')
            <script>
                $('#{{$elementID}}').on('click', function (e) {
                    e.preventDefault();

                    var $this = $(this);

                    $.confirm({
                        title: '{{$options['confirmation']['title']}}',
                        content: '{{$options['confirmation']['content']}}',
                        buttons: {
                            Yes: {
                                text: '{{trans('administration::index.yes')}}',
                                //btnClass: 'btn-warning',
                                action: function () {
                                    $this.closest('form').submit();
                                }
                            },
                            No: {
                                text: '{{trans('administration::index.no')}}'
                            }
                        }
                    });

                });
            </script>
            @endpush
        @endif

        @if ($options['wrapper'] !== false)
    </div>
@endif
