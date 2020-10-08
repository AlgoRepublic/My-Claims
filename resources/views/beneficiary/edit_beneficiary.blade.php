@extends('app')
@section('title', 'Home Page')
@section('mainbody')
    <div class="container">
        <div class="container">
            <div class="custom_form_section">
                <div class="custom_page_heading">
                    <h4>Edit Beneficiary</h4>
                </div>
                <form method="POST" action="{{ url('/beneficiary/edit') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=" ">Name<span class="text-danger"><b>*</b></span></label>
                            <input type="text" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed" class="form-control" name="bene_name" placeholder="Enter Name" value="{{ $beneficiary->name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Surname<span class="text-danger"><b>*</b></span></label>
                            <input type="text" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed" class="form-control" name="bene_surname" placeholder="Enter Surname" value="{{ $beneficiary->surname }}" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=" ">South African Identity Document Number<span class="text-danger"><b>*</b></span></label>
                            <input type="text" pattern="\d*" maxlength="13" minlength="13" title="Only dgits are allowed | Length should be 13." class="form-control" name="bene_document_number" value="{{ $beneficiary->identity_document_number }}" placeholder="Enter Document Number" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Cell Phone Number<span class="text-danger"><b>*</b></span></label>
                            <input type="text" pattern="\d*" maxlength="10" minlength="10" title="10 digits minimum | Only positive digits are allowed" class="form-control" name="bene_cell_number" value="{{ $beneficiary->cell_number }}" placeholder="Enter Cell Number" required="">
                        </div>
                        <input type="hidden" value="{{ $beneficiary->id }}" name="ben_id" />
                    </div>

                    <div class="form-row justify-content-center">
                        <div class="form-group">
                            <input type="submit" class="btn custom_btn_form" value="Save" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

