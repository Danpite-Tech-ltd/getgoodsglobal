@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout') @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
<style>
    .theme-btn-s2 {
        right: 0;
        top: 50%;
        transform: translateY(-50%);
    }

    #place_order_btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush @section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;

        $partial_amount = Cart::instance('shopping')->content();
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-5 cus-order-2">
                <div class="checkout-shipping">
                    @php
                        $partial_payment = 0;
                        foreach ($partial_amount as $amount) {
                            $partial_payment += $amount->options->preebooking;
                        }
                    @endphp

                    <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <input type="hidden" name="paid_partial_payment_amount" value="{{ $partial_payment }}">
                        <div class="card">
                            <div class="card-header">
                                <!--<h6>আপনার অর্ডারটি কনফার্ম করতে তথ্যগুলো পূরণ করে <span style="color:#fe5200;">"অর্ডার করুন"</span> বাটন এ ক্লিক করুন অথবা ফোনে অর্ডার করতে এই নাম্বার <a href="tel:{{ $contact->hotline }}">{{ $contact->hotline }}</a> এর উপরে ক্লিক করুন।   </h6>-->
                                <input type="hidden" id="hidden_couponId" name="couponId" value="">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="name">Name *</label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="@if ($customer) {{ $customer->name }} @endif"
                                                required />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="phone">Phone *</label>
                                            <input type="text" minlength="11" id="number" maxlength="11"
                                                pattern="0[0-9]+"
                                                title="please enter number only and 0 must first character"
                                                title="Please enter an 11-digit number." id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="@if ($customer) {{ $customer->phone }} @endif"
                                                required />
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">City *</label>
                                            <select class="form-select form-select-lg city" name="city"
                                                id="">
                                                <option value="" disabled selected>Select your City</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city }}">{{ $city }}</option>
                                                @endforeach
                                            </select>
                                            @error('city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="address">Address * (District, Thana, Village )</label>
                                            <input type="address" id="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address"
                                                value="@if ($customer) {{ $customer->address }} @endif"
                                                required />
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="area">Select Your Area *</label>
                                            <select type="area" id="area" class="form-select"
                                                @error('area') is-invalid @enderror" name="area" required>
                                                <option value="">Select Shipping Option</option>
                                                @foreach ($shippingcharge as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="note">Note (optional)</label>
                                            <input type="note" id="note"
                                                class="form-control @error('note') is-invalid @enderror" name="note"
                                                value="{{ old('note') }}" />
                                        </div>
                                    </div>

                                    <!-- col-end -->
                                    <div class="col-sm-12">

                                        <div class="radio_payment">
                                            <label id="payment_method">Payment Getway</label>
                                            <div class="payment_option">

                                            </div>
                                        </div>
                                        <div class="payment-methods">
                                            @if (!$partial_payment || $partial_payment == 0)
                                                <div class="form-check p_cash">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio1" value="Cash On Delivery" checked required />
                                                    <label class="form-check-label" for="inlineRadio1">
                                                        Cash On Delivery
                                                    </label>
                                                </div>
                                            @endif
                                            @if ($bkash_gateway)
                                                <div class="form-check p_bkash">
                                                    <input class="form-check-input" type="radio"
                                                        name="payment_method" id="inlineRadio2" value="bkash"
                                                        @if ($partial_payment) checked @endif required />
                                                    <label class="form-check-label" for="inlineRadio2">
                                                        Bkash
                                                    </label>
                                                </div>
                                            @endif

                                            @if ($shurjopay_gateway)
                                                <div class="form-check p_shurjo">
                                                    <input class="form-check-input" type="radio"
                                                        name="payment_method" id="inlineRadio3" value="shurjopay"
                                                        required />
                                                    <label class="form-check-label" for="inlineRadio3">
                                                        Shurjopay
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-------------------->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="order_place" id="Checkout_orderPlace" type="submit">Place
                                                Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card end -->
                    </form>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-7 cust-order-1">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>ORDER INFORMATION</h5>
                        </div>
                        <div class="card-body cartlist cart_area">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">DELETE</th>
                                        <th style="width: 40%;">PRODUCT</th>
                                        <th style="width: 20%;">QTY</th>
                                        <th style="width: 20%;">PRICE</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                        class="fas fa-trash text-danger"></i></a>
                                            </td>
                                            <td class="text-left">
                                                <a href="{{ route('product', $value->options->slug) }}"> <img
                                                        src="{{ asset($value->options->image) }}" />
                                                    {{ Str::limit($value->name, 20) }}</a>
                                                @if ($value->options->product_size)
                                                    <p>Size: {{ $value->options->product_size }}</p>
                                                @endif
                                                @if ($value->options->product_color)
                                                    <p>Color: {{ $value->options->product_color }}</p>
                                                @endif
                                            </td>
                                            @php
                                                $single_product = App\Models\Product::find($value->id);
                                                $single_stock_quantity = $single_product->stock;
                                                $variant_stock_quantity = App\Models\Productsize::where(
                                                    'product_id',
                                                    $value->id,
                                                )
                                                    ->where('size', $value->options->product_size)
                                                    ->sum('quantity');
                                            @endphp
                                            <td class="cart_qty">
                                                <div class="qty-cart vcart-qty">
                                                    <div class="quantity">
                                                        <button class="minus cart_decrement"
                                                            data-id="{{ $value->rowId }}">-</button>
                                                        <input type="text" value="{{ $value->qty }}" readonly />
                                                        <button class="plus cart_increment"
                                                            data-id="{{ $value->rowId }}">+</button>
                                                    </div>
                                                </div>
                                                <br>
                                                @if ($single_product->type == 1)
                                                    @if ($variant_stock_quantity < $value->qty)
                                                        <span class="bg-danger text-white p-2">No Stock </span>
                                                    @endif
                                                @else
                                                    @if ($single_stock_quantity < $value->qty)
                                                        <span class="bg-danger text-white p-2">No Stock </span>
                                                    @endif
                                                @endif
                                            </td>
                                            
                                            <td><span class="alinur">৳ </span><strong>{{ $value->price }} </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">SUB TOTAL</th>
                                        <td class="px-4">
                                            <span id="net_total"><span class="alinur">৳
                                                </span><strong>{{ $subtotal }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">DISCOUNT</th>
                                        <td class="px-4">
                                            <span id="discount_amount"><span class="alinur">৳
                                                </span><strong id="discount">00</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">DELIVERY CHARGE</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span class="alinur">৳
                                                </span><strong>{{ $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">TOTAL</th>
                                        <td class="px-4">
                                            <span id="grand_total"><span class="alinur">৳
                                                </span><strong>{{ $subtotal + $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <div class="apply-area position-relative mb-2">
                            <input type="text" id="coupon" name="coupon" class="form-control border"
                                placeholder="Enter your coupon">
                            <input type="hidden" id="hidden_coupon" value="00">
                            <button class="theme-btn-s2 position-absolute btn btn-dark" id="applyCoupon"
                                type="button">Submit</button>
                        </div>
                        <strong id="error"></strong>
                    </div>
                </div>
                @if ($partial_payment)
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="apply-area position-relative mb-2">
                                <p>Note: For now you will pay {{ $partial_payment }} TK for partial payment and pay
                                    more {{ $subtotal - $partial_payment }} TK + Shipping Charge after recieving
                                    the product.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- col end -->
            </div>
        </div>
</section>
@endsection @push('script')
<script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
<script>
    $('#applyCoupon').on('click', function() {
        var couponCode = $('#coupon').val();

        if (!couponCode) {
            $('#error').html('Please enter a coupon code!');
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('coupon.apply') }}',
            type: 'POST',
            data: {
                coupon: couponCode,
                amount: Number("<?php echo $subtotal; ?>"),
            },
            success: function(response) {
                var shipping = {{ $shipping }};
                if (response.success == true) {
                    $('#discount').html(response.discount);
                    $('#hidden_coupon').val(response.discount);
                    $('#hidden_couponId').val(response.id);
                    $('#grand_total > strong').html(parseInt(response.amount) + parseInt(shipping));
                    c
                    $('#error').html(response.message).css({
                        'color': 'green',
                    });
                } else {
                    $('#error').html(response.message).css({
                        'color': 'red',
                    }); // Clear error message if there's none
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                $('#error').html(error);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
<script>
    $("#area").on("change", function() {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            data: {
                id: id,
                discount: $('#hidden_coupon').val(),
            },
            url: "{{ route('shipping.charge') }}",
            dataType: "html",
            success: function(response) {
                $(".cartlist").html(response);
            },
        });
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({
        ecommerce: null
    });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "view_cart",
        ecommerce: {
            currency: "BDT",
            value: Number("<?php echo $subtotal; ?>"),
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: Number("<?php echo $cartInfo->id; ?>"),
                        price: Number("<?php echo $cartInfo->price; ?>"),
                        item_brand: "{{ $cartInfo->options->brands }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({
        ecommerce: null
    });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            currency: "BDT",
            value: Number("<?php echo $subtotal; ?>"),
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: Number("<?php echo $cartInfo->id; ?>"),
                        price: Number("<?php echo $cartInfo->price; ?>"),
                        item_brand: "{{ $cartInfo->options->brands }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script>
    $(document).ready(function() {
        if ($('.cart_qty .bg-danger').length > 0) {
            $('.order_place').prop('disabled', true);
        }
    });
</script>
@endpush
