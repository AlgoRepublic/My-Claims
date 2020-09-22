@extends('app')
@section('title', 'Home Page')
@section('mainbody')
    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Check Policies</h4>
            </div>
            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            <form action="{{ url('/beneficiary/find-policy') }}" method="post">
                @csrf
                <div class="custom_form_heading"><span>To see what Policies your Loved-Ones have left you as a Beneficiary, Enter the Policy Holder’s Identity Number.</span></div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Policyholder’s Identity Number*</label>
                        <input type="text" class="form-control" name="policyholder_number" placeholder="5453453643" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Your(Beneficiary) Identity Number*</label>
                        <input type="text" class="form-control" name="beneficiary_number" placeholder="5453453643" required>
                    </div>
                </div>

                {{--<div class="custom_form_heading"><span>Ted Smith has existing policies / Investments / Funeral Covers</span></div>
                <span>For Security Purposes, please Upload a copy of Beneficiaries Identity</span>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>
                    </div>
                </div>
                <span>For Security Purposes, Please Upload a Copy of a Policy Holder’s Death Certicate</span>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a type="submit" class="custom_btn_form">Save</a>
                    </div>
                </div>
                <hr>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <span>Please Note that Verification will Take up to 5 Working Days. You will Receive an Email with the Policy / Funeral cover / Investment / will</span>
                    </div>
                </div>--}}

                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <input type="submit" class="btn custom_btn_form" value="Check">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

