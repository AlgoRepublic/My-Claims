@extends('admin.app')
@section('title', 'Beneficiaries')
@section('maincontent')
<h2>Declined Claims List</h2>
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
        <div class="custom_data_table_responsive">
            <table id="advanced_table" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Policyholder IDN</th>
                    <th>Beneficiary Identity Proof</th>
                    <th>Policyholder Death Proof</th>
                    <th>Email Preference</th>
                    <th>Claim Date</th>
                    <th>Declined Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($claims as $claim)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $claim->user->identity_document_number ?? 'Null' }}</td>
                        <td><a class="text-blue" href="{{ \Illuminate\Support\Facades\URL::to('/').\Illuminate\Support\Facades\Storage::url($claim->beneficiary_identity) }}" download>Beneficiary Proof</a></td>
                        <td><a class="text-blue" href="{{ \Illuminate\Support\Facades\URL::to('/').\Illuminate\Support\Facades\Storage::url($claim->policyholder_death_proof) }}" download>PolicyHolder Death Proof</a></td>
                        <td>{{ $claim->email_preference }}</td>
                        <td>{{ date('Y-m-d', strtotime($claim->beneficiary_request_date)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($claim->approved_date)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
