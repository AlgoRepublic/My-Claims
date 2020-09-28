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

{{--<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="forms-sample" method="post" action="{{ route('addProduct') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Add Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Product Image*</label>
                            <input type="file" name="product_image" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service*</label>
                                    <select class="form-control" name="service_id" id="pro-service" required>
                                        <option selected disabled value="">-Select Service-</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service Category</label>
                                    <select class="form-control" name="category_id" id="pro-service-cat">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service Sub Category</label>
                                    <select class="form-control" name="subcategory_id" id="pro-service-subcat">
                                    </select>
                                </div>
                            </div>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName1">Name*</label>
                            <input type="text" class="form-control" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea1">Description*</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>--}}

{{--<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="forms-sample" method="post" action="{{ route('editProduct') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Product Image*</label>
                            <div><img src="" width="100%" height="auto" id="prodImgEdit" /></div>
                            <input type="file" name="product_image" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service*</label>
                                    <select class="form-control" name="service_id" id="pro-service" required>
                                        <option selected disabled value="">-Select Service-</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service Category</label>
                                    <select class="form-control" name="category_id" id="pro-service-cat">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleSelectGender">Service Sub Category</label>
                                    <select class="form-control" name="subcategory_id" id="pro-service-subcat">
                                    </select>
                                </div>
                            </div>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName1">Name*</label>
                            <input type="text" class="form-control" name="name" placeholder="Name" id="prodNameEdit" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea1">Description*</label>
                            <textarea class="form-control" name="description" rows="4" id="prodDescEdit" required></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="product_id" id="prodIDEdit" />
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>--}}
@endsection
