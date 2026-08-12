@extends('backEnd.layouts.master')
@section('title', 'Stock Report')
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
                <h4 class="page-title">Stock Report</h4>
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="keyword" class="form-label">Keyword</label>
                                    <input type="text" value="{{ request()->get('keyword') }}" class="form-control"
                                        name="keyword">
                                </div>
                            </div>
                            <!--col-sm-3-->
                            <div class="col-sm-2">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Categories </label>
                                    <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                        name="category_id" value="{{ old('category_id') }}">
                                        <option value="">Select..</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if (request()->get('category_id') == $category->id) selected @endif>{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
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

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">Stock In/Out</label>
                                    <select class="form-control"
                                        name="stock_filter" value="{{ request()->get('stock_filter') }}">
                                        <option value="">Select..</option>
                                        <option value="1" {{ request()->get('stock_filter') == '1' ? 'selected' : '' }}>Stock In</option>
                                        <option value="0" {{ request()->get('stock_filter') == '0' ? 'selected' : '' }}>Stock Out</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="filter" class="form-label">Stock Filter</label>
                                    <select class="form-control"
                                        name="filter" value="{{ request()->get('filter') }}">
                                        <option value="">Select..</option>
                                        <option value="high" {{ request()->get('filter') == 'high' ? 'selected' : '' }}>High to Low Stock</option>
                                        <option value="low" {{ request()->get('filter') == 'low' ? 'selected' : '' }}>Low to High Stock</option>
                                    </select>
                                </div>
                            </div>

                            <!--col-sm-3-->
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!-- col end -->
                        </div>
                    </form>
                    <div class="row mb-3">
                        <div class="col-sm-6 no-print">
                            {{ $sizes->links('pagination::bootstrap-4') }}
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
                    <div id="content-to-export" class="table-responsive">
                        <table class="table nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>SKU</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Total Stock</th>
                                    <th>Current Stock</th>
                                    <th>Sale Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $stock = 0; $total = 0;@endphp
                                @foreach ($sizes as $key => $size)
                                    @php
                                        $colorName = App\Models\Color::find($size->color_id)->colorName;
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $size->product->name ?? '' }}</td>
                                        <td>{{ $size->product->product_code ?? '' }}</td>
                                        <td>{{ $size->sku ?? '' }}</td>
                                        <td>{{ $colorName ?? '' }}</td>
                                        <td>{{ $size->size }}</td>
                                        <td>{{ $size->product->category->name ?? '' }}</td>
                                        <td>{{ $size->product->brand->name ?? 'No Brand' }}</td>
                                        <td>{{ $size->total_stock }}</td>
                                        <td>{{ $size->stock }}</td>
                                        <td>{{ $size->SalePrice }}</td>
                                        <td>{{ $size->stock * $size->SalePrice }}</td>
                                        <td>{{
                                            ($size->stock > 5) ? "Available" :
                                            (($size->stock >= 1 && $size->stock <= 5) ? "Low Stock" : "No Stock")
                                        }}</td>
                                    </tr>
                                    @php
                                        $stock += $size->stock;
                                        $total += $size->stock * $size->SalePrice;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total</strong></td>
                                    <td><strong>{{ $stock }} Pcs</strong></td>
                                    <td><strong>{{ $total }} Tk</strong></td>
                                </tr>
                                <tr>

                                    <td colspan="8" class="text-center">
                                        @php
                                            $product_sizes = App\Models\Productsize::get();
                                        @endphp
                                        <!-- <h5><strong>Total Stock = {{ $product_sizes->sum('total_stock') }} Tk</strong></h5>
                                        <h5><strong>Total Current Stock = {{ $product_sizes->sum('stock') }} Pcs</strong></h5> -->
                                        <!-- <h5><strong>Total Sale Price = {{ $product_sizes->sum('SalePrice') * $product_sizes->sum('stock') }} Tk</strong></h5> -->
                                    </td>
                                    {{-- <td colspan="8" class="text-center">
                                        <h5><strong>Total Purchase = {{ $total_purchase }}</strong></h5>
                                        <h5><strong>Total Stock = {{ $total_stock }} Pcs</strong></h5>
                                        <h5><strong>Total Price = {{ $total_price }} Tk</strong></h5>
                                    </td> --}}
                                </tr>
                            </tfoot>
                        </table>
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
