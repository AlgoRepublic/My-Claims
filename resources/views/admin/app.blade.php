<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Show My Claims - @yield('title')</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/c3/c3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css') }}">
    <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="wrapper">
    <header class="header-top" header-theme="light">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div class="top-menu d-flex align-items-center">
                    <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
                    <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
                </div>
                <div class="top-menu d-flex align-items-center">
                    {{--<div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notiDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-bell"></i><span class="badge bg-danger">3</span></a>
                        <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notiDropdown">
                            <h4 class="header">Notifications</h4>
                            <div class="notifications-wrap">
                                <a href="#" class="media">
                                            <span class="d-flex">
                                                <i class="ik ik-check"></i> 
                                            </span>
                                    <span class="media-body">
                                                <span class="heading-font-family media-heading">Invitation accepted</span> 
                                                <span class="media-content">Your have been Invited ...</span>
                                            </span>
                                </a>
                                <a href="#" class="media">
                                            <span class="d-flex">
                                                <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                                            </span>
                                    <span class="media-body">
                                                <span class="heading-font-family media-heading">Steve Smith</span> 
                                                <span class="media-content">I slowly updated projects</span>
                                            </span>
                                </a>
                                <a href="#" class="media">
                                            <span class="d-flex">
                                                <i class="ik ik-calendar"></i> 
                                            </span>
                                    <span class="media-body">
                                                <span class="heading-font-family media-heading">To Do</span> 
                                                <span class="media-content">Meeting with Nathan on Friday 8 AM ...</span>
                                            </span>
                                </a>
                            </div>
                            <div class="footer"><a href="javascript:void(0);">See all activity</a></div>
                        </div>
                    </div>--}}
                    {{--<button type="button" class="nav-link ml-10 right-sidebar-toggle"><i class="ik ik-message-square"></i><span class="badge bg-success">3</span></button>--}}
                    {{--<div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-plus"></i></a>
                        <div class="dropdown-menu dropdown-menu-right menu-grid" aria-labelledby="menuDropdown">
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Dashboard"><i class="ik ik-bar-chart-2"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Message"><i class="ik ik-mail"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Accounts"><i class="ik ik-users"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Sales"><i class="ik ik-shopping-cart"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Purchase"><i class="ik ik-briefcase"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Pages"><i class="ik ik-clipboard"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Chats"><i class="ik ik-message-square"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Contacts"><i class="ik ik-map-pin"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Blocks"><i class="ik ik-inbox"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Events"><i class="ik ik-calendar"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Notifications"><i class="ik ik-bell"></i></a>
                            <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="More"><i class="ik ik-more-horizontal"></i></a>
                        </div>
                    </div>--}}
                    <button type="button" class="nav-link ml-10" id="apps_modal_btn" data-toggle="modal" data-target="#appsModal"><i class="ik ik-grid"></i></button>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-2x fa-user-circle"></i>{{--<img class="avatar" src="{{ url('storage/theme/img/user.jpg') }}" alt="">--}}</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ url('admin/logout') }}"><i class="ik ik-power dropdown-icon"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="page-wrap">
        <div class="app-sidebar colored">
            <div class="sidebar-header">
                <a class="header-brand" href="{{ url('/admin') }}">
                    <div class="logo-img">
                        {{--<img src="src/img/brand-white.svg" class="header-brand-img" alt="lavalite">--}}
                    </div>
                    <span class="text">Show My Claims</span>
                </a>
                <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
                <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
            </div>

            <div class="sidebar-content">
                <div class="nav-container">
                    <nav id="main-menu-navigation" class="navigation-main">
                        <div class="nav-lavel">Navigation</div>
                        <div class="nav-item active">
                            <a href="{{ url('admin/') }}"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                        </div>

                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-layers"></i><span>User Management</span> <span class="badge badge-danger">@yield('product_count')</span></a>
                            <div class="submenu-content">
                                <a href="{{ url('admin/policyHolders') }}" class="menu-item">Policy Holders</a>
                            </div>
                        </div>

                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-layers"></i><span>Beneficiary Management</span> <span class="badge badge-danger">@yield('product_count')</span></a>
                            <div class="submenu-content">
                                <a href="{{ url('admin/beneficiaries') }}" class="menu-item">Beneficiaries</a>
                            </div>
                        </div>

                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-layers"></i><span>Products</span> <span class="badge badge-danger">@yield('product_count')</span></a>
                            <div class="submenu-content">
                                <a href="{{ url('admin/products') }}" class="menu-item">View</a>
                            </div>
                        </div>

                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-layers"></i><span>Loyalty Rewards</span></a>
                            <div class="submenu-content">
                                <a href="{{ url('admin/loyalty-store') }}" class="menu-item">View</a>
                            </div>
                        </div>

                        {{--<div class="nav-item">
                            <a href="pages/navbar.html"><i class="ik ik-menu"></i><span>Navigation</span> <span class="badge badge-success">New</span></a>
                        </div>
                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-layers"></i><span>Widgets</span> <span class="badge badge-danger">150+</span></a>
                            <div class="submenu-content">
                                <a href="pages/widgets.html" class="menu-item">Basic</a>
                                <a href="pages/widget-statistic.html" class="menu-item">Statistic</a>
                                <a href="pages/widget-data.html" class="menu-item">Data</a>
                                <a href="pages/widget-chart.html" class="menu-item">Chart Widget</a>
                            </div>
                        </div>
                        <div class="nav-lavel">UI Element</div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-box"></i><span>Basic</span></a>
                            <div class="submenu-content">
                                <a href="pages/ui/alerts.html" class="menu-item">Alerts</a>
                                <a href="pages/ui/badges.html" class="menu-item">Badges</a>
                                <a href="pages/ui/buttons.html" class="menu-item">Buttons</a>
                                <a href="pages/ui/navigation.html" class="menu-item">Navigation</a>
                            </div>
                        </div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-gitlab"></i><span>Advance</span> <span class="badge badge-success">New</span></a>
                            <div class="submenu-content">
                                <a href="pages/ui/modals.html" class="menu-item">Modals</a>
                                <a href="pages/ui/notifications.html" class="menu-item">Notifications</a>
                                <a href="pages/ui/carousel.html" class="menu-item">Slider</a>
                                <a href="pages/ui/range-slider.html" class="menu-item">Range Slider</a>
                                <a href="pages/ui/rating.html" class="menu-item">Rating</a>
                            </div>
                        </div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-package"></i><span>Extra</span></a>
                            <div class="submenu-content">
                                <a href="pages/ui/session-timeout.html" class="menu-item">Session Timeout</a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="pages/ui/icons.html"><i class="ik ik-command"></i><span>Icons</span></a>
                        </div>
                        <div class="nav-lavel">Forms</div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-edit"></i><span>Forms</span></a>
                            <div class="submenu-content">
                                <a href="pages/form-components.html" class="menu-item">Components</a>
                                <a href="pages/form-addon.html" class="menu-item">Add-On</a>
                                <a href="pages/form-advance.html" class="menu-item">Advance</a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="pages/form-picker.html"><i class="ik ik-terminal"></i><span>Form Picker</span> <span class="badge badge-success">New</span></a>
                        </div>

                        <div class="nav-lavel">Tables</div>
                        <div class="nav-item">
                            <a href="pages/table-bootstrap.html"><i class="ik ik-credit-card"></i><span>Bootstrap Table</span></a>
                        </div>
                        <div class="nav-item">
                            <a href="pages/table-datatable.html"><i class="ik ik-inbox"></i><span>Data Table</span></a>
                        </div>

                        <div class="nav-lavel">Charts</div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-pie-chart"></i><span>Charts</span> <span class="badge badge-success">New</span></a>
                            <div class="submenu-content">
                                <a href="pages/charts-chartist.html" class="menu-item active">Chartist</a>
                                <a href="pages/charts-flot.html" class="menu-item">Flot</a>
                                <a href="pages/charts-knob.html" class="menu-item">Knob</a>
                                <a href="pages/charts-amcharts.html" class="menu-item">Amcharts</a>
                            </div>
                        </div>

                        <div class="nav-lavel">Apps</div>
                        <div class="nav-item">
                            <a href="pages/calendar.html"><i class="ik ik-calendar"></i><span>Calendar</span></a>
                        </div>
                        <div class="nav-item">
                            <a href="pages/taskboard.html"><i class="ik ik-server"></i><span>Taskboard</span></a>
                        </div>

                        <div class="nav-lavel">Pages</div>

                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-lock"></i><span>Authentication</span></a>
                            <div class="submenu-content">
                                <a href="pages/login.html" class="menu-item">Login</a>
                                <a href="pages/register.html" class="menu-item">Register</a>
                                <a href="pages/forgot-password.html" class="menu-item">Forgot Password</a>
                            </div>
                        </div>
                        <div class="nav-item has-sub">
                            <a href="#"><i class="ik ik-file-text"></i><span>Other</span></a>
                            <div class="submenu-content">
                                <a href="pages/profile.html" class="menu-item">Profile</a>
                                <a href="pages/invoice.html" class="menu-item">Invoice</a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="pages/layouts.html"><i class="ik ik-layout"></i><span>Layouts</span><span class="badge badge-success">New</span></a>
                        </div>
                        <div class="nav-lavel">Other</div>
                        <div class="nav-item has-sub">
                            <a href="javascript:void(0)"><i class="ik ik-list"></i><span>Menu Levels</span></a>
                            <div class="submenu-content">
                                <a href="javascript:void(0)" class="menu-item">Menu Level 2.1</a>
                                <div class="nav-item has-sub">
                                    <a href="javascript:void(0)" class="menu-item">Menu Level 2.2</a>
                                    <div class="submenu-content">
                                        <a href="javascript:void(0)" class="menu-item">Menu Level 3.1</a>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="menu-item">Menu Level 2.3</a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="javascript:void(0)" class="disabled"><i class="ik ik-slash"></i><span>Disabled Menu</span></a>
                        </div>
                        <div class="nav-item">
                            <a href="javascript:void(0)"><i class="ik ik-award"></i><span>Sample Page</span></a>
                        </div>
                        <div class="nav-lavel">Support</div>
                        <div class="nav-item">
                            <a href="javascript:void(0)"><i class="ik ik-monitor"></i><span>Documentation</span></a>
                        </div>
                        <div class="nav-item">
                            <a href="javascript:void(0)"><i class="ik ik-help-circle"></i><span>Submit Issue</span></a>
                        </div>--}}
                    </nav>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="container-fluid">
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                    </div>
                    {{--<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>--}}
                @endif
                @yield('maincontent')
            </div>
        </div>

        {{--<aside class="right-sidebar">
            <div class="sidebar-chat" data-plugin="chat-sidebar">
                <div class="sidebar-chat-info">
                    <h6>Chat List</h6>
                    <form class="mr-t-10">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search for friends ...">
                            <i class="ik ik-search"></i>
                        </div>
                    </form>
                </div>
                <div class="chat-list">
                    <div class="list-group row">
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Gene Newman">
                            <figure class="user--online">
                                <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Gene Newman</span>  <span class="username">@gene_newman</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Billy Black">
                            <figure class="user--online">
                                <img src="{{ url('storage/theme/img/users/2.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Billy Black</span>  <span class="username">@billyblack</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Herbert Diaz">
                            <figure class="user--online">
                                <img src="{{ url('storage/theme/img/users/3.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Herbert Diaz</span>  <span class="username">@herbert</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Sylvia Harvey">
                            <figure class="user--busy">
                                <img src="{{ url('storage/theme/img/users/4.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Sylvia Harvey</span>  <span class="username">@sylvia</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item active" data-chat-user="Marsha Hoffman">
                            <figure class="user--busy">
                                <img src="{{ url('storage/theme/img/users/5.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Marsha Hoffman</span>  <span class="username">@m_hoffman</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Mason Grant">
                            <figure class="user--offline">
                                <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Mason Grant</span>  <span class="username">@masongrant</span> </span>
                        </a>
                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Shelly Sullivan">
                            <figure class="user--offline">
                                <img src="{{ url('storage/theme/img/users/2.jpg') }}" class="rounded-circle" alt="">
                            </figure><span><span class="name">Shelly Sullivan</span>  <span class="username">@shelly</span></span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>--}}

        <div class="chat-panel" hidden>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <a href="javascript:void(0);"><i class="ik ik-message-square text-success"></i></a>
                    <span class="user-name">John Doe</span>
                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="card-body">
                    <div class="widget-chat-activity flex-1">
                        <div class="messages">
                            <div class="message media reply">
                                <figure class="user--online">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/3.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>Epic Cheeseburgers come in all kind of styles.</p>
                                </div>
                            </div>
                            <div class="message media">
                                <figure class="user--online">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>Cheeseburgers make your knees weak.</p>
                                </div>
                            </div>
                            <div class="message media reply">
                                <figure class="user--offline">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/5.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>Cheeseburgers will never let you down.</p>
                                    <p>They'll also never run around or desert you.</p>
                                </div>
                            </div>
                            <div class="message media">
                                <figure class="user--online">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>A great cheeseburger is a gastronomical event.</p>
                                </div>
                            </div>
                            <div class="message media reply">
                                <figure class="user--busy">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/5.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>There's a cheesy incarnation waiting for you no matter what you palete preferences are.</p>
                                </div>
                            </div>
                            <div class="message media">
                                <figure class="user--online">
                                    <a href="#">
                                        <img src="{{ url('storage/theme/img/users/1.jpg') }}" class="rounded-circle" alt="">
                                    </a>
                                </figure>
                                <div class="message-body media-body">
                                    <p>If you are a vegan, we are sorry for you loss.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="javascript:void(0)" class="card-footer" method="post">
                    <div class="d-flex justify-content-end">
                        <textarea class="border-0 flex-1" rows="1" placeholder="Type your message here"></textarea>
                        <button class="btn btn-icon" type="submit"><i class="ik ik-arrow-right text-success"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="w-100 clearfix">
                <span class="text-center text-sm-left d-md-inline-block">Copyright © 2018 ThemeKit v2.0. All Rights Reserved.</span>
                <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">Crafted with <i class="fa fa-heart text-danger"></i> by <a href="http://lavalite.org/" class="text-dark" target="_blank">Lavalite</a></span>
            </div>
        </footer>

    </div>
