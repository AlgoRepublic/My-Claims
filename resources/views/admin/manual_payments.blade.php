@extends('admin.app')
@section('title', 'Policyholders')
@section('maincontent')
    <h2>Unverified Manual Payments</h2>
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
            <div class="table-responsive-sm">
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
                                <a data-toggle="modal" data-target="#msgModal" data-id="{{ $policyHolder->id }}" class="btn btn-info verify-pay-btn">Verify Payment</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="forms-sample" method="post" action="{{ url('admin/verifyPayment') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demoModalLabel">Payment Verification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p><i><b>Please carefully add the date on which client actually paid the bill. Expiration would be set according to the package selected by the client.</b></i></p>
                                <label for=" ">Funds Received Date:- </label>
                                <input type="date" class="form-control" name="received_date" placeholder="{{ date('Y-m-d') }}" required />
                                <input type="hidden" class="form-control" id="pol-pay-id" name="policyholder_id" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
