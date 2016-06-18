<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Lang::get('administration::index.admin_title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{asset("/vendor/provision/administration/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("/vendor/provision/administration/bower_components/font-awesome/css/font-awesome.min.css")}}">
    <link rel="stylesheet" href="{{asset("/vendor/provision/administration/bower_components/ionicons/css/ionicons.min.css")}}">
    <link rel="stylesheet" href="{{asset("/vendor/provision/administration/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}">
    <link rel="stylesheet" href="{{asset("/vendor/provision/administration/bower_components/iCheck/skins/square/blue.css")}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset("/vendor/provision/administration/bower_components/html5shiv/dist/html5shiv.min.js")}}"></script>
    <script src="{{asset("/vendor/provision/administration/bower_components/respond/dest/respond.min.js")}}"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">

@if (!empty($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@yield('content')

<script src="{{asset("/vendor/provision/administration/bower_components/jquery/dist/jquery.min.js")}}"></script>
<script src="{{asset("/vendor/provision/administration/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
<script src="{{asset("/vendor/provision/administration/bower_components/iCheck/icheck.js")}}"></script>

@yield('bottom_js')

</body>
</html>

