
@extends('backEnd.layouts.master')
@section('title','Banner Create')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Blog</h4>
                </div>
                <div class="card-body">

                    <form  method="post" action="{{route('update')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="p-2">Title</label>
                            <input type="text" class="form-control " name="b_title"  value="{{$edit->b_title}}">
                            <input type="hidden" class="form-control " name="id"  value="{{$edit->id}}">
                        </div>
                        <div class="form-group">
                            <label  class="p-2">Short_Description</label>
                            <textarea   name="b_short_des" class="form-control "  >{{$edit->b_short_des}}</textarea>

                        <div class="form-group">
                            <label  class="p-2">Long_Description</label>

                            <div>
                                 <textarea   name="b_long_des" id="body" class="form-control" >{{$edit->b_long_des}}</textarea>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="p-2">Image</label> <br>
                            <img style="height:50px" src="{{ asset($edit->b_image) }}" alt="">
                            <input type="file" class="form-control" name="b_image"   >
                        </div>

                        {{-- <div class="form-group">
                            <label class="p-2">Author</label>
                            <input type="text" class="form-control " name="b_author"  value="{{$edit->b_author}}">
                        </div>
                        <div class="form-group">
                            <label class="p-2">Author-image</label> <br>
                            <img style="height:50px" src="{{ asset($edit->b_author_image) }}" alt="">
                            <input type="file" class="form-control" name="b_author_image"    >
                        </div> --}}
                        <div class="form-group">
                            <label class="p-2">status</label>
                            <select class="form-control pb-2"  placeholder="status" name="status"   value="{{$edit->status}}">
                               <option value="1">Active</option>
                               <option value="0">Deactive</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="p-2">Blog-Date</label>
                            <input type="date" class="form-control" name="b_date"    value="{{$edit->b_date}}">
                        </div>
                          <div class="form-group p-2">
                           <input type="submit" class="btn btn-danger rounded" value="Update">
                          </div>



    </div>
</div>

<script>
    ClassicEditor
    .create( document.querySelector( '#body' ) )

    .catch( error => {
    console.error( error );
    });
</script>



@endsection





