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
        @if(\Illuminate\Support\Facades\Session::has('message'))
            <div class="alert {{ \Illuminate\Support\Facades\Session::get('alert-class', 'alert-info') }} alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ \Illuminate\Support\Facades\Session::get('message') }}
            </div>
        @endif
        <form method="post" action="{{ url('/policyHolder/edit') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Name*</label>
                    <input type="text" class="form-control" value="{{ old('name', $userData->name) }}" placeholder="John Deo" name="name" required>
                </div>
                <input type="hidden" value="{{ old('id', $userData->id) }}" name="id" />
                <div class="form-group col-md-6">
                    <label for=" ">Surname*</label>
                    <input type="text" class="form-control" id=" " value="{{ old('surname', $userData->surname) }}" placeholder="Deo Smith" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Contact Number*</label>
                    <input type="text" pattern="\d*" maxlength="10" minlength="10" title="10 digits minimum" class="form-control" id="reg-contact-no" value="{{ old('mobile', $userData->mobile) }}" placeholder="0123456789" name="mobile" required>
                    <p id="reg-contact-error"></p>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Email</label>
                    <input type="email" class="form-control" id=" " value="{{ old('email') }}" placeholder="john_deo@xyz.com" name="email">
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Old Password*</label>
                    <input type="password" class="form-control" id=" " placeholder="********" name="old_password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">New Password</label>
                    <input type="password" class="form-control" id=" " placeholder="********" name="new_password">
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Repeat New Password</label>
                    <input type="password" class="form-control" id=" " placeholder="********" name="re_pwd">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">South African Identity Document Number*</label>
                    <input type="text" pattern="\d*" maxlength="13" minlength="13" title="13 digits IDN is valid" class="form-control" value="{{ old('identity_document_number', $userData->identity_document_number) }}" placeholder="123456789" name="identity_document_number" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Billing method</label>
                    <select class="form-control" name="package">
                        @foreach($packages as $package)
                            <option {{ (!empty($userData->payment->package_id) && $userData->payment->package_id == $package->id) ? 'selected' : '' }} value="{{ $package->id }}">{{ $package->name }}</option>
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
