@extends('admin.app')
@section('title', 'Policy Holders')
@section('maincontent')
    <h2>Policy Holder Detail</h2>
    <div class="card">
        <div class="card-header row">
            <div class="col col-sm-3">
                <h5>Name: {{ $username }}</h5>
            </div>
            <div class="col col-sm-6">
                <div class="card-search with-adv-search dropdown">

                </div>
            </div>
            <div class="col col-sm-3">
                <div class="card-options text-right">
                    <h5>IDN: {{ $documentNumber }}</h5>
                    {{--<a href="#"><i class="ik ik-plus" data-toggle="modal" data-target="#productModal"></i></a>--}}
                </div>
            </div>
        </div>
        <div class="card-body">
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
                        @if(Session::has('message'))
                            <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ Session::get('message') }}
                            </div>
                        @endif
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
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-policy" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="custom_form_heading text-center"><span>Active Policies/Will</span></div>
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
                "bFilter": false
            });
            $("#manage-bene-tbl").DataTable({
                "bFilter": false
            });
        });
    </script>
@endsection
