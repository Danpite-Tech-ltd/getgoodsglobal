@extends('backEnd.layouts.master')
@section('title','Ticket Manage')

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
                <div class="page-title-right">
                   
                </div>
                <h4 class="page-title">Ticket Manage</h4>
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
                            <th style="width:5%">SL</th>
                            <th style="width:10%">Order ID</th>
                            <th style="width:10%">Name</th>
                            <th style="width:15%">Email</th>
                            <th style="width:12%">Phone</th>
                            <th style="width:18%">Message</th>
                            <th style="width:10%">Date</th>
                            <th style="width:10%">Closed Date</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                
                
                    <tbody>
                        @foreach($show_data as $key=>$value)
                        @php 
                            $ticketdetails = $value->ticketdetailsmessage()->latest()->first();
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <a href="{{ url('admin/order/edit/' . $value->order_id) }}" style="color:black;">{{$value->order_id}}</a>
                            </td>
                            <td>{{$value->name}}</td>
                            <td>{{$value->email}}</td>
                            <td>{{$value->phone}}</td>
                            <td>{{ $ticketdetails->message ?? "" }}</td>
                            <td>
                                {{ $value->created_at->format('d M, Y') }}<br>
                                 {{ $value->created_at->format('h:i A') }}
                            </td>
                            <td>
                                @if($value->status == 0)
                                {{ $value->closed_date?->format('d M, Y') }}<br>
                                 {{ $value->closed_date?->format('h:i A') }}
                                @else
                                @endif
                            </td>
                            <td>
                                <div class="button-list">
                                    @if($value->status == 1)
                                        <form method="post" action="{{route('ticket.inactive')}}" class="d-inline"> 
                                        @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">       
                                            <button type="button" class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm" style="background: green;border: none;border-radius: 13px;">Open</i></button>
                                        </form>
                                    @else
                                        <form method="post" action="{{route('ticket.active')}}" class="d-inline">
                                            @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">        
                                            <button type="button" class="btn btn-xs  btn-success waves-effect waves-light change-confirm" style="background: red;border: none;border-radius: 13px;">Closed</i></button>
                                        </form>
                                    @endif
                                    <a href="{{route('ticket.edit',$value->ticket_id)}}" class="btn btn-xs btn-primary waves-effect waves-light"><i class="fe-edit-1"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
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