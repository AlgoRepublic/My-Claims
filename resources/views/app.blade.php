<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Show My Claims | @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom fonts Awesome -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Roboto Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
</head>
<body id="page-top">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="{{ asset('storage/img/web_logo.png') }}"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('policyHolder/') }}">Policy Holder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('beneficiary/') }}">Beneficiary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/what-we-do') }}">What we do</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/blog') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/contact-us') }}">Contact us</a>
                </li>
                @if(\Illuminate\Support\Facades\Auth::check())
                    <li class="nav-item dropdown" style="cursor: pointer">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">Profile</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item"><a href="{{ url('/') }}">Edit Profile</a></li>
                            <li class="dropdown-item"><a href="{{ url('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@yield('mainbody')
<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <span class="copyright">Copyright &copy; Show My Claims - 2020</span>
            </div>
        </div>
    </div>
</footer>
<!-- Bootstrap JavaScript -->
<script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('/js/main.min.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>
</body>
</html>
