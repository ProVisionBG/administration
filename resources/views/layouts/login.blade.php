<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ trans('administration::index.admin_title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex, nofollow">

    <link rel="stylesheet" href='{{asset("/vendor/provision/administration/css/all.css")}}'>

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

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@yield('content')

<div class="text-center">
    <small><a href="http://www.provision.bg/" target="_blank"><b>ProVision</b></a> CMS v{{Config::get('provision_administration.version')}}</small>
</div>

<script src="{{asset("/vendor/provision/administration/js/all.js")}}"></script>

@yield('bottom_js')

</body>
</html>

