@extends('backEnd.layouts.master')
@section('title', 'Order Report')
@section('content')
@section('css')
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <style>
        p {
            margin: 0;
        }

        @page {
            margin: 50px 0px 0px 0px;
        }

        @media print {
            td {
                font-size: 18px;
            }

            p {
                margin: 0;
            }

            title {
                font-size: 25px;
            }

            header,
            footer,
            .no-print,
            .left-side-menu,
            .navbar-custom {
                display: none !important;
            }
        }
    </style>
@endsection
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Order Report</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="no-print">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="keyword" class="form-label">Keyword</label>
                                    <input type="text" value="{{ request()->get('keyword') }}" class="form-control"
                                        name="keyword">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-3">
                                <div class="mb-3 form-group">
                                    <label for="filter" class="form-label">Filter Order/Sale</label>
                                    <select class="form-control select2 @error('filter') is-invalid @enderror"
                                        name="filter" value="{{ request()->get('filter') }}">
                                        <option value="">Select..</option>
                                        <option value="">Order</option>
                                            <!--<option value="qty"-->
                                            <!--    @if (request()->get('filter') == 'qty') selected @endif>Quantity-->
                                            <!--</option>-->
                                            <option value="sale"
                                                @if (request()->get('filter') == 'sale') selected @endif>Sale
                                            </option>
                                    </select>
                                    @error('filter')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="mb-3 form-group">
                                    <label for="filter_qty" class="form-label">Filter </label>
                                    <select class="form-control select2 @error('filter_qty') is-invalid @enderror"
                                        name="filter_qty" value="{{ request()->get('filter_qty') }}">
                                        <option value="">Select..</option>
                                            <option value="high"
                                                @if (request()->get('filter_qty') == 'high') selected @endif>High to Low QTY
                                            </option>
                                            <option value="low"
                                                @if (request()->get('filter_qty') == 'low') selected @endif>Low to High QTY
                                            </option>
                                    </select>
                                    @error('filter_qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" value="{{ request()->get('start_date') }}"
                                        class="form-control flatdate" name="start_date">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" value="{{ request()->get('end_date') }}"
                                        class="form-control flatdate" name="end_date">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-12">
                                <div class="mb-3 form-group">
                                    <button class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.order_report') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                            <!-- col end -->
                        </div>
                    </form>
                    <div class="mb-3 row">
                        <div class="col-sm-6 no-print">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                        <div class="col-sm-6">
                            <div class="export-print text-end">
                                <button onclick="printFunction()"class="no-print btn btn-success"><i
                                        class="fa fa-print"></i> Print</button>
                                <button id="export-excel-button" class="no-print btn btn-info"><i
                                        class="fas fa-file-export"></i> Export</button>
                            </div>
                        </div>
                    </div>
                    <div id="content-to-export">
                        <div class="table-responsive">
                            <table class="table nowrap w-100">
                                <thead>
                                    <tr>
                                        <!--<th style="width:5%">Order ID</th>-->
                                        <!--<th style="width:20%">Customer</th>-->
                                        <!--<th style="width:20%">Phone</th>-->
                                        <!--<th style="width:30%">Product</th>-->
                                        <!--<th style="width:10%">Purchase</th>-->
                                        <!--<th style="width:10%">Sale</th>-->
                                        <!--<th style="width:10%">Qty</th>-->
                                        <!--<th style="width:10%">Total</th>-->

                                        @if(request()->filter == 'sale')
                                        <th>SL</th>
                                        @else
                                        <th>Order ID</th>
                                        @endif
                                        <!--<th>Order Type</th>-->
                                        <th>Order Status</th>
                                        <th>Product Name</th>

                                        @if(request()->filter == 'sale')
                                        <th>Product ID</th>
                                        <th>SKU</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        @else
                                            <th>Product Code</th>
                                            <th>SKU</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total Amount</th>
                                            <th>Discount</th>
                                            <th>Delivery Charge</th>
                                            <th>COD Charge</th>
                                            <th>Grand Total</th>
                                            <th>Customer Name</th>
                                            <th>Customer Number</th>
                                            <!--<th>Customer Email</th>-->
                                            <th>Customer Address</th>
                                            <th>Payment Method</th>
                                            <th>Refund Amount</th>
                                            <th>Admin Note</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $total_purchase = 0;
                                        $total_qty = 0;
                                        $total_sale = 0;
                                        $single_sale = 0;
                                    @endphp
                                    @foreach ($orders as $key => $value)

                                        <tr>
                                            <td>{{ $value->order ? $value->order->invoice_id : $key + 1 }}</td>
                                            <!--<td>Online</td>-->
                                            <td>Order Delivered</td>
                                            @php
                                                $product = App\Models\Product::where('id', $value->product_id)->first();
                                                $product_color = App\Models\Productcolor::where('color', $value->product_color)->where('product_id', $value->product_id)->first();
                                                $product_size = App\Models\Productsize::where('size', $value->product_size)->where('product_id', $value->product_id)->where('color_id', $product_color->color_id)->first();
                                            @endphp
                                            @if(request()->filter == 'sale')
                                                <td>{{ $product?->name }}</td>
                                                <td>{{ $product?->product_code }}</td>
                                                <td>{{ $product_size?->sku }}</td>
                                                <td>{{ $value->product_color }}</td>
                                                <td>{{ $value->product_size }}</td>
                                                <td>{{ $value->total_sale }}</td>
                                                <td>{{ $value->total_amount }}</td>
                                            @else
                                                <td>{{ $value?->product_name }}</td>
                                                <td>{{ $product?->product_code ?? "" }}</td>
                                                <td>{{ $product_size?->sku }}</td>
                                                <td>{{ $value->qty }}</td>
                                                <td>{{ $value->sale_price }}</td>
                                                <!--<td>{{ $value->shipping ? $value->shipping->address : '' }}</td>-->
                                                <td>{{ $total_amount = $value->qty * $value->sale_price }}</td>
                                                @php
                                                    $shipping_charge = App\Models\Order::where(
                                                        'id',
                                                        $value->order_id,
                                                    )->first()->shipping_charge;
                                                    $order = App\Models\Order::where('id', $value->order_id)->first();
                                                @endphp
                                                <td>{{ $order->coupon_discount }}</td>
                                                <td>{{ $order->shipping_charge }}</td>
                                                <td>{{ number_format($order->cod_charge, 0) }}</td>
                                                <!--<td>{{ $total_amount + $shipping_charge - $order->discount }}</td>-->
                                                <td>{{ $order->amount }}</td>
                                                <td>{{ $value->shipping ? $value->shipping->name : '' }}</td>
                                                <td>{{ $value->shipping ? $value->shipping->phone : '' }}</td>
                                                <!--<td>{{ $value->shipping ? $value->shipping->email : '' }}</td>-->
                                                <td>{{ $value->shipping ? $value->shipping->address : '' }}</td>
                                                <td>{{ $value->order ? $value->order->order_type : '0' }}</td>
                                                <td>{{ $value->order ? $value->order->refund_paid_amount : '' }}</td>
                                                <td>{{ $value->order ? $value->order->refund_paid_amount : '' }}</td>
                                                <td>{{ $value->order ? $value->order->admin_note : '' }}</td>
                                            @endif



                                            <!--<td>{{ $value->shipping ? $value->shipping->name : '' }}</td>-->
                                            <!--<td>{{ $value->shipping ? $value->shipping->phone : '' }}</td>-->
                                            <!--<td>{{ $value->product_name }}</td>-->
                                            <!--<td>{{ $value->purchase_price }}</td>-->
                                            <!--<td>{{ $value->sale_price }}</td>-->
                                            <!--<td>{{ $value->qty }}</td>-->
                                            <!--<td>{{ $value->qty * $value->sale_price }}</td>-->
                                        </tr>
                                        @php
                                            $total_purchase += $value->qty * $value->purchase_price;
                                            $total_qty += $value->qty;
                                            $total_sale += $value->qty * $value->sale_price;
                                            $single_sale += $value->sale_price;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    {{-- <tr>
                                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                                        <td><strong>{{ $total_purchase }}</strong></td>
                                        <td><strong>{{ $single_sale }}</strong></td>
                                        <td><strong>{{ $total_qty }}</strong></td>
                                        <td><strong>{{ $total_sale }} </strong></td>
                                    </tr> --}}
                                    <!--<tr>-->
                                    <!--    <td colspan="8" class="text-center">-->
                                    <!--        <h5><strong>Total Purchase = {{ $totalPurchase }}</strong></h5>-->
                                    <!--        <h5><strong>Total Sales = {{ $total_sales - $discounts }}</strong></h5>-->
                                    <!--        <h5><strong>Total Profit = {{ $total_sales - $totalPurchase - $discounts }}</strong>-->
                                    <!--        </h5>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        flatpickr(".flatdate", {});
    });
</script>
<script>
    function printFunction() {
        window.print();
    }
</script>
<script>
    $(document).ready(function() {
        $('#export-excel-button').on('click', function() {
            var contentToExport = $('#content-to-export').html();
            var tempElement = $('<div>');
            tempElement.html(contentToExport);
            tempElement.find('.table').table2excel({
                exclude: ".no-export",
                name: "Order Report"
            });
        });
    });
</script>

@endsection
