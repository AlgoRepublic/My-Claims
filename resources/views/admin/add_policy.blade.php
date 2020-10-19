@extends('admin.app')
@section('title', 'Policyholders')
@section('maincontent')
    <h2>Add Policy</h2>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="custom_form_section">
                    <form method="POST" action="{{ url('/admin/addPolicy') }}" enctype="multipart/form-data">
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
                                <input type="file" class="form-control custom-up" name="doc_file" accept=".png,.jpg,.jepg,.pdf,.doc,.docx" required>
                            </div>
                        </div>
                        <input type="hidden" name="source" value="admin" />
                        <input type="hidden" name="policyholder_id" value="{{ $policyholder_id }}" />
                        <div class="opp-attr-div"></div>
                        <div class="form-row justify-content-center">
                            <div class="form-group">
                                <a class="btn btn-lg custom_btn_delete" href="{{ url('/policyHolder/') }}">Back</a>
                                <input type="submit" class="btn custom_btn_form" value="Save" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

