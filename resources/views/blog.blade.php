@extends('app')
@section('title', 'Blog')
@section('mainbody')

    <div class="container">
        <div class="custom_blog_section">
            <div class="custom_page_heading">
                <h4>Blogs</h4>
            </div>
            <div class="card-deck">
                @foreach($blogs as $blog)
                    @if($blog->status == 1)
                        <div class="card">
                            <img class="blog-cover" src="{{ asset(parse_url($blog->image)['path']) }}" width="100%" height="150px">
                            <div class="card-body">
                                <h5 class="card-title">{{ $blog->title }}</h5>
                                <p class="card-text">
                                    {!! substr($blog->content, 0, 200) !!}...
                                </p>
                                <div class="row justify-content-center">
                                    <a href="{{ route('blog-detail', ['id' => $blog->id]) }}" class="custom_btn_form">Read
                                        More</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection


