<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @if(!empty(\Administration::getTitle())){{\Administration::getTitle()}} | @endif{{ Lang::get('administration::index.admin_title') }}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href='{{asset("/vendor/provision/administration/css/all.css")}}'>

    @stack('top_css')
</head>
<body class="hold-transition skin-blue sidebar-mini @if(\Request::cookie('administration-navigation-collapsed')=='true') sidebar-collapse @endif">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{route('provision.administration.index')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>P</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Administration</b> Panel</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a id="slidebar-toggle-button" href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="goto-to-site">
                        <a href="/" target="_blank" title="{{trans('administration::index.go_to_website')}}">
                            <i class="fa  fa-external-link"></i>
                        </a>
                    </li>
                    <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li><!-- end message -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>--}}
                <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{--<img src="{{Gravatar::get(Auth::guard('provision_administration')->user()->email),160}}" class="user-image" alt="User Image">--}}
                            <img src="https://www.gravatar.com/avatar/{{md5( strtolower( trim( Auth::guard('provision_administration')->user()->email ) ) )}}?d=identicon&f=y&r=g&s=25" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{Auth::guard('provision_administration')->user()->name}}</span> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                {{--<img src="{{Gravatar::get(Auth::guard('provision_administration')->user()->email,160)}}" class="img-circle" alt="User Image">--}}
                                <img src="https://www.gravatar.com/avatar/{{md5( strtolower( trim( Auth::guard('provision_administration')->user()->email ) ) )}}?d=identicon&f=y&r=g&s=160" class="img-circle" alt="User Image">
                                <p>
                                    {{Auth::guard('provision_administration')->user()->name}}
                                    <small>Member since {{Auth::guard('provision_administration')->user()->created_at}}</small>
                                </p>
                            </li>
                        {{--
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        --}}
                        <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{route('provision.administration.administrators.edit',[Auth::guard('provision_administration')->user()->id])}}" class="btn btn-default btn-flat">{{trans('administration::index.settings')}}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{route('provision.administration.logout')}}" class="btn btn-default btn-flat">{{trans('administration::index.logout')}}</a>
                                </div>
                            </li>

                        </ul>
                    </li>

                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>

                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="https://www.gravatar.com/avatar/{{md5( strtolower( trim( Auth::guard('provision_administration')->user()->email ) ) )}}?d=identicon&f=y&r=g&s=45" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{Auth::guard('provision_administration')->user()->name}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <div class="clearfix"></div>

            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                @include('administration::partials.navigation', ['items' => \Menu::get('ProVisionAdministrationMenu')->sortBy('order', 'asc')->roots()])
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content body">

            <section id="module-header" class="content-header">
                <h1>
                    {{\Administration::getTitle()}}
                    <small>{{\Administration::getSubTitle()}}</small>
                </h1>

                @if(Breadcrumbs::exists('admin_final'))
                    {{Breadcrumbs::setView('administration::partials.breadcrumbs')}}
                    {!! Breadcrumbs::render('admin_final') !!}
                @endif
            </section>

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
        </div>
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> {{Config::get('provision_administration.version')}}
        </div>
        <strong><a href="http://www.provision.bg/?ref=cms5" target="_blank"><b>ProVision</b></a> Administration</strong>. All rights reserved.
    </footer>


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">


            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div><!-- ./wrapper -->

<script>
    //current language
    var language = '{{Administration::getLanguage()}}';

    //translations for JS
    var translates = {!! json_encode(Lang::get('administration::js')) !!};

    //ckeditor settings
    window.CKEDITOR_BASEPATH = '/vendor/provision/administration/bower_components/ckeditor/';
    var formModels = []; //ckeditor form->model container

    /*
     media settings
     */
    var mediaRouteIndex = '{{route('provision.administration.media.index')}}';
</script>
<script src="{{asset("/vendor/provision/administration/js/all.js")}}"></script>

@stack('js_scripts')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
</script>

@stack('bottom_js')

</body>
</html>
