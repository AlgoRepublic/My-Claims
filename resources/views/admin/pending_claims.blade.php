@extends('admin.app')
@section('title', 'Beneficiaries')
@section('maincontent')
<h2>Pending Claims List</h2>
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
        <table id="advanced_table" class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Beneficiary Identity Proof</th>
                <th>Policyholder Death Proof</th>
                <th>Email Preference</th>
                <th>Claim Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($claims as $claim)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a class="text-blue" href="{{ \Illuminate\Support\Facades\URL::to('/').\Illuminate\Support\Facades\Storage::url($claim->beneficiary_identity) }}" download>Beneficiary Proof</a></td>
                    <td><a class="text-blue" href="{{ \Illuminate\Support\Facades\URL::to('/').\Illuminate\Support\Facades\Storage::url($claim->policyholder_death_proof) }}" download>PolicyHolder Death Proof</a></td>
                    <td>{{ $claim->email_preference }}</td>
                    <td>{{ date('Y-m-d', strtotime($claim->beneficiary_request_date)) }}</td>
                    <td class="d-inline-flex">
                        <a class="btn btn-xs btn-success" href="{{ route('update-claim', ['id' => $claim->id,'type' => 'approved']) }}" onclick="return confirm('Are you sure you want to approve this?')">Approve</a>
                        &nbsp;
                        &nbsp;
                        <a class="btn btn-xs btn-danger" href="{{ route('update-claim', ['id' => $claim->id,'type' => 'declined']) }}" onclick="return confirm('Are you sure you want to decline this?')">Decline</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
