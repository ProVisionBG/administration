<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Lang::get('administration::index.admin_title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

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

@yield('content')

<script src="{{asset("/vendor/provision/administration/js/all.js")}}"></script>

@yield('bottom_js')

</body>
</html>

