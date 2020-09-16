@extends('app')
@section('title', 'Home Page')
@section('mainbody')

    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Manage Policy Documents</h4>
            </div>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for=" ">Name</label>
                        <hr>
                        <h5>{{ $username }}</h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for=" ">Identity Document Number</label>
                        <hr>
                        <h5>8202255695023</h5>
                    </div>
                </div>
                <div class="custom_form_heading text-center"><span>Active Policies/Will</span></div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom_btn_row text-right">
                            <a href="{{ url('/policyHolder/addPolicy') }}" class="custom_btn_add btn btn-sm">Add Policy</a>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="custom_data_table_responsive">
                                <table id="manage-policy-tbl" class="table table-hover" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Passport Number</th>
                                        <th>Contact Number</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <a href="#">
                                                <p class="font-weight-bold mb-0">Tanya Smith</p>
                                            </a>
                                        </td>
                                        <td>9205036698563</td>
                                        <td>0835416582</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h" data-toggle="tooltip" data-placement="top"
                                                       title="Actions"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                    <a class="dropdown-item" href="#"> Add New</a>
                                                    <a class="dropdown-item text-danger" href="#"> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">
                                                <p class="font-weight-bold mb-0">John Smith</p>
                                            </a>
                                        </td>
                                        <td>8506052205089</td>
                                        <td>+07901234567</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h" data-toggle="tooltip" data-placement="top"
                                                       title="Actions"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                    <a class="dropdown-item" href="#"> Add New</a>
                                                    <a class="dropdown-item text-danger" href="#"> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#">
                                                <p class="font-weight-bold mb-0">Tod Smith</p>
                                            </a>
                                        </td>
                                        <td>9604024479078</td>
                                        <td>+0732652528</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h" data-toggle="tooltip" data-placement="top"
                                                       title="Actions"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                    <a class="dropdown-item" href="#">  Add New</a>
                                                    <a class="dropdown-item text-danger" href="#"> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" required >
                            <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="custom_btn_row text-center">
                            <a type="submit" class="custom_btn_add">Add</a>
                            <a type="submit" class="custom_btn_delete">Delete</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <span>Please update your profile regularly to avoid incorrect information.</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

