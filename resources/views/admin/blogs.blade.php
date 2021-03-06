@extends('admin.app')
@section('title', 'Beneficiaries')
@section('maincontent')
    <h2>Blogs List</h2>
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
                    <a href="{{ url('admin/add-blog') }}" class="btn btn-xs btn-info">Add Blog</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="custom_data_table_responsive">
                <table id="advanced_table" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Blog Title</th>
                        <th>Blog Image</th>
                        <th>Current Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $blog->title }}</td>
                            <td><img src="{{ asset(parse_url($blog->image)['path']) }}" height="50" width="50"/></td>
                            <th>
                                @if($blog->status == 0)
                                    <span class="badge badge-info">Drafted</span>
                                @elseif($blog->status == 1)
                                    <span class="badge badge-success">Published</span>
                                @endif
                            </th>
                            <td>{{ date('Y-m-d', strtotime($blog->created_at)) }}</td>
                            <td class="d-inline-flex">
                                <a class="btn btn-xs btn-success" href="{{ route('blogs', ['id' => $blog->id]) }}">Details</a>
                                &nbsp;
                                &nbsp;
                                <a class="btn btn-xs btn-danger" href="{{ route('deleteBlog', ['id' => $blog->id]) }}"
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

