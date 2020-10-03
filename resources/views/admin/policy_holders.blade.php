@extends('admin.app')
@section('title', 'Policy Holders')
@section('maincontent')
<h2>Policy Holder List</h2>
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
                <a href="#"><i class="ik ik-plus" data-toggle="modal" data-target="#productModal"></i></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="advanced_table" class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>IDN</th>
                <th>Creation Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($policyHolders as $policyHolder)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $policyHolder->name }}</td>
                    <td>{{ $policyHolder->surname }}</td>
                    <td>{{ $policyHolder->email }}</td>
                    <td>{{ $policyHolder->mobile }}</td>
                    <td>{{ $policyHolder->identity_document_number }}</td>
                    <td>{{ date('Y-m-d', strtotime($policyHolder->created_at)) }}</td>
                    <td>
                        <a href="{{ route('policyHoldersDetail', ['id' => $policyHolder->id]) }}" class="btn btn-info">View Details</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
