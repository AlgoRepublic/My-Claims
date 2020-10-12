@extends('admin.app')
@section('title', 'Policyholders')
@section('maincontent')
<h2>Policyholders List</h2>
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

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="custom_data_table_responsive">
            <table id="admin-pol-tbl" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Policyholder Name</th>
                    <th>Policyholder Surname</th>
                    <th>Email</th>
                    <th>Cell Number</th>
                    <th>IDN</th>
                    <th>Creation Date</th>
                    <th>Actions</th>
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
                        <td class="d-inline-flex">
                            <a href="{{ route('policyHoldersDetail', ['id' => $policyHolder->id]) }}" class="btn btn-xs btn-info">View Details</a>
                            &nbsp;&nbsp;
                            <a href="{{ route('policyHoldersEdit', ['id' => $policyHolder->id]) }}" class="btn btn-xs btn-primary">Edit</a>
                            &nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete this policyholder?')" href="{{ route('deletePolicyHolder',['id' => $policyHolder->id]) }}" class="btn btn-xs btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('Page-JS')
    <script>
        $(document).ready(function() {

            $("#admin-pol-tbl").DataTable();
        });
    </script>
@endsection
