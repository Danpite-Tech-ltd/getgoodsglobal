@extends('backEnd.layouts.master')
@section('title','Order Edit')
@section('css')
<style>
    .cart-table th, .cart-table td {
        vertical-align: middle !important;
    }
    .qty-cart {
        width: 105px;
        margin: 0 auto;
    }
    .qty-cart .quantity {
        position: relative;
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 32px;
        overflow: hidden;
        background: #fff;
    }
    .qty-cart .quantity input {
        width: 100% !important;
        height: 32px !important;
        text-align: center;
        border: none !important;
        padding: 0 32px !important;
        font-weight: 600;
        font-size: 14px;
        background: transparent;
        line-height: 32px;
    }
    .qty-cart .quantity .minus,
    .qty-cart .quantity .plus {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 30px;
        height: 32px;
        line-height: 28px;
        background: #f1f3f5;
        border: 0;
        color: #333;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background 0.2s;
        z-index: 10;
    }
    .qty-cart .quantity .minus:hover,
    .qty-cart .quantity .plus:hover {
        background: #e2e6ea;
    }
    .qty-cart .quantity .minus {
        left: 0;
        border-right: 1px solid #ccc;
    }
    .qty-cart .quantity .plus {
        right: 0;
        border-left: 1px solid #ccc;
    }
    .cart-table select.form-control {
        font-size: 12px;
        padding: 4px 6px;
        height: 32px;
    }
