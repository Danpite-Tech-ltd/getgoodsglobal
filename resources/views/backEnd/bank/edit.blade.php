@extends('backEnd.layouts.master')
@section('title','Bank Edit')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/backEnd') }}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet"
@endsection
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('bank.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Bank Edit</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{route('bank.update')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$edit_data->id}}" name="id">
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="account_name" class="form-label">Account Name *</label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ $edit_data->account_name }}" id="account_name" required="">
                            @error('account_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @if($edit_data->id == 5)
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="cod_acount_name" class="form-label">cod acount_name *</label>
                            <input type="text" class="form-control @error('cod_acount_name') is-invalid @enderror" name="cod_acount_name" value="{{ $edit_data->cod_acount_name }}" id="cod_acount_name" required="">
                            @error('cod_acount_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="account_number" class="form-label">Account Number *</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ $edit_data->account_number }}" id="account_number" required="">
                            @error('account_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="branch" class="form-label">Branch Name </label>
                            <input type="text" class="form-control @error('branch') is-invalid @enderror" name="branch" value="{{ $edit_data->branch }}" id="branch">
                            @error('branch')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="routing_number" class="form-label">Routing Number</label>
                            <input type="text" class="form-control @error('routing_number') is-invalid @enderror" name="routing_number" value="{{ $edit_data->routing_number }}" id="routing_number">
                            @error('routing_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image" class="form-label">Image </label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ $edit_data->image }}"  id="image" >
                            <img src="{{asset($edit_data->image)}}" alt="" class="edit-image">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="cod" class="form-label">COD Percentage</label>
                            <input type="text" class="form-control @error('cod') is-invalid @enderror" name="cod" value="{{ $edit_data->cod }}" id="cod">
                            @error('cod')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="description" class="form-label">Description *</label>
                            <textarea name="description" id="description" rows="6"
                                class="summernote form-control @error('description') is-invalid @enderror"
                                
                                >{{ $edit_data->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col-end -->
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="status" class="d-block">Status</label>
                            <label class="switch">
                              <input type="checkbox" value="1" name="status" @if($edit_data->status==1)checked @endif>
                              <span class="slider round"></span>
                            </label>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/libs//summernote/summernote-lite.min.js"></script>

<script>
        $(".summernote").summernote({
            placeholder: "Enter Your Text Here",
        });
    </script>
@endsection