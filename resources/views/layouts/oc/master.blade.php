<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Godmode | {{env('APP_NAME', 'AGM Control Panel')}}</title>
    <link rel="icon" href="{{route('home')}}/favicon.ico" type="image/x-icon"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initi'layouts.lcs.masteral-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{route('oc.home')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>CP</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>AGM Control Panel</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            {{--Sidebar user panel--}}
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{Auth::user()->photo}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{Auth::user()->name.' '.Auth::user()->surname}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MENU</li>
                <li><a href="#"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="#"><i class="fa fa-bar-chart"></i> <span>Registrations</span></a></li>
                <li><a href="#"><i class="fa fa-bed"></i> <span>Rooming</span></a></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-hotel"></i> <span>Check-in</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-hotel"></i> <span>Check-in MariLena</span></a></li>
                        <li><a href="#"><i class="fa fa-hotel"></i> <span>Check-in MariRena</span></a></li>
                        <li><a href="#"><i class="fa fa-hotel"></i> <span>Check-in Heraklion</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>Welcome Bags</span></a></li>
                <li><a href="{{route('oc.logout')}}"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Built by <a href="https://www.linkedin.com/in/frangiadakisdimitris/">Dimitris Frangiadakis</a> of <a
                    href="https://www.facebook.com/esnharo/">ESN Haro</a>.
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{\Carbon\Carbon::now()->year}} <a href="https://esngreece.gr" target="_blank">ESN Greece</a>.</strong> All rights
        reserved.
    </footer>
</div>

<script src="{{asset('js/app.js')}}"></script>
@yield('js')
</body>
</html>