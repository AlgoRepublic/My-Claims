@extends('app')
@section('title', 'Home Page')
@section('mainbody')

    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Manage Policy Documents</h4>
            </div>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=" ">Name</label>
                        <hr>
                        <h5>John Deo</h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=" ">Identity Document Number</label>
                        <hr>
                        <h5>8202255695023</h5>
                    </div>
                </div>
                <div class="custom_form_heading"><span>Active Policies/Will</span></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" required >
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="custom_btn_row text-center">
                            <a type="submit" class="custom_btn_add">Add</a>
                            <a type="submit" class="custom_btn_delete">Delete</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <span>Please update your profile regularly to avoid incorrect information.</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

