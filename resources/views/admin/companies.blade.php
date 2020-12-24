@extends('admin.app')
@section('title', 'Companies')
@section('maincontent')
    <h2>Companies List</h2>
    <div class="card">
        <div class="card-header row">
            <div class="col col-sm-3">
            </div>
            <div class="col col-sm-6">
                <div class="card-search with-adv-search dropdown">
                </div>
            </div>
            <div class="col col-sm-3">
                <div class="text-right">
                    <a href="{{ url('admin/addCompany') }}" class="btn btn-xs btn-info">Add Company</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="custom_data_table_responsive">
                <table id="advanced_table" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $company->name }}</td>
                            <td><img src="{{ asset( str_replace("public/", "/storage/", $company->logo) )  }} "
                                     height="50" width="50"/></td>
                            <td>{{ date('Y-m-d',  strtotime($company->created_at)) }}</td>
                            <td class="d-inline-flex">
                                <a class="btn btn-xs btn-primary mr-2"
                                   href="{{ route('editCompanyAdmin', ['id' => $company->id]) }}">Edit</a>
                                <a class="btn btn-xs btn-primary mr-2"
                                   href="{{ route('companyUsersAdmin', ['company' => $company->id]) }}">Users</a>
                                <a class="btn btn-xs btn-danger"
                                   href="{{ route('deleteCompanyAdmin', ['id' => $company->id]) }}"
                                   onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

