@extends('app')
@section('title', 'What We Do')
@section('mainbody')

    <div class="container">
        <div class="custom_blog_section">
            <div class="custom_page_heading">
                <h4>Blogs</h4>
            </div>
            <div class="card-deck">
                <div class="card">
                    <img src="{{ asset('/storage/img/blog_img/img-4.jpg') }}" width="100%">
                    <div class="card-body">
                        <h5 class="card-title">Lorem Ipsum</h5>
                        <p class="card-text">This is a wider card It's a broader card with text below as a natural lead-in to extra content. This content is a little longer. This card has even longer content than the first to show that equal height action.</p>
                        <div class="row justify-content-center">
                            <a type="submit" class="custom_btn_form">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('/storage/img/blog_img/img-2.jpg') }}" width="100%">
                    <div class="card-body">
                        <h5 class="card-title">Lorem Ipsum</h5>
                        <p class="card-text">This is a wider card It's a broader card with text below as a natural lead-in to extra content. This content is a little longer. This card has even longer content than the first to show that equal height action.</p>
                        <div class="row justify-content-center">
                            <a type="submit" class="custom_btn_form">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('/storage/img/blog_img/img-3.jpg') }}" width="100%">
                    <div class="card-body">
                        <h5 class="card-title">Lorem Ipsum</h5>
                        <p class="card-text">This is a wider card It's a broader card with text below as a natural lead-in to extra content. This content is a little longer. This card has even longer content than the first to show that equal height action.</p>
                        <div class="row justify-content-center">
                            <a type="submit" class="custom_btn_form">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

