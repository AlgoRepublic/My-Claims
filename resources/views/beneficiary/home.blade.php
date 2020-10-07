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
            <form action="{{ url('/beneficiary/find-policy') }}" method="post">
                @csrf
                <div class="custom_form_heading"><span>To see what policies your loved-ones have left you as a beneficiary, enter the policy holder’s identity number.</span></div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Policyholder’s Identity Number<span class="error-text">*</span></label>
                        <input type="text" class="form-control" name="policyholder_number" placeholder="5453453643" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for=" ">Enter Beneficiary's Identity Number<span class="error-text">*</span></label>
                        <input type="text" class="form-control" name="beneficiary_number" placeholder="5453453643" required>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <input type="submit" class="btn custom_btn_form" value="Check">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

