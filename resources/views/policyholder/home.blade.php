@extends('app')
@section('title', 'Home Page')
@section('mainbody')

    <div class="container">
        <div class="custom_form_section">
            @if(Session::has('message'))
                <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            <div class="custom_page_heading">
                <h4>Manage Policy Documents</h4>
            </div>
            @error('error')
            <div class="alert alert-danger" role="alert">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            {{--@if(!empty($trail_expiration_date))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ "Free Trial will be ending on ".$trail_expiration_date }}</strong>
                </div>
            @endif--}}
            <div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=" ">Policyholder Name</label>
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
                <div class="custom_policy_tab_wrapper">

                    <ul class="nav nav-pills pills-dark mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-beneficiaries" role="tab" aria-controls="pills-home" aria-selected="true">Beneficiaries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-policy" role="tab" aria-controls="pills-profile" aria-selected="false">Policies</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-beneficiaries" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="custom_form_heading text-center"><span>All Beneficiaries</span></div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="custom_btn_row text-right">
                                        <form method="post" action="{{ url('policyHolder/add-beneficiary-or-policy') }}">
                                            @csrf
                                            <input type="hidden" name="request_for" value="beneficiary">
                                            <input type="submit" class="custom_btn_add btn btn-sm custom-resp-btn" value="Add Beneficiary">
                                        </form>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="custom_data_table_responsive">
                                            <table id="manage-bene-tbl" class="table table-hover" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Beneficiary Name</th>
                                                    <th>Beneficiary Surname</th>
                                                    <th>Identity Document Number</th>
                                                    <th>Cell Number</th>
                                                    <th>Added Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($beneficiaries as $beneficiary)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $beneficiary->name }}</td>
                                                        <td>{{ $beneficiary->surname }}</td>
                                                        <td>{{ $beneficiary->identity_document_number }}</td>
                                                        <td>{{ $beneficiary->cell_number }}</td>
                                                        <td>{{ date('Y-m-d', strtotime($beneficiary->created_at)) }}</td>
                                                        <td style="display: inline-flex;">
                                                            <a href="{{ route('editBeneficiary',['id' => $beneficiary->id]) }}" class="btn btn-sm btn-info custom-width">Edit</a>&nbsp;&nbsp;
                                                            <a onclick="return confirm('Are you sure you would like to delete this beneficiary')" href="{{ route('deleteBeneficiary',['id' => $beneficiary->id]) }}" class="btn btn-sm btn-danger custom-width">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group">
                                    <span>Please ensure that your beneficiary's cell phone number(s) are always correct. This will help when they download the policyholder's documents as we will call them for security purposes.</span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-policy" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="custom_form_heading text-center"><span>All Active Policies/Will</span></div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="custom_btn_row text-right">
                                        <form method="post" action="{{ url('policyHolder/add-beneficiary-or-policy') }}">
                                            @csrf
                                            <input type="hidden" name="request_for" value="policy">
                                            <input type="submit" class="custom_btn_add btn btn-sm custom-resp-btn" value="Add Policy">
                                        </form>
                                        {{--<br>
                                        <a href="{{ url('/policyHolder/addPolicy') }}" class="custom_btn_add btn btn-sm custom-resp-btn">Add Policy</a>--}}
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
                                                    <th>Name of Institution</th>
                                                    <th>Type of policy</th>
                                                    <th>Policy Number</th>
                                                    <th>Policy Document</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($policies as $policy)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $policy->institute_name }}</td>
                                                        <td>{{ $policy->type }}</td>
{{--                                                        <td>{{ ucfirst(str_replace('_',' ', $policy->type)) }}</td>--}}
                                                        <td>{{$policy->policy_number}}</td>
                                                        <td>
                                                            <a href="{{ \Illuminate\Support\Facades\URL::to('/').'/public/'.\Illuminate\Support\Facades\Storage::url($policy->document) }}" download>
                                                                <p class="font-weight-bold mb-0">{{ $policy->document_original_name }}</p>
                                                            </a>
                                                        </td>
{{--                                                        <td>{{ date('Y-m-d', strtotime($policy->created_at)) }}</td>--}}
                                                        <td>
                                                            <a href="{{ route('editPolicy',['id' => $policy->id]) }}" class="btn btn-sm btn-info custom-width">Edit</a>&nbsp;&nbsp;
                                                            <a onclick="return confirm('Are you sure you would like to delete this document?')" href="{{ route('deletePolicy',['id' => $policy->id]) }}" class="btn btn-sm btn-danger">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group">
                                    <span>Please ensure that your policies are kept up to date. Show my claims will not be held liable for inactive policies.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('Page-JS')
    <script>
        $(document).ready(function() {

            $("#manage-policy-tbl").DataTable({
                "bFilter": false,
                "language": {
                    "emptyTable": "No policies added"
                }
            });
            $("#manage-bene-tbl").DataTable({
                "bFilter": false,
                "language": {
                    "emptyTable": "No beneficiaries added"
                }
            });
        });
    </script>
@endsection
