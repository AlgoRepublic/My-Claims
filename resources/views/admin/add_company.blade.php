@extends('admin.app')
@section('title', 'Company')
@section('maincontent')
    <h2>Add Company</h2>
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
            <form action="{{ url('/admin/addCompany') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("company_name") ? "input-group-danger" : ""}}">
                        <label for="name">Company Name:- </label>
                        <input type="text" class="form-control" id="company_name"
                               value="{{old("company_name")}}" name="company_name"/>
                        @error('company_name')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("manager_name") ? "input-group-danger" : ""}}">
                        <label for="name">Manager Name:- </label>
                        <input type="text" class="form-control" id="manager_name"
                               value="{{old("manager_name")}}" name="manager_name"/>
                        @error('manager_name')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("manager_mobile") ? "input-group-danger" : ""}}">
                        <label for="name">Manager Mobile:- </label>
                        <input type="text" class="form-control" id="manager_mobile"
                               value="{{old("manager_mobile")}}" name="manager_mobile"/>
                        @error('manager_mobile')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("manager_email") ? "input-group-danger" : ""}}">
                        <label for="name">Manager Email:- </label>
                        <input type="text" class="form-control" id="manager_email"
                               value="{{old("manager_email")}}" name="manager_email"/>
                        @error('manager_email')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("company_logo") ? "input-group-danger" : ""}}">
                        <label for="company-img">Company Logo:- </label>
                        <input type="file" class="form-control" name="company_logo" id="company_logo"
                               accept="image/jpg,image/png,image/jpeg"/>
                        @error('company_logo')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

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


