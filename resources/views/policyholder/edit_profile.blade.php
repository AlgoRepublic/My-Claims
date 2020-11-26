<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Policyholder Edit | Show My Claims</title>
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
            <h4>Policyholder - Edit Profile</h4>
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
                    <label for=" ">Name<span class="error-text">*</span></label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9\s]+" title="Only alphanumeric characters are allowed." value="{{ old('name', $userData->name) }}" name="name" required>
                </div>
                <input type="hidden" value="{{ old('id', $userData->id) }}" name="id" />
                <div class="form-group col-md-6">
                    <label for=" ">Surname<span class="error-text">*</span></label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9\s]+" title="Only alphanumeric characters are allowed." value="{{ old('surname', $userData->surname) }}" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for=" ">Cell Number<span class="error-text">*</span></label>
                    <input type="text" data-original="{{ $userData->mobile }}" data-type="mobile" pattern="\d*" maxlength="10" minlength="10" title="10 digits minimum" class="form-control" id="reg-contact-no" value="{{ old('mobile', $userData->mobile) }}" name="mobile" required>
                    <span id="reg-contact-error"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Email</label>
                    <input type="email" class="form-control" value="{{ old('email', $userData->email) }}" id="reg-email" name="email">
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">South African Identity Document Number<span class="error-text">*</span></label>
                    <input type="text" id="user-idn" data-original="{{ $userData->identity_document_number }}" data-type="identity_document_number" pattern="\d*" maxlength="13" minlength="13" title="Only dgits are allowed | Length should be 13." class="form-control" value="{{ old('identity_document_number', $userData->identity_document_number) }}" name="identity_document_number" required>
                    <span id="reg-idn-error"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Old Password<span class="error-text">*</span></label>
                    <input type="password" class="form-control" id="edit-old-pass" name="old_password" required>
                    <span toggle="#edit-old-pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">New Password</label>
                    <input type="password" class="form-control" id="reg-pass" name="new_password">
                    <span toggle="#reg-pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
                <div class="form-group col-md-6">
                    <label for=" ">Confirm New Password</label>
                    <input type="password" class="form-control" id="reg-re-pass" name="re_pwd">
                    <span toggle="#reg-re-pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    <span id="reg-pass-error"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Billing Type<span class="error-text">*</span></label>
                    <select class="form-control" name="payment_method" id="bill-type" required>
                        <option value="">-Select Type-</option>
                        <option value="manual" {{ (!empty($userData->payment->payment_method) && $userData->payment->payment_method == 'manual') ? 'selected' : '' }}>Manual Transfer</option>
                        <option value="eft" {{ (!empty($userData->payment->payment_method) && $userData->payment->payment_method == 'eft') ? 'selected' : '' }}>EFT</option>
                        <option value="cc" {{ (!empty($userData->payment->payment_method) && $userData->payment->payment_method == 'cc') ? 'selected' : '' }}>Card Payment</option>
                    </select>
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
                    <a class="btn btn-lg custom_btn_delete" href="{{ url('/policyHolder/') }}">Back</a>
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
