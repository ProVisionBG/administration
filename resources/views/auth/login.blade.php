@include('administration::partials.head')

<body class="hold-transition login-page">

@include('administration::partials.messages')

<div class="login-box">

    <div class="login-logo">
        <a href="/">{{env('APP_NAME')}}</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">@lang('administration::auth.login_title')</p>

        {{Form::open(['route' => Administration::routeName('auth.login.post')])}}
        <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="@lang('administration::auth.email')">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control"
                   placeholder="@lang('administration::auth.password')" autocomplete="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" class="icheck"> @lang('administration::auth.remember_me')
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit"
                        class="btn btn-primary btn-block btn-flat">@lang('administration::auth.sign_in')</button>
            </div>
            <!-- /.col -->
        </div>
        {{Form::close()}}

    {{--        <div class="social-auth-links text-center">--}}
    {{--            <p>- OR -</p>--}}
    {{--            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in--}}
    {{--                using Facebook</a>--}}
    {{--        </div>--}}
    <!-- /.social-auth-links -->

        <a href="{{Administration::route('auth.password.request')}}">@lang('administration::auth.forgot_password_link')</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="{{mix('js/app.js','vendor/provision/administration/')}}"></script>

</body>
</html>
