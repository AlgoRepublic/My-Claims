<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Policyholder Signup | Show My Claims</title>
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
    <div class="custom_form_section">
        <div class="custom_page_heading">
            <h4>Policyholder - Incomplete Registration</h4>
        </div>
        @error('error')
        <div class="alert alert-danger" role="alert">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
        @if(!empty($msg))
            <div class="alert alert-warning alert-dismissible">
                {{ $msg }}
            </div>
        @endif
        <form method="post" action="{{ url('/policyHolder/complete-registration') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Billing Type<span class="error-text">*</span></label>
                    <select class="form-control" name="payment_method" required>
                        <option value="">-Select Type-</option>
                        <option value="eft">EFT</option>
                        <option value="cc">Credit Card</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Billing method<span class="error-text">*</span></label>
                    <select class="form-control" name="package" required>
                        <option value="">-Select Package-</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="user_id" value="{{ $user_id }}" />
                <input type="hidden" name="sub_again" value="{{ $sub_again ?? '' }}" />
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group">
                    <a class="btn btn-lg custom_btn_delete" href="{{--{{ url('/') }}--}} {{ $_SERVER['HTTP_REFERER'] ?? url('/') }}">Back</a>
                    <input type="submit" id="reg-sub-btn" class="btn custom_btn_form" type="submit" value="Save" />
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('/js/main.min.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>
</body>
</html>
