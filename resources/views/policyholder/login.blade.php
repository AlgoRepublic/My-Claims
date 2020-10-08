<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Policy Holder Login | Show My Claims</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('storage/img/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom fonts Awesome -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Roboto Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="custom_login_section">
                <div class="custom_logo_card">
                    <img src="{{ asset('storage/img/web_logo.png') }}">
                </div>
                <hr>
                <h3 class="text-center">Policy Holder Login</h3>
                <br>
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                    </div>
                @endif
                @error('error')
                <div class="alert alert-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
                <form method="post" action="{{ url('/policyHolder/login') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for=" ">Cell Number<span class="error-text">*</span></label>
                            <input type="number" value="{{ old('cell_number') }}" name="cell_number" class="form-control" minlength="9" maxlength="9" placeholder="123456789" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for=" ">Password<span class="error-text">*</span></label>
                            <input type="password" name="password" class="form-control" id=" " placeholder="**********" required>
                            <span class="custom_forgot_password"><a href="{{ url('/forgot-password') }}"> Forgot Password?</a></span>
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group">
                            <a class="btn btn-lg custom_btn_delete" href="{{ url('/') }}">Back</a>
                            <input type="submit" class="btn custom_btn_form" type="submit" value="Login" />
                        </div>
                    </div>
                    <hr>
                    <div class="custom_btn_form_text">
                        <span class="bottom_card_line custom_card_bottom_text"><a href="{{ url('/policyHolder/register') }}"> Register</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('/js/main.min.js') }}"></script>
</body>
</html>
