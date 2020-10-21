@extends('admin.app')
@section('title', 'Beneficiaries')
@section('maincontent')
<h2>Contact Requests List</h2>
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
        <div class="table-responsive-sm">
            <table id="advanced_table" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Cell Number</th>
                    <th>Sent To</th>
                    <th>Sent Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $request->user_name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->contact_number }}</td>
                        <td>{{ $request->send_to }}</td>
                        <td>{{ date('Y-m-d', strtotime($request->created_at)) }}</td>
                        <td>
                            <a data-toggle="modal" data-target="#msgModal" data-msg="{{ base64_encode($request->message) }}" class="btn btn-info admin-msg-btn">View Message</a>
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
            <form class="forms-sample" method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Contact Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for=" ">Email Message:- </label>
                            <textarea cols="8" rows="10" class="form-control" id="admin-contac-msg" readonly></textarea>
                        </div>
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
