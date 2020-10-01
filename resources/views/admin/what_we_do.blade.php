@extends('admin.app')
@section('title', 'What We Do')
@section('maincontent')
<h2>What We Do</h2>
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
        <form action="{{ url('/admin/what-we-do') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for=" ">What We Do Content :- </label>
                    <textarea name="what_we_do" rows="10" id="editor" class="form-control">{{ $settings['what_we_do'] }}</textarea>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ $settings['id'] }}">
            <div class="form-row justify-content-center">
                <div class="form-group">
                    <input type="submit" class="btn btn-info">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
