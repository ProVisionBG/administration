<div
        class="media-item"
        data-id="{{$item->id}}"
        data-route-update="{{route('provision.administration.media.update',$item->id)}}"
        data-route-destroy="{{route('provision.administration.media.destroy',$item->id)}}"
>

    <div class="img">
        <img src="{{$item->path}}/A_{{$item->file}}"/>
    </div>

    <div class="btn-group-vertical">
        <button type="button" class="btn btn-default btn-drag"><i class="fa fa-arrows"></i></button>
        <button type="button" class="btn btn-default btn-delete"><i class="fa fa-trash-o"></i></button>

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#"><i class="fa fa-pencil-square-o"></i> Редактирай</a></li>
            </ul>
        </div>
    </div>

    <div class="bottom-controls">

        <div class="input-group input-group-sm">
             <span class="input-group-addon">
                <input type="checkbox" name="selected[]" value="{{$item->id}}"/>
            </span>
            <div class="input-group-btn" class="media-choice-language">
                <button type="button" class="btn btn-default btn-selected-lang dropdown-toggl" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="lang-sm" lang="{{$item->lang}}"></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @foreach(\Administration::getLanguages() as $flag=>$language)
                        <li><a href="javascript:void(0);" class="choice-lang" data-lang="{{$flag}}"><span class="lang-sm" lang="{{$flag}}"></span> {{$language['native']}}</a></li>
                    @endforeach
                    <li><a href="javascript:void(0);" class="choice-lang" data-lang=""><span class="lang-sm" lang=""></span> {{trans('administration::index.all')}}</a></li>
                </ul>
            </div>

        </div>


    </div>
</div>