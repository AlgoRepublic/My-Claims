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
                        <h5>{{ $username }}</h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=" ">Identity Document Number</label>
                        <hr>
                        <h5>{{ $documentNumber }}</h5>
                    </div>
                </div>
                <hr>
                <hr>
                <div class="custom_form_heading text-center"><span>Active Policies/Will</span></div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom_btn_row text-right">
                            <a href="{{ url('/policyHolder/addPolicy') }}" class="custom_btn_add btn btn-sm">Add Policy</a>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="custom_data_table_responsive">
                                <table id="manage-policy-tbl" class="table table-hover" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>Type</th>
                                        <th>Document</th>
                                        <th>Added Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($policies as $policy)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $policy->name }}</td>
                                            <td>{{ ucfirst(str_replace('_',' ', $policy->type)) }}</td>
                                            <td>
                                                <a href="{{ \Illuminate\Support\Facades\URL::to('/').\Illuminate\Support\Facades\Storage::url($policy->document) }}" download>
                                                    <p class="font-weight-bold mb-0">{{ $policy->document_original_name }}</p>
                                                </a>
                                            </td>
                                            <td>{{ date('Y-m-d', strtotime($policy->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="form-row">
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
                </div>--}}
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

