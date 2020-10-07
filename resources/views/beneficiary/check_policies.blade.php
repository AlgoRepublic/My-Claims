@extends('app')
@section('title', 'Home Page')
@section('mainbody')
    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Authorised Beneficiaries</h4>
            </div>
            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            <form action="{{ url('/beneficiary/policy-request') }}" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Policyholder’s Identity Number</label>
                        <input type="text" class="form-control" value="{{ $policyholder_number }}" name="policyholder_idn" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Your(Beneficiary) Identity Number</label>
                        <input type="text" class="form-control" value="{{ $beneficiary_number }}" name="beneficiary_idn" readonly>
                    </div>
                </div>
                @csrf
                <input type="hidden" name="ben_id" value="{{ $ben_id }}" />
                <div class="custom_form_heading">
                    <span>
                        <b>{{ $name }}</b> has existing policies
                        @foreach($policy_type as $type)
                            /<b>{{ ucwords(str_replace('_',' ', $type))}}</b>
                        @endforeach
                    </span>
                </div>
                <span>For Security Purposes, Please Upload a copy of Beneficiaries Identity</span>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        {{--<div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" name="beneficiary_identity" required>
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>--}}
                        <input type="file" class="form-control custom-up" name="beneficiary_identity" accept=".png,.jpg,.jepg,.pdf,.doc,.docx" required>
                    </div>
                </div>
                <span>For Security Purposes, Please Upload a Copy of a Policy Holder’s Death Certificate</span>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        {{--<div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" name="policy_identity" required>
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>--}}
                        <input type="file" class="form-control custom-up" name="policy_identity" accept=".png,.jpg,.jepg,.pdf,.doc,.docx" required>
                    </div>
                </div>
                <span>Please enter an Email address where you wish these documents to be sent to</span>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="email" class="form-control" placeholder="john_deo@xyz.com" name="email_preference">
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a class="btn btn-lg custom_btn_delete" href="{{ url('/beneficiary/') }}">Back</a>
                        <input type="submit" class="btn custom_btn_form">
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

