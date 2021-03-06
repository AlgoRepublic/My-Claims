@extends('app')
@section('title', 'What We Do')
@section('mainbody')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="custom_contact_section">
                    <div class="custom_page_heading">
                        <h4>Contact Us</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if(\Illuminate\Support\Facades\Session::has('message'))
                                <div class="alert {{ \Illuminate\Support\Facades\Session::get('alert-class', 'alert-info') }} alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ \Illuminate\Support\Facades\Session::get('message') }}
                                </div>
                            @endif
                            <form method="post" action="{{ url('/contact-us') }}">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for=" ">Name*</label>
                                        <input type="text" pattern="[A-Za-z0-9\s]+" title="Only letters and numbers are allowed!" class="form-control" name="user_name" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Email*</label>
                                        <input type="email" class="form-control" name="user_email" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Contact Number*</label>
                                        <input type="text" pattern="\d*" maxlength="10" minlength="10" title="Only digits are allowed | Length should be 10" class="form-control" name="contact_number">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for=" ">Details*</label>
                                        <textarea class="form-control" name="msg" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="form-row justify-content-center">
                                    <div class="form-group">
                                        <input type="submit" class="btn custom_btn_form">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom_contact_section custom_contact_support">
                    <h3>Support</h3>
                    <hr>
                    <ul>
                        <li><span> Contact </span><i class="fa fa-sm fa-phone"></i>0870125716</li>
                    </ul>
                    <div class="custom_social_link">
                        <ul>
                            <li><a href="https://www.linkedin.com/company/show-my-claims/" target="_blank"><i class="fa fa-2x fa-linkedin-square"></i></a></li>
                            <li><a href="https://web.facebook.com/showmycla" target="_blank"> <i class="fa fa-2x fa-facebook-square"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