</div>




<div class="modal fade apps-modal" id="appsModal" tabindex="-1" role="dialog" aria-labelledby="appsModalLabel" aria-hidden="true" data-backdrop="false">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ik ik-x-circle"></i></button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="quick-search">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 ml-auto mr-auto">
                            <div class="input-wrap">
                                <input type="text" id="quick-search" class="form-control" placeholder="Search..." />
                                <i class="ik ik-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="container">
                    <div class="apps-wrap">
                        <div class="app-item">
                            <a href="{{ url('/admin') }}"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                        </div>
                        <div class="app-item dropdown">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-layers"></i><span>Services</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ url('admin/services') }}">View</a>
                                <a class="dropdown-item" href="{{ url('admin/service-categories') }}">Service Category</a>
                            </div>
                        </div>
                        <div class="app-item dropdown">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-clipboard"></i><span>Products</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ url('admin/products') }}">View</a>
                            </div>
                        </div>


                        {{--<div class="app-item dropdown">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-command"></i><span>Ui</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-mail"></i><span>Message</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-users"></i><span>Accounts</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-shopping-cart"></i><span>Sales</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-briefcase"></i><span>Purchase</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-server"></i><span>Menus</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-clipboard"></i><span>Pages</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-message-square"></i><span>Chats</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-map-pin"></i><span>Contacts</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-box"></i><span>Blocks</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-calendar"></i><span>Events</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-bell"></i><span>Notifications</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-pie-chart"></i><span>Reports</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-layers"></i><span>Tasks</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-edit"></i><span>Blogs</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-settings"></i><span>Settings</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-more-horizontal"></i><span>More</span></a>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>--}}
<script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('plugins/screenfull/dist/screenfull.js') }}"></script>
<script src="{{ asset('plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('plugins/d3/dist/d3.min.js') }}"></script>
<script src="{{ asset('plugins/c3/c3.min.js') }}"></script>
<script src="{{ asset('js/tables.js') }}"></script>
<script src="{{ asset('js/widgets.js') }}"></script>
<script src="{{ asset('js/charts.js') }}"></script>
<script src="{{ asset('dist/js/theme.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

</body>
</html>
