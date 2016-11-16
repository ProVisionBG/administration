{{--<button title="{{$name}}" type="{{@$attributes['type']}}" name="{{@$attributes['name']}}" value="{{@$attributes['value']}}" class="{{@$attributes['class']}}">--}}
{{--@if(!empty($icon))--}}
{{--<i class="fa fa-{{$icon}}" aria-hidden="true"></i>--}}
{{--@else--}}
{{--{{$name}}--}}
{{--@endif--}}
{{--</button>--}}

<?php
$elementID = 'adminSwitch-' . str_random(20);
?>
<input id="{{$elementID}}" type="checkbox" name="{{$name}}" @if($model->$name==true) checked @endif>

<script>
    $("#{{$elementID}}").bootstrapSwitch({
        size: 'small',
        onText: '{{trans('administration::index.yes')}}',
        offText: '{{trans('administration::index.no')}}',
        offColor: 'danger',
        onSwitchChange: function (event, state) {
            console.log(state);
            $.ajax({
                url: '{{route('provision.administration.ajax.save-switch')}}',
                type: 'POST',
                data: {
                    table: '{{$model->getTable()}}',
                    id: {{$model->id}},
                    state: state,
                    field: '{{$name}}'
                },
                success: function (result) {
                    console.log(result);
                }
            });
        }
    });
</script>