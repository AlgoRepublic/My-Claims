@extends('admin.app')
@section('title', 'Expired Subscriptions')
@section('maincontent')
    <h2>Expired Subscriptions List</h2>
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
                <table id="admin-exp-sub-tbl" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Policyholder Surname</th>
                        <th>Email</th>
                        <th>Cell Number</th>
                        <th>IDN</th>
                        <th>Expired Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expired_subscription_users as $expired_subscription_user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expired_subscription_user->name }}</td>
                            <td>{{ $expired_subscription_user->surname }}</td>
                            <td>{{ $expired_subscription_user->email }}</td>
                            <td>{{ $expired_subscription_user->mobile }}</td>
                            <td>{{ $expired_subscription_user->identity_document_number }}</td>
                            <td>{{ date('Y-m-d', strtotime($expired_subscription_user->payment->expiration_date)) }}</td>
                            <td class="d-inline-flex">&nbsp;&nbsp;
                                <a onclick="return confirm('Are you sure you want to Send SMS?')"
                                   href="{{ route('expiredSubscriptionSendSMS',['id' => $expired_subscription_user->id]) }}"
                                   class="btn btn-xs btn-danger">Send SMS</a>
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
        $(document).ready(function () {

            $("#admin-exp-sub-tbl").DataTable();
        });
    </script>
@endsection
