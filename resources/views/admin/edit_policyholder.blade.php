@extends('admin.app')
@section('title', 'Policyholders')
@section('maincontent')
<h2>Edit Policyholder</h2>
<div class="card">
    <div class="card-header row">
        <div class="col col-sm-3">
        </div>
        <div class="col col-sm-6">
            <div class="card-search with-adv-search dropdown">
            </div>
        </div>
        <div class="col col-sm-3">
            <div class="card-options text-right">

            </div>
        </div>
    </div>
    <div class="card-body">
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
                            <input type="text" class="form-control" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed | No spaces are allowed." value="{{ old('name', $userData->name) }}" placeholder="John Deo" name="name" required>
                        </div>
                        <input type="hidden" value="{{ old('id', $userData->id) }}" name="id" />
                        <div class="form-group col-md-6">
                            <label for=" ">Surname<span class="error-text">*</span></label>
                            <input type="text" class="form-control" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed | No spaces are allowed." value="{{ old('surname', $userData->surname) }}" placeholder="Deo Smith" name="surname" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=" ">Cell Number<span class="error-text">*</span></label>
                            <input type="text" data-type="mobile" pattern="\d*" maxlength="10" minlength="10" title="10 digits minimum" class="form-control" id="reg-contact-no" value="{{ old('mobile', $userData->mobile) }}" placeholder="0123456789" name="mobile" required>
                            <span id="reg-contact-error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Email</label>
                            <input type="email" class="form-control" value="{{ old('email') }}" placeholder="john_deo@xyz.com" name="email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">South African Identity Document Number<span class="error-text">*</span></label>
                            <input type="text" id="user-idn" data-type="identity_document_number" pattern="\d*" maxlength="13" minlength="13" title="Only dgits are allowed | Length should be 13." class="form-control" value="{{ old('identity_document_number', $userData->identity_document_number) }}" placeholder="123456789" name="identity_document_number" required>
                            <span id="reg-idn-error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">New Password</label>
                            <input type="password" class="form-control" id="reg-pass" placeholder="********" name="new_password">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Confirm New Password</label>
                            <input type="password" class="form-control" id="reg-re-pass" placeholder="********" name="re_pwd">
                            <span id="reg-pass-error"></span>
                        </div>
                        <input type="hidden" name="source" value="admin" />
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group">
                            <a class="btn btn-lg custom_btn_delete" href="{{ url('/admin/policyHolders/') }}">Back</a>
                            <input type="submit" id="reg-sub-btn" class="btn custom_btn_form" type="submit" value="Save" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
