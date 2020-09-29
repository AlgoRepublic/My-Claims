@extends('admin.app')
@section('title', 'Beneficiaries')
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
                <th>IDN</th>
                <th>Cell Number</th>
                <th>Creation Date</th>
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
@endsection
