@extends('admin.app')
@section('title', 'Company Users')
@section('maincontent')
    <h2>Company: {{$company->name}}</h2>
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
                    <a href="{{ route('companyAddUserViewAdmin', ['company'=>$company->id]) }}"
                       class="btn btn-xs btn-info">Add
                        User</a>
                    <a href="{{route('companyAdmin')}}" class="btn btn-xs btn-info">Go Back</a>
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
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile}}</td>
                            <td><span class="badge badge-primary">{{ $user->role->role_name }}</span></td>
                            <td class="d-inline-flex">
                                <a class="btn btn-xs btn-primary mr-2"
                                   href="{{route('companyEditUserViewAdmin', ['company'=> $company->id, 'user'=> $user->id])}}">Edit</a>
                                <a class="btn btn-xs btn-danger"
                                   href="{{route('companyDeleteUserAdmin', ['company'=> $company->id, 'user'=> $user->id])}}"
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

