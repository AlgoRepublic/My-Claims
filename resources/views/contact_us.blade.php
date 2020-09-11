@extends('app')
@section('title', 'What We Do')
@section('mainbody')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="custom_contact_section">
                    <div class="custom_page_heading">
                        <h4>Contact Us</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for=" ">Name</label>
                                        <input type="text" class="form-control" id=" " placeholder="John Deo">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Email</label>
                                        <input type="text" class="form-control" id=" " placeholder="john_deo@xyz.com">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Contact Number*</label>
                                        <input type="text" class="form-control" id=" " placeholder="+00 123456789">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Details</label>
                                        <textarea class="form-control" id=" " rows="4" ></textarea>
                                    </div>
                                </div>
                                <div class="form-row justify-content-center">
                                    <div class="form-group">
                                        <a type="submit" class="custom_btn_form">Submit</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

