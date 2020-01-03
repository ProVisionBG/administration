{{-- Errors --}}
@if($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            {{--<h5><i class="icon fas fa-ban"></i> Error</h5>--}}
            <p>{{$error}}</p>
        </div>
    @endforeach
@endif
