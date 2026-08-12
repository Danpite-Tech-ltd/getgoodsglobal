@extends('backEnd.layouts.master')
@section('title','Banner Edit')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid mt-4">
    <!-- end page title -->
   <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{route('featurebanner.update',$edit_data->id)}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_one" class="form-label">Image One</label>
                            <input type="file" class="form-control @error('image_one') is-invalid @enderror" name="image_one"  id="image_one" >
                            <img src="{{asset($edit_data->image_one)}}" alt="" class="edit-image">
                            @error('image_one')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title_one" class="form-label">Title One</label>
                            <input type="text" class="form-control @error('title_one') is-invalid @enderror" name="title_one" value="{{$edit_data->title_one}}" id="title_one">
                            @error('title_one')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_two" class="form-label">Image Two</label>
                            <input type="file" class="form-control @error('image_two') is-invalid @enderror" name="image_two"   id="image_two" >
                            <img src="{{asset($edit_data->image_two)}}" alt="" class="edit-image">
                            @error('image_two')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title_two" class="form-label">Title Two</label>
                            <input type="text" class="form-control @error('title_two') is-invalid @enderror" name="title_two" value="{{$edit_data->title_two}}" id="title_two">
                            @error('title_two')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_three" class="form-label">Image Three</label>
                            <input type="file" class="form-control @error('image_three') is-invalid @enderror" name="image_three"  id="image_three" >
                            <img src="{{asset($edit_data->image_three)}}" alt="" class="edit-image">
                            @error('image_three')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title_three" class="form-label">Title Three</label>
                            <input type="text" class="form-control @error('title_three') is-invalid @enderror" name="title_three" value="{{$edit_data->title_three}}" id="title_three">
                            @error('title_three')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="image_four" class="form-label">Image Four</label>
                            <input type="file" class="form-control @error('image_four') is-invalid @enderror" name="image_four"  id="image_four" >
                            <img src="{{asset($edit_data->image_four)}}" alt="" class="edit-image">
                            @error('image_four')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="title_four" class="form-label">Title Four</label>
                            <input type="text" class="form-control @error('title_four') is-invalid @enderror" name="title_four" value="{{$edit_data->title_four}}" id="title_four">
                            @error('title_four')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div>
                        <button type="submit" class="btn btn-success">Submit</button>
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
@endsection
