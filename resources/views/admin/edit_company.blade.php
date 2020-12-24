@extends('admin.app')
@section('title', 'Company')
@section('maincontent')
    <h2>Edit Company</h2>
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
            <form action="{{ url('/admin/editCompany') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("company_name") ? "input-group-danger" : ""}}">
                        <label for="name">Company Name:- </label>
                        <input type="text" class="form-control" id="company_name" value="{{$company->name}}"
                               name="company_name"/>
                        @error('company_name')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        @if(!empty($company->logo))
                            <img src="{{ asset( str_replace("public/", "/storage/", $company->logo)) }}" height="100px" width="100px"/>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="company-img">Company Logo:- </label>
                                <input type="file" class="form-control" name="company_logo" id="company_logo"
                                       accept="image/jpg,image/png,image.gif,image/jpeg"/>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{$company->id}}">

                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a href="{{ url('admin/companies') }}" class="btn btn-warning">Cancel</a>
                        <input type="submit" class="btn btn-info">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('Page-JS')
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection

<style>

</style>


