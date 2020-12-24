@extends('admin.app')
@section('title', 'Add Company User')
@section('maincontent')
    <h2>Add User</h2>
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
            <form action="{{ route('companyAddUserAdmin', ['company' => $company->id]) }}" method="post">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("name") ? "input-group-danger" : ""}}">
                        <label for="name">Name:- </label>
                        <input type="text" class="form-control" id="name"
                               value="{{old("name")}}" name="name"/>
                        @error('name')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("email") ? "input-group-danger" : ""}}">
                        <label for="name">Email:- </label>
                        <input type="text" class="form-control" id="email"
                               value="{{old("email")}}" name="email"/>
                        @error('email')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12 {{$errors->has("mobile") ? "input-group-danger" : ""}}">
                        <label for="name">Mobile:- </label>
                        <input type="text" class="form-control" id="mobile"
                               value="{{old("mobile")}}" name="mobile"/>
                        @error('mobile')
                        <label class="error mt-2 text-danger">{{$message}}</label>
                        @enderror
                    </div>
                </div>

                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a href="{{ route('companyUsersAdmin', ['company'=>$company->id]) }}"
                           class="btn btn-warning">Cancel</a>
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


