@extends('app')
@section('title', 'Add Policy')
@section('mainbody')
    <style>
        .custom-upload-input{
            border: none;
        }
        .custom-upload-input-row{
            display: flex;
        }
        .custom-upload-input-row a.custom-upload-file-download{
            margin: 10px 12px;
        }
        .custom-upload-input-row a.custom-upload-file-download:hover{
            text-decoration: none;
        }
        .custom-upload-input-row a.custom-upload-file-delete{
        margin: 10px 0px 0px auto;
        }
    </style>

    <div class="container">
        <div class="custom_form_section">
            <div class="custom_page_heading">
                <h4>Edit Policy</h4>
            </div>
            <form method="POST" action="{{ url('/policyHolder/editPolicy') }}" enctype="multipart/form-data">
                @csrf
                <div class="custom_form_heading"><span>Add your policies</span></div>
                <div class="form-row">
                    {{--<div class="form-group col-md-6">
                        <label for=" ">Document Name<span class="text-danger"><b>*</b></span></label>
                        <input type="text" class="form-control" name="doc_name" placeholder="Enter Document Name" required>
                    </div>--}}
                    <div class="form-group col-md-6">
                        <label for=" ">Name of institution<span class="text-danger"><b>*</b></span></label>
                        <input type="text" class="form-control" name="institute_name" value="{{$policy->institute_name}}" placeholder="" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Type of policy<span class="text-danger"><b>*</b></span></label>
                        <input type="text" class="form-control" value="{{$policy->type}}" name="policy_type" required>
                    </div>
                    {{--<div class="form-group col-md-6">
                        <label>Select Policy Type<span class="text-danger"><b>*</b></span></label>
                        <select class="form-control" name="policy_type">
                            <option value="" selected disabled>-Select Type-</option>
                            <option value="life_cover">Life cover</option>
                            <option value="funeral_cover">Funeral cover</option>
                            <option value="investment">Investment </option>
                            <option value="will">Will</option>
                            <option value="other">Other</option>
                        </select>
                    </div>--}}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Policy number</label>
                        <input type="text" class="form-control" name="policy_number" value="{{$policy->policy_number}}">
                    </div>
                    <div class="form-group col-md-6">
                        {{--<div class="custom-file">
                            <input type="file" class="custom-file-input" name="doc_file" id="upPolicyDoc" required>
                            <label class="custom-file-label" for="upPolicyDoc">Choose file...</label>
                        </div>--}}
                        <label>Upload a policy document (Optional)</label>
                        <input type="file" class="form-control custom-up custom-upload-input" name="doc_file" accept=".png,.jpg,.jepg,.pdf,.doc,.docx" value="{{ \Illuminate\Support\Facades\URL::to('/').'/public/'.\Illuminate\Support\Facades\Storage::url($policy->document) }}">
                        @if($policy->document_original_name)
                            <div id="policyDocument" class="custom-upload-input-row">
                                <a href="{{ \Illuminate\Support\Facades\URL::to('/').'/public/'.\Illuminate\Support\Facades\Storage::url($policy->document) }}" class="custom-upload-file-download" download>
                                    <p class="font-weight-bold mb-0"> {{ $policy->document_original_name }}</p>
                                </a>
                                <a href="javascript:void(0)" onclick="deleteDocument()" class="custom-upload-file-delete"> <i class="fas fa-trash"></i></a>
                            </div>
                        @endif
                    </div>
                </div>

                {{--<div class="custom_form_heading"><span>Select Beneficiaries</span></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Select Beneficiaries<span class="text-danger"><b>*</b></span></label>
                        <select class="form-control" id="add-policy-ben" name="beneficiaries[]" multiple>
                            @foreach($benList as $ben)
                                <option value="{{ $ben->id }}">{{ $ben->name . ' '. $ben->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}
                <div class="opp-attr-div"></div>
                {{--<a id="creat-new-ben" style="color: red;cursor: pointer;">Create New Beneficiary</a>--}}
                <input type="hidden" value="{{ $policy->id }}" name="id" />


                <div class="form-row justify-content-center">
                    <div class="form-group">
                        <a class="btn btn-lg custom_btn_delete" href="{{ url('/policyHolder/') }}">Back</a>
                        <input type="submit" class="btn custom_btn_form" value="Save" />
                    </div>
                </div>
            </form>
            <div class="alert alert-info" role="alert">
                If you don’t know what your <strong>policy number</strong> is, please call the institution you have the policy with and request them to give it to you.
            </div>
        </div>
    </div>

    <script>
        function deleteDocument()
        {
            if(confirm("Are you sure you want to delete this document?"))
            {
                let token = $("input[name='_token']").val()
                $.ajax({
                    method : 'POST',
                    url : '/policyHolder/deletePolicyDocument',
                    data : {policy_id : "{{$policy->id}}", _token : token },
                    dataType : 'JSON',
                    success: function (data) {
                        console.log("success")
                        alert (data.message);
                        $("div[id='policyDocument']").remove();
                    },
                    complete: function () {
                        console.log("Submit Complete!")
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("error")

                    }
                });
            }

        }
    </script>
@endsection

