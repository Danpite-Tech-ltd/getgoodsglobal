@extends('backEnd.layouts.master')
@section('title', 'Bulk Quantity Manage')
@section('css')
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            {{-- <div class="page-title-right">
                                <a href="" class="btn btn-primary rounded-pill"></a>
                            </div> --}}
                            <h4 class="page-title">Bulk Quantity Manage</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class=" card-body">
                                @if (session('delete'))
                                    <div class="alert bg-danger text-white">{{ session('delete') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="alert bg-danger text-white">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if(session('success'))
                                    <div class="alert bg-success text-white">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle w-100" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">SL</th>
                                                <th style="width:20%">Product Name</th>
                                                <th style="width:20%">Title</th>
                                                <th style="width:10%">Min Qty</th>
                                                <th style="width:10%">Max Qty</th>
                                                <th style="width:15%">Price</th>
                                                <th style="width:10%">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        @foreach($bulk_qty as $value)
                                        <form action="{{ route('productbulkquantity.update', $value->id) }}" method="POST">
                                            @csrf
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                
                                                <td>{{$product->name}}</td>
                                                
                                                <td>
                                                <input class="form-control form-control-sm" value="{{$value->title}}" name="title">
                                                </td>
                                                
                                                <td>
                                                <input class="form-control form-control-sm" value="{{$value->min_qty}}" name="min_qty">
                                                </td>
                                                
                                                <td>
                                                <input class="form-control form-control-sm" value="{{$value->max_qty}}" name="max_qty">
                                                </td>
                                                
                                                <td>
                                                <input class="form-control form-control-sm" value="{{$value->price}}" name="price">
                                                </td>
                                                
                                                <td>
                                                    <a href="{{ route('productbulkquantity.destroy',$value->id) }}" class="btn btn-sm bg-danger text-white">
                                                        <i class="fe-trash-2"></i>
                                                    </a>
                                                    
                                                    <button type="submit" class="btn btn-sm bg-success text-white">
                                                        <i class="fe-save"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </form>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    
                                    </div>


                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-12">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h4>Create</h4>
                            </div>
                            <div class="card-body">
                                
                                <form action="{{ route('productbulkquantity.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Minimum Quantity</label>
                                        <input type="number" class="form-control" name="min_qty">
                                        @error('min_qty')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Maximum Quantity</label>
                                        <input type="number" class="form-control" name="max_qty">
                                        @error('max_qty')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn bg-success text-white">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <!-- third party js -->
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/js/pages/datatables.init.js"></script>
    <!-- third party js ends -->
@endsection
