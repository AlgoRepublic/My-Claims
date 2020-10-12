@extends('admin.app')
@section('title', 'Blog')
@section('maincontent')
<h2>{{ !empty($blog->id)? 'Edit':'Add' }} Blog</h2>
<div class="card">
    <div class="card-header row">
        <div class="col col-sm-3">
        </div>
        <div class="col col-sm-6">
            <div class="card-search with-adv-search dropdown">
            </div>
        </div>
        <div class="col col-sm-3">
            <div class="card-options text-right">
                {{--<a href="#"><i class="ik ik-plus" data-toggle="modal" data-target="#productModal"></i></a>--}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ url('/admin/add-blog') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for=" ">Blog Title:- </label>
                    <input type="text" class="form-control" value="{{ !empty($blog['title']) ? $blog['title'] : '' }}" name="title" required/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for=" ">Main Content :- </label>
                    <textarea name="content" cols="8" rows="10" id="editor" class="form-control">{{ !empty($blog['content']) ? $blog['content'] : '' }}</textarea>
                </div>
            </div>
            @if(!empty($blog->image))
                <img src="{{ $blog->image }}" height="100px" width="100px" />
            @endif
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for=" ">Blog Display Image:- </label>
                    <input type="file" class="form-control" name="image" id="blog-img" {{ $blog->image ?? 'required' }} accept="image/jpg,image/png,image.gif,image/jpeg"/>                </div>
            </div>
            <input type="hidden" name="id" value="{{ !empty($blog['id']) ? $blog['id'] : ''  }}">
            <div class="form-row justify-content-center">
                <div class="form-group">
                    <a href="{{ url('admin/blogs') }}" class="btn btn-warning">Cancel</a>
                    <input type="submit" class="btn btn-info">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('Page-JS')
    <script>
        $(document).ready(function() {

            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        });
    </script>
@endsection
