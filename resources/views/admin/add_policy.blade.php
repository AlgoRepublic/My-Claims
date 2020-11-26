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
                        <div class="custom_form_heading"><span>Add your policies</span></div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for=" ">Name of institution<span class="text-danger"><b>*</b></span></label>
                                <input type="text" class="form-control" name="institute_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Type of policy<span class="text-danger"><b>*</b></span></label>
                                <input type="text" class="form-control" name="policy_type" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Policy number</label>
                                <input type="text" class="form-control" name="policy_number">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Upload a policy document (Optional)</label>
                                <input type="file" class="form-control custom-up" name="doc_file" accept=".png,.jpg,.jepg,.pdf,.doc,.docx">
                            </div>
                        </div>
                        <input type="hidden" name="source" value="admin" />
                        <input type="hidden" name="policyholder_id" value="{{ $policyholder_id }}" />
                        <div class="opp-attr-div"></div>
                        <div class="form-row justify-content-center">
                            <div class="form-group">
                                <a class="btn btn-lg custom_btn_delete" href="{{ url('admin/policyHolders') }}">Back</a>
                                <input type="submit" class="btn custom_btn_form" value="Save" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

