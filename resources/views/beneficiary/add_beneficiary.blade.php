@extends('app')
@section('title', 'Home Page')
@section('mainbody')
    <div class="container">
        <div class="container">
            <div class="custom_form_section">
                <div class="custom_page_heading">
                    <h4>Add Beneficiary</h4>
                </div>
                <form method="POST" action="{{ url('/beneficiary/add') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=" ">Name<span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="bene_name" placeholder="Enter Name" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Surname<span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="bene_surname" placeholder="Enter Surname" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for=" ">South African Identity Document Number<span class="text-danger"><b>*</b></span></label>
                            <input type="number" class="form-control" name="bene_document_number" placeholder="Enter Document Number" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for=" ">Cell Phone Number<span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="bene_cell_number" placeholder="Enter Cell Number" required="">
                        </div>
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

