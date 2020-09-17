<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Policy Holder Signup | Show My Claims</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
            <h4>Policy Holder - Signup</h4>
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
                    <input type="text" class="form-control" id=" " value="{{ old('name') }}" placeholder="John Deo" name="name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Surname*</label>
                    <input type="text" class="form-control" id=" " value="{{ old('surname') }}" placeholder="Deo Smith" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Contact Number*</label>
                    <input type="text" class="form-control" id="reg-contact-no" value="{{ old('mobile') }}" placeholder="+00 123456789" name="mobile" required>
                    <p id="reg-contact-error"></p>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Email</label>
                    <input type="text" class="form-control" id=" " value="{{ old('email') }}" placeholder="john_deo@xyz.com" name="email">
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Password*</label>
                    <input type="password" class="form-control" id=" " placeholder="********" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Repeat Password*</label>
                    <input type="password" class="form-control" id=" " placeholder="********" name="re_pwd" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">South African Identity Document Number*</label>
                    <input type="number" class="form-control" id=" " placeholder="123456789" name="identity_document_number" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Billing method</label>
                    <select class="form-control" name="package">
                        <option value="">-Select Package-</option>
                        <option value="1">Annually R250</option>
                        <option value="2">Monthly R23</option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group">
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
