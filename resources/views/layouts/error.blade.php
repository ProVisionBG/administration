<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @if(!empty(\Administration::getTitle())){{\Administration::getTitle()}} | @endif{{ Lang::get('administration::index.admin_title') }}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href='{{asset("/vendor/provision/administration/css/all.css")}}'>
</head>
<body class="hold-transition login-page">


<div class="wrapper">

    @yield('content')

</div>

<footer class="main-footer" style="margin-left:0; position: fixed; bottom: 0; width: 100%;">
    <div class="pull-right hidden-xs">
        <b>Version</b> {{Config::get('provision_administration.version')}}
    </div>
    <strong><a href="http://www.provision.bg/?ref=cms5" target="_blank"><b>ProVision</b></a> Administration</strong>. All rights reserved.
</footer>


</body>
</html>
