@include('administration::partials.head')

<body class="hold-transition login-page">

<div class="login-box">

    <div class="login-logo">
        <a href="/">{{env('APP_NAME')}}</a>
    </div>

    @include('administration::partials.messages')

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">@lang('administration::auth.login_title')</p>

            {{Form::open(['route' => Administration::routeName('auth.login.post')])}}
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="@lang('administration::auth.email')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control"
                       placeholder="@lang('administration::auth.password')" autocomplete="password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember">
                        <label for="remember">
                            @lang('administration::auth.remember_me')
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit"
                            class="btn btn-primary btn-block">@lang('administration::auth.sign_in')</button>
                </div>
                <!-- /.col -->
            </div>
            {{Form::close()}}

            {{--            <div class="social-auth-links text-center mb-3">--}}
            {{--                <p>- OR -</p>--}}
            {{--                <a href="#" class="btn btn-block btn-primary">--}}
            {{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
            {{--                </a>--}}
            {{--                <a href="#" class="btn btn-block btn-danger">--}}
            {{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
            {{--                </a>--}}
            {{--            </div>--}}

            <p class="mb-1">
                <a href="{{Administration::route('auth.password.request')}}">@lang('administration::auth.forgot_password_link')</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="{{mix('js/app.js','vendor/provision/administration/')}}"></script>

</body>
</html>