</style>
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd')}}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <!--<div class="row">-->
    <!--    <div class="col-12">-->
    <!--        <div class="page-title-box">-->
    <!--            <div class="page-title-right">-->
    <!--                <form method="post" action="{{route('admin.order.cart_clear')}}" class="d-inline">-->
    <!--                    @csrf-->
    <!--                <button type="submit" class="btn btn-danger rounded-pill delete-confirm" title="Delete"><i class="fas fa-trash-alt"></i> Cart Clear</button></form>-->
    <!--            </div>-->
    <!--            <h4 class="page-title">Order Create</h4>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- end page title -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.order.update')}}" method="POST" class="row pos_form" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$order->id}}" name="order_id">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="product_id" class="form-label">Products *</label>
                                <select id="cart_add" class="form-control select2 @error('product_id') is-invalid @enderror"  value="{{ old('product_id') }}" >
                                    <option value="">Select..</option>
                                    @foreach($products as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-12">
                          <table class="table table-bordered table-responsive-sm cart-table">
                            <thead>
                              <tr>
                                <th style="width:7%; text-align: center;">Image</th>
                                <th style="width:25%;">Name</th>
                                <th style="width:16%;">Color</th>
                                <th style="width:16%;">Size</th>
                                <th style="width:120px; text-align: center;">Quantity</th>
                                <th style="width:12%; text-align: center;">Sell Price</th>
                                <th style="width:12%; text-align: center;">Sub Total</th>
                                <th style="width:6%; text-align: center;">Action</th>
                              </tr>
                            </thead>
                            <tbody id="cartTable">
                              @include('backEnd.order.cart_content')
                            </tbody>
                          </table>
                        </div>
                        <!-- custome address -->
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group mb-2">
                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Customer Name" name="name" value="{{$shippinginfo->name}}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group mb-2">
                                        <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Customer Number" name="phone" value="{{$shippinginfo->phone}}"  required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- col-end -->
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="address" placeholder="Address" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{$shippinginfo->address}}"  required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <select type="area" id="area" class="form-control @error('area') is-invalid @enderror" name="area"   required>
                                            <option value="">Delivery Area</option>
                                            @foreach($shippingcharge as $key=>$value)
                                            <option value="{{$value->id}}" @if($shippinginfo->area == $value->name) selected @endif>{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="text" placeholder="admin_note" id="admin_note" class="form-control @error('admin_note') is-invalid @enderror" name="admin_note" value="{{$order->admin_note}}" >
                                        @error('admin_note')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="integer" placeholder="Refund paid amount" id="refund_paid_amount" class="form-control @error('refund_paid_amount') is-invalid @enderror" name="refund_paid_amount" value="{{$order->refund_paid_amount}}" >
                                        @error('refund_paid_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <input 
    type="number" 
    step="0.01"
    placeholder="weight" 
    id="weight" 
    class="form-control @error('weight') is-invalid @enderror" 
    name="weight" 
    value="{{ old('weight', is_numeric($order->weight) ? $order->weight : '') }}"
>

                                        @error('weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- col-end -->
                            </div>
                        </div>
                        <!-- cart total -->
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <tbody id="cart_details">
                                     @php
                                        $subtotal = Cart::instance('pos_shopping')->subtotal();
                                        $subtotal = str_replace(',','',$subtotal);
                                        $subtotal = str_replace('.00', '',$subtotal);
                                        $shipping = Session::get('pos_shipping') ?? 0;
                                    @endphp
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>{{$subtotal}}</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping Fee</td>
                                        <td>{{$shipping}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{(float)$subtotal + (float)$shipping}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <input type="submit" class="btn btn-success" value="Update Order" />
                        </div>
                    </form>
                </div>
                <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
        <!-- end col-->
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
    $(".summernote").summernote({
        placeholder: "Enter Your Text Here",
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
    });

    function cart_content(){
        $.ajax({
            type: "GET",
            url: "{{route('admin.order.cart_content')}}",
            dataType: "html",
            success: function(cartinfo){
                $('#cartTable').html(cartinfo);
            }
        });
    }

    function cart_details(){
        $.ajax({
            type: "GET",
            url: "{{route('admin.order.cart_details')}}",
            dataType: "html",
            success: function(cartinfo){
                $('#cart_details').html(cartinfo);
            }
        });
    }

    // Add product to cart
    $('#cart_add').on('change', function(e) {
        var id = $(this).val();
        if (!id) return;
        $.ajax({
            type: "GET",
            data: { id: id },
            url: "{{route('admin.order.cart_add')}}",
            dataType: "json",
            success: function(response) {
                cart_content();
                cart_details();
                $('#cart_add').val('').trigger('change.select2');
            }
        });
    });

    // Helper to update variant on server
    function updateCartVariant(rowId, colorId, colorName, colorImage, sizeName, sizePrice, purchasePrice) {
        var data = { rowId: rowId };
        if (colorId !== null) data.color_id = colorId;
        if (colorName !== null) data.color_name = colorName;
        if (colorImage !== null) data.color_image = colorImage;
        if (sizeName !== null) data.size = sizeName;
        if (sizePrice !== null) data.size_price = sizePrice;
        if (purchasePrice !== null) data.purchase_price = purchasePrice;

        $.ajax({
            type: "GET",
            data: data,
            url: "{{route('admin.order.cart_update_variant')}}",
            dataType: "json",
            success: function(res) {
                cart_content();
                cart_details();
            }
        });
    }

    // When Color changes in a table row
    $(document).on('change', '.cart_color', function(e) {
        var row = $(this).closest('tr');
        var rowId = $(this).data('rowid');
        var colorId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var colorName = selectedOption.data('color-name') || '';
        var colorImage = selectedOption.data('color-image') || '';

        var variantsJson = row.find('.row_variants_json').val();
        var variants = {};
        try {
            variants = variantsJson ? JSON.parse(variantsJson) : {};
        } catch(e) {
            variants = {};
        }

        // Update hidden color_name and color_image inputs so form submission is correct
        row.find('.input_color_name').val(colorName);
        row.find('.input_color_image').val(colorImage);

        var sizeSelect = row.find('.cart_size');
        sizeSelect.empty().append('<option value="">Select Size</option>');

        var sizes = [];
        if (variants[colorId] && variants[colorId].sizes) {
            sizes = variants[colorId].sizes;
        }

        if (sizes.length > 0) {
            $.each(sizes, function(i, sz) {
                sizeSelect.append('<option value="' + sz.size + '" data-price="' + sz.price + '" data-purchase-price="' + sz.purchase_price + '">' + sz.size + ' (৳' + sz.price + ')</option>');
            });
            // Auto-select first size for this color
            sizeSelect.val(sizes[0].size);
            var sizeName = sizes[0].size;
            var sizePrice = sizes[0].price;
            var purchasePrice = sizes[0].purchase_price;

            // Update row image if color image exists
            if (colorImage) {
                var assetBase = "{{asset('')}}";
                row.find('.row_img').attr('src', assetBase + colorImage);
            }

            updateCartVariant(rowId, colorId, colorName, colorImage, sizeName, sizePrice, purchasePrice);
        } else {
            updateCartVariant(rowId, colorId, colorName, colorImage, '', 0, 0);
        }
    });

    // When Size changes in a table row
    $(document).on('change', '.cart_size', function(e) {
        var rowId = $(this).data('rowid');
        var sizeName = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var sizePrice = selectedOption.data('price') || 0;
        var purchasePrice = selectedOption.data('purchase-price') || 0;

        updateCartVariant(rowId, null, null, null, sizeName, sizePrice, purchasePrice);
    });

    // Quantity Increment
    $(document).on('click', '.cart_increment', function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var qty = $(this).val();
        if(id){
            $.ajax({
                cache: false,
                data: { 'id': id, 'qty': qty },
                type: "GET",
                url: "{{route('admin.order.cart_increment')}}",
                dataType: "json",
                success: function(response){
                    if (response && response.status === 'out_of_stock') {
                        showStockAlert('পর্যাপ্ত Stock নেই! Available: ' + response.stock + ' টি');
                        return;
                    }
                    if (response && response.status === 'error') {
                        showStockAlert(response.message);
                        return;
                    }
                    cart_content();
                    cart_details();
                },
                error: function(xhr){
                    var res = xhr.responseJSON;
                    if (res && res.status === 'out_of_stock') {
                        showStockAlert('পর্যাপ্ত Stock নেই! Available: ' + res.stock + ' টি');
                    }
                }
            });
        }
    });

    function showStockAlert(msg) {
        // Remove old alert if any
        $('#stock-alert-box').remove();
        var html = '<div id="stock-alert-box" class="alert alert-danger alert-dismissible fade show" role="alert" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;">'
            + '<strong><i class="fa fa-exclamation-triangle"></i> Stock সীমা!</strong> ' + msg
            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
            + '</div>';
        $('body').append(html);
        setTimeout(function(){ $('#stock-alert-box').fadeOut(400, function(){ $(this).remove(); }); }, 4000);
    }

    // Quantity Decrement
    $(document).on('click', '.cart_decrement', function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var qty = $(this).val();
        if(id){
            $.ajax({
                cache: false,
                type: "GET",
                data: { 'id': id, 'qty': qty },
                url: "{{route('admin.order.cart_decrement')}}",
                dataType: "json",
                success: function(cartinfo){
                    cart_content();
                    cart_details();
                }
            });
        }
    });

    // Cart Remove
    $(document).on('click', '.cart_remove', function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        if(id){
            $.ajax({
                cache: false,
                type: "GET",
                data: { 'id': id },
                url: "{{route('admin.order.cart_remove')}}",
                dataType: "json",
                success: function(cartinfo){
                    cart_content();
                    cart_details();
                }
            });
        }
    });

    // Cart Clear
    $(document).on('click', '.cartclear', function(e){
        $.ajax({
            cache: false,
            type: "GET",
            url: "{{route('admin.order.cart_clear')}}",
            dataType: "json",
            success: function(cartinfo){
                cart_content();
                cart_details();
            }
        });
    });

    // Shipping Area
    $("#area").on("change", function () {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            data: { id: id },
            url: "{{route('admin.order.cart_shipping')}}",
            dataType: "html",
            success: function(cartinfo){
                cart_content();
                cart_details();
            }
        });
    });
</script>
@endsection
