
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
                    <h4 class="card-title">Blog Create</h4>
                </div>
                <div class="card-body">

                    <form  method="post" action="{{route('blog_store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="p-2">Title</label>
                            <input type="text" class="form-control " name="b_title" required>
                        </div>
                        <div class="form-group">
                            <label  class="p-2">Short_Description</label>
                            <textarea   name="b_short_des" class="form-control " required></textarea>

                        <div class="form-group">
                            <label  class="p-2">Description</label>

                            <div>
                                 <textarea   name="b_long_des" id="body" class="form-control" ></textarea>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="p-2">Image</label>
                            <input type="file" class="form-control" name="b_image"   required>
                        </div>

                        {{-- <div class="form-group">
                            <label class="p-2">Author</label>
                            <input type="text" class="form-control " name="b_author" required>
                        </div>
                        <div class="form-group">
                            <label class="p-2">Author-image</label>
                            <input type="file" class="form-control" name="b_author_image"   required>
                        </div> --}}
                        <div class="form-group">
                            <label class="p-2">status</label>
                            <select class="form-control pb-2"  placeholder="status" name="status" required>
                               <option value="1">Active</option>
                               <option value="0">Deactive</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label class="p-2">Blog-Date</label>
                            <input type="date" class="form-control" name="b_date"   required>
                        </div>
                          <div class="form-group p-2">
                           <input type="submit" class="btn btn-danger rounded">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" ></script>

@endsection





