<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Policy Holder Signup | Show My Claims</title>
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
            <h4>Policy Holder - Register</h4>
        </div>
        @error('error')
        <div class="alert alert-danger" role="alert">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
        <form method="post" action="{{ url('/policyHolder/register') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Name*</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+" value="{{ old('name') }}" placeholder="John Deo" name="name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Surname*</label>
                    <input type="text" class="form-control" value="{{ old('surname') }}" placeholder="Deo Smith" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Cell Number*</label>
                    <input type="text" pattern="\d*" maxlength="10" minlength="10" title="10 digits minimum" class="form-control" id="reg-contact-no" value="{{ old('mobile') }}" placeholder="0123456789" name="mobile" required>
                    <span id="reg-contact-error"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Email</label>
                    <input type="email" class="form-control" value="{{ old('email') }}" placeholder="john_deo@xyz.com" name="email">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Password*</label>
                    <input type="password" pattern=".{6,}" title="6 characters minimum" class="form-control" id="reg-pass" placeholder="********" name="password" required>
                    <span toggle="#reg-pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    <span id="reg-pass-error"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Repeat Password*</label>
                    <input type="password" pattern=".{6,}" title="6 characters minimum" class="form-control" id="reg-re-pass" placeholder="********" name="re_pwd" required>
                    <span toggle="#reg-re-pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">South African Identity Document Number*</label>
                    <input type="text" pattern="\d*" maxlength="13" minlength="13" title="13 digits IDN is valid" class="form-control" value="{{ old('identity_document_number') }}" placeholder="0123456789000" name="identity_document_number" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Billing method</label>
                    <select class="form-control" name="package" required>
                        <option value="">-Select Package-</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group">
                    <a class="btn btn-lg btn-warning" href="{{ url('/') }}">Back</a>
                    <input type="submit" id="reg-sub-btn" class="btn custom_btn_form" type="submit" value="Send" />
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
