@extends('backEnd.layouts.master')
@section('title','Contact Manage')

@section('css')
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">               
                <h4 class="page-title">Coupon List</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle w-100" style="table-layout:fixed;">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Coupon Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Available</th>
                            <th>Validity</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                
                
                    <tbody>
                        @foreach ($coupons as $index => $coupon)
                            <!--<tr>-->
                            <!--    <th>{{ $index + 1 }}</th>-->
                            <!--    <td>{{ $coupon->coupon_name }}</td>-->
                            <!--    <td>{{ $coupon->coupon_type == 1 ? 'Solid' : 'Percentage' }}</td>-->
                            <!--    <td>{{ $coupon->amount }} {{ $coupon->coupon_type == 1 ? '৳' : '%' }}</td>-->
                            <!--    <td class="text-center">{{ $coupon->count == null ? '0' : $coupon->count }}</td>-->
                            <!--    <td>-->
                            <!--        @if (carbon\Carbon::now() < $coupon->validity)-->
                            <!--        <button type="button" class="btn btn-success btn-sm">-->
                            <!--            {{ carbon\Carbon::now()->diffInDays($coupon->validity) }} days remaining-->
                            <!--        </button>                                    -->
                            <!--        @else-->
                            <!--        <button type="button" class="btn btn-warning btn-sm">-->
                            <!--        Expired {{ carbon\Carbon::now()->diffInDays($coupon->validity) }}  days ago-->
                            <!--        </button>-->
                            <!--        @endif                                -->
                            <!--    </td>-->
                            <!--    <td>-->
                            <!--        <a href="{{ route('delete.coupon' , $coupon->id) }}">-->
                            <!--            <button type="button" class="btn btn-danger btn-icon btn-xs">-->
                            <!--                <i width="18px" height="18px" data-feather="trash"></i>-->
                            <!--            </button>-->
                            <!--        </a>-->
                            <!--    </td>-->
                            <!--</tr>-->
                                <tr>
                            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                            
                                    <td>{{ $index + 1 }}</td>
                            
                                    <td>
                                        <input type="text" name="coupon_name" class="form-control form-control-sm"
                                               value="{{ $coupon->coupon_name }}">
                                    </td>
                            
                                    <td>
                                        <select name="coupon_type" class="form-control form-control-sm">
                                            <option value="1" {{ $coupon->coupon_type == 1 ? 'selected' : '' }}>Solid</option>
                                            <option value="2" {{ $coupon->coupon_type == 2 ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </td>
                            
                                    <td>
                                        <input type="number" name="amount" class="form-control form-control-sm"
                                               value="{{ $coupon->amount }}">
                                    </td>
                                    <td>
                                        <input type="number" name="count" class="form-control form-control-sm"
                                               value="{{ $coupon->count ?? 0 }}">
                                    </td>
                            
                                    <!--<td class="text-center">-->
                                    <!--    {{ $coupon->count ?? 0 }}-->
                                    <!--</td>-->
                            
                                    <td>
                                        @if (carbon\Carbon::now() < $coupon->validity)
                                            <button type="button" class="btn btn-success btn-sm">
                                                {{ carbon\Carbon::now()->diffInDays($coupon->validity) }} days remaining
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning btn-sm">
                                                Expired {{ carbon\Carbon::now()->diffInDays($coupon->validity) }} days ago
                                            </button>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <input type="date" class="form-control" value="{{ $coupon->validity }}" id="validity" name="validity">
                                    </td>
                                    
                            
                                    <td>
                                        <a href="{{ route('delete.coupon',$coupon->id) }}" class="btn btn-danger btn-xs" style="padding:2px 5px;margin-right:10px;">
                                            <i data-feather="trash"></i>
                                        </a>
                                        
                                        
                            
                                        <button type="submit" class="btn btn-success btn-xs" style="padding:2px 5px;">
                                            <i data-feather="save"></i>
                                        </button>
                                    </td>
                            
                            </form>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create Coupons</h5>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('coupon.store') }}">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="name">Coupon name</label>
                        <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Coupon name .. " name="coupon_name">
                    </div>
                    <div class="form-group">
                        <label>Coupon type</label>
                        <select class="form-control form-control-sm mb-2" name="coupon_type">
                            <option selected disabled>Select coupon types</option>
                            <option value="1">Solid</option>
                            <option value="2">Percentage</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" autocomplete="off" placeholder="Amount" name="amount">
                    </div>
                    <div class="form-group mb-2">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" autocomplete="off" placeholder="quantity" name="quantity">
                    </div>
                    <div class="form-group mb-2">
                        <label for="validity">Validity</label>
                        <input type="date" class="form-control" id="validity" name="validity">
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Insert</button>
                </form>
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
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
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