@extends('backEnd.layouts.master')
@section('title','Banner Create')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />


@endsection
<style>
    .addbutton{
        display: flex;
    }
</style>

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Blog-Manager</h4>
                    <div class="row justify-content-between align-items-center">
                        <div class="col-sm-4">
                            <a href="{{route('blog')}}" class="btn btn-danger rounded-pill"><i class=""></i> Add Blog</a>
                        </div>

                        <div class="col-sm-4 text-right">
                            <form class="custom_form">
                                <div class="form-group d-flex">
                                    <input type="text" name="keyword" placeholder="Search" class="form-control">
                                    <button class="btn rounded-pill btn-info ml-2">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                   </div>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">id</th>
                            <th scope="col">title</th>
                            <th scope="col">image</th>
                            <th scope="col">status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($blog as $key => $showblog)
                          <tr>
                            <th scope="row">{{++$key}}</th>
                            <td>{{$showblog->b_title}}</td>

                            <td>
                                <img style="height:50px" src="{{ asset($showblog->b_image) }}" alt="">
                            </td>

                            <td>
                                @if($showblog->status == 1)
                                <a href="{{route('status',$showblog->id)}}" class="btn btn-success">Active</a>
                                @else
                                <a href="{{route('status', $showblog->id)}}" class="btn btn-danger">Inactive</a>

                                @endif


                            </td>
                            <td>
                                <a href="{{route('edit',$showblog->id)}}" class="btn btn-danger">Edit</a>
                                <a href="{{route('delete',$showblog->id)}}" class="btn btn-success">Delete</a>
                            </td>

                          </tr>
                         @endforeach

                         @if($blog->isNotEmpty())

                          @else
                         <tr>
                       <td colspan="12">
                    <div class="alert alert-danger text-center" role="alert">
                   No Blog found!
                  </div>
                </td>
                </tr>
                @endif
                        </tbody>
                      </table>


                    </div>
                      </div>
                       </div>
                         </div>



@endsection
