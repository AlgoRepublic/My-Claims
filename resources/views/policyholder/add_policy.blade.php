@extends('app')
@section('title', 'Add Policy')
@section('mainbody')

    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Add Policy</h4>
            </div>
            <form method="POST" action="{{ url('/policyHolder/addPolicy') }}" enctype="multipart/form-data">
                @csrf
                <div class="custom_form_heading"><span>Enter Name of the Institution and Type of Policy</span></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=" ">Document Name<span class="text-danger"><b>*</b></span></label>
                        <input type="text" class="form-control" name="doc_name" placeholder="Enter Document Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Select Policy Type<span class="text-danger"><b>*</b></span></label>
                        <select class="form-control" name="policy_type">
                            <option value="" selected disabled>-Select Type-</option>
                            <option value="life_cover">Life cover</option>
                            <option value="funeral_cover">Funeral cover</option>
                            <option value="investment">Investment </option>
                            <option value="will">Will</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="custom_form_heading"><span>Upload Policy Document</span></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{--<div class="custom-file">
                            <input type="file" class="custom-file-input" name="doc_file" id="upPolicyDoc" required>
                            <label class="custom-file-label" for="upPolicyDoc">Choose file...</label>
                        </div>--}}
                        <input type="file" class="form-control custom-up" name="doc_file" accept=".png,.jpg,.jepg,.pdf,.doc,.docx" required>
                    </div>
                </div>

                {{--<div class="custom_form_heading"><span>Select Beneficiaries</span></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Select Beneficiaries<span class="text-danger"><b>*</b></span></label>
                        <select class="form-control" id="add-policy-ben" name="beneficiaries[]" multiple>
                            @foreach($benList as $ben)
                                <option value="{{ $ben->id }}">{{ $ben->name . ' '. $ben->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}
                <div class="opp-attr-div"></div>
                {{--<a id="creat-new-ben" style="color: red;cursor: pointer;">Create New Beneficiary</a>--}}

                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a class="btn btn-lg custom_btn_delete" href="{{ url('/policyHolder/') }}">Back</a>
                        <input type="submit" class="btn custom_btn_form" value="Save" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

