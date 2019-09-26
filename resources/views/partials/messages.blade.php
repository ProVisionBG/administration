{{-- Errors --}}
@if($errors->any())
    @foreach ($errors->all() as $error)
        <div class="callout callout-danger">
            {{--<h4>I am a danger callout!</h4>--}}
            <p>{{$error}}</p>
        </div>
    @endforeach
@endif