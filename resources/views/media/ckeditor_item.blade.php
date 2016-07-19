<div
        class="media-item"
        style="float:left; margin:0 10px;"
>

    <div class="img">
        <img src="{{$item->path}}/_{{$item->file}}"/>
    </div>

    <div class="bottom-controls">

        <div class="input-group input-group-sm">
             <span class="input-group-addon">
                <input
                        id="element{{$item->id}}"
                        type="radio"
                        name="selected_item"
                        value="{{$item->id}}"

                        data-path="{{$item->path}}"
                        data-file="{{$item->file}}"
                        data-id="{{$item->id}}"
                />
                 <label for="element{{$item->id}}">{{trans('administration::index.select')}}</label>
            </span>
        </div>

    </div>
</div>