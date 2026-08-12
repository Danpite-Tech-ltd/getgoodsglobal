@extends('frontEnd.layouts.master')
@section('title', $details->name)
@push('seo')
    <meta name="app-url" content="{{ route('product', $details->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $details->meta_description }}" />
    <meta name="keywords" content="{{ $details->slug }}" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{ $details->name }}" />
    <meta name="twitter:title" content="{{ $details->name }}" />
    <meta name="twitter:description" content="{{ $details->meta_description }}" />
    <meta name="twitter:creator" content="gomobd.com" />
    <meta property="og:url" content="{{ route('product', $details->slug) }}" />
    <meta name="twitter:image" content="{{ asset($details->image->image) }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $details->name }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $details->slug) }}" />
    <meta property="og:image" content="{{ asset($details->image->image) }}" />
    <meta property="og:description" content="{{ $details->meta_description }}" />
    <meta property="og:site_name" content="{{ $details->name }}" />
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/zoomsl.css') }}">
    <style>
        @media only screen and (min-width: 320px) and (max-width: 720px) {
            .prdetail {
                margin-top: 30px;
            }

            .modal-content {
                left: -50% !important;
                width: 85% !important;
            }

            .modal.show .modal-dialog {
                left: 56% !important;
            }
        }

        .modal.show .modal-dialog {
            top: 30% !important;
        }

        .modal-content {
            left: -50% !important;
        }
    </style>
@endpush

@section('content')
    <div class="homeproduct main-details-page prdetail">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <section class="product-section">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6 position-relative">
                                    @if ($details->old_price)
                                        <div class="product-details-discount-badge">
                                            <div class="sale-badge">
                                                <div class="sale-badge-inner">
                                                    <div class="sale-badge-box">
                                                        <span class="sale-badge-text">
                                                            <p> @php $discount=(((($details->old_price)-($details->new_price))*100) / ($details->old_price)) @endphp {{ number_format($discount, 0) }}% OFF
                                                            </p>

                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="details_slider owl-carousel">
                                        @if ($productcolors->count() > 0)
                                            @foreach ($productcolors as $proc)
                                                <div class="dimage_item">
                                                    <img src="{{ asset($proc->Image) }}" class="block__pic" />
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($details->images as $value)
                                                <div class="dimage_item">
                                                    <img src="{{ asset($value->image) }}" class="block__pic" />
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (isset($details->PostImage))
                                            @foreach (json_decode($details->PostImage) as $key => $image)
                                                <div class="dimage_item">
                                                    <img src="{{ asset('public/images/product/slider') }}/{{ $image }}"
                                                        class="block__pic" />
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div
                                        class="indicator_thumb @if ($details->images->count() > 4) thumb_slider owl-carousel @endif">
                                        @php
                                            $keyidone = 0;
                                            $keyid = 0;
                                        @endphp

                                        @if ($productcolors->count() > 0)
                                            @foreach ($productcolors as $key => $procs)
                                                <div class="indicator-item" id="{{ ++$keyidone }}"
                                                    data-id="{{ $key }}">
                                                    <img src="{{ asset($procs->Image) }}" />
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($details->images as $key => $image)
                                                @php
                                                    $keyid = $key + $keyidone + 1;
                                                @endphp
                                                <div class="indicator-item" id="{{ $keyid }}"
                                                    data-id="{{ $key + $keyidone + 1 }}">
                                                    <img src="{{ asset($image->image) }}" />
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (isset($details->PostImage))
                                            @foreach (json_decode($details->PostImage) as $key => $image)
                                                <div class="indicator-item" data-id="{{ $key + $keyid + 1 }}">
                                                    <img
                                                        src="{{ asset('public/images/product/slider') }}/{{ $image }}" />
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="details_right">


                                        <div class="product">
                                            <div class="product-cart">
                                                <p class="name">{{ $details->name }}</p>
                                                <p class="details-price">
                                                    @if ($productsizes->count() > 0)
                                                        <del>৳<span
                                                                id="delp">{{ $productsizes[0]->RegularPrice }}</span></del>
                                                        ৳<span id="regp">{{ $productsizes[0]->SalePrice }}</span>
                                                    @else
                                                        @if ($details->old_price)
                                                            <del>৳<span
                                                                    id="delp">{{ $details->old_price }}</span></del>
                                                        @endif ৳<span
                                                            id="regp">{{ $details->new_price }}</span>
                                                    @endif

                                                </p>
                                                <p class="text-danger details-price product_stock_out d-none">Stock Out</p>
                                                @if (isset($details->short_des))
                                                    <div>
                                                        {!! $details->short_des !!}
                                                    </div>
                                                @endif




                                                <div class="pro_brand mt-1">
                                                    <p>SKU :
                                                        {{ $details->product_code }}
                                                    </p>
                                                </div>

                                                <form action="{{ route('cart.store') }}" method="POST" id="addcart"
                                                    name="formName">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $details->id }}" />
                                                    @if ($productcolors->count() > 0)
                                                        <div class="pro-color d-flex" style="width: 100%;">
                                                            <h6><b>Choose Color :</b></h6>
                                                            <div class="color_inner mx-4">
                                                                <div class="size-container">
                                                                    <div class="selector">
                                                                        @foreach ($productcolors as $key => $procolor)
                                                                            <div class="selector-item">
                                                                                <input type="radio"
                                                                                    id="fc-option{{ $procolor->id }}"
                                                                                    value="{{ $procolor->color }}"
                                                                                    name="product_color"
                                                                                    class="selector-item_radio emptyalert"
                                                                                    required
                                                                                    {{ $key == 0 ? 'checked' : '' }} />
                                                                                <label for="fc-option{{ $procolor->id }}"
                                                                                    id="smimg{{ $key }}"
                                                                                    onclick="setimg({{ $key }})"
                                                                                    style="{{ $key == 0 ? 'border: 4px solid #e4002b;' : '' }}"
                                                                                    class="selector-item_label">
                                                                                    <img src="{{ asset($procolor->Image) }}"
                                                                                        alt="Color Image" />
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @php
                                                        $productsi = $productsizes;
                                                        $data = $productsi->where('size_id', 1)->first();
                                                    @endphp

                                                    @if (isset($productsi))

                                                        {{-- @foreach ($productsizes as $prosize)
                                                            <div class="selector-item" hidden>
                                                                <input type="radio" id="f-option{{ $prosize->id }}"
                                                                    value="{{ $prosize->size }}" name="product_size"
                                                                    class="selector-item_radio emptyalert" required
                                                                    checked />
                                                                <label for="f-option{{ $prosize->id }}"
                                                                    onclick="setprice({{ $prosize->RegularPrice }},{{ $prosize->SalePrice }})"
                                                                    class="selector-item_label">{{ $prosize->size }}</label>
                                                            </div>
                                                        @endforeach
                                                    @else --}}
                                                        @if (count($productsizes) == 0)
                                                        @else
                                                            <div class="pro-size d-flex" style="width: 100%;">
                                                                <h6 class="mb-2"><b>Weight :</b></h6>
                                                                <div class="size_inner mx-2">
                                                                    <div class="size-container">
                                                                        <div class="selector">
                                                                            @foreach ($productsizes as $prosize)
                                                                                <div class="selector-item">
                                                                                    <input type="radio"
                                                                                        id="f-option{{ $prosize->id }}"
                                                                                        value="{{ $prosize->size }}"
                                                                                        name="product_size"
                                                                                        class="selector-item_radio emptyalert select_product_size"
                                                                                        required />
                                                                                    <label style="padding:0px 8px"
                                                                                        for="f-option{{ $prosize->id }}"
                                                                                        onclick="setprice({{ $prosize->RegularPrice }},{{ $prosize->SalePrice }})"
                                                                                        class="selector-item_label">{{ $prosize->size }}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if ($details->pro_unit)
                                                        <div class="pro_unig">
                                                            <label>Unit: {{ $details->pro_unit }}</label>
                                                            <input type="hidden" name="pro_unit"
                                                                value="{{ $details->pro_unit }}" />
                                                        </div>
                                                    @endif


                                                    <div class="row">
                                                        <div class="qty-cart col-sm-12">
                                                            <div class="quantity">
                                                                <span class="minus">-</span>
                                                                <input type="text" id="getqty" name="qty"
                                                                    value="1" />
                                                                <span class="plus">+</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex single_product col-sm-12">
                                                            <input type="submit" id="addtocart_btn_m"
                                                                class="btn px-4 add_cart_btn"
                                                                onclick="return sendSuccess();" name="add_cart"
                                                                value="ADD TO CART"
                                                                style="background:#900C3F;color:#fff;border:1px solid #900C3F;" />

                                                            <input type="submit" id="order_now_btn_m"
                                                                class="btn px-4 order_now_btn order_now_btn_m"
                                                                onclick="return sendSuccess();" name="order_now"
                                                                value="BUY NOW"
                                                                style="background-color: #006400;border-color: #006400;color: #fff;" />
                                                        </div>
                                                    </div>
                                                    <div class="mt-md-2 mt-2">
                                                        <h4 class="font-weight-bold">
                                                            <a class="btn w-100 call_now_btn"
                                                                href="tel: {{ $contact->hotline }}"
                                                                style="background: #00008B;color:white">
                                                                <i class="fa fa-phone-square"></i>
                                                                {{ $contact->hotline }}
                                                            </a>
                                                        </h4>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="description-nav-wrapper">
        <div class="container">
            <div class="row">

                <div class="col-sm-12">
                    <div class="description-nav">
                        <ul class="desc-nav-ul">

                            <li>
                                <a href="#description" target="_self">Description</a>
                            </li>

                            <li>
                                <a href="#writeReview" target="_self">Reviews ({{ $reviews->count() }}) </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="pro_details_area">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="description tab-content details-action-box" id="description">
                        <h2>বিস্তারিত</h2>
                        <p>{!! $details->description !!}</p>
                    </div>
                    <div class="tab-content details-action-box" id="writeReview">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="section-head">
                                        <div class="title">
                                            <h2>Reviews ({{ $reviews->count() }})</h2>
                                            <p>Get specific details about this product from customers who own it.</p>
                                        </div>
                                        <div class="action">
                                            <div>
                                                <button type="button" class="details-action-btn question-btn btn-overlay"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                    style="background-color:#38a943">
                                                    Write a review
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($reviews->count() > 0)
                                        <div class="customer-review">
                                            <div class="row">
                                                @foreach ($reviews as $key => $review)
                                                    <div class="col-sm-12 col-12">
                                                        <div class="review-card">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <img src="{{ asset($review->image) }}"
                                                                        alt="">
                                                                </div>
                                                                <div class="col-9">
                                                                    <p class="reviewer_name"><i
                                                                            data-feather="message-square"></i>
                                                                        {{ $review->name }}</p>
                                                                    <p class="review_data">
                                                                        {{ $review->created_at->format('d-m-Y') }}</p>
                                                                    <p class="review_star">{!! str_repeat('<i class="fa-solid fa-star"></i>', $review->ratting) !!}</p>
                                                                    <p class="review_content">{{ $review->review }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-content">
                                            <i class="fa fa-clipboard-list"></i>
                                            <p class="empty-text">This product has no reviews yet. Be the first one to
                                                write a review.</p>
                                        </div>
                                    @endif
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" style="width: 100%">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Your review</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="insert-review">
                                                        @if (Auth::guard('customer')->user())
                                                            <form action="{{ route('customer.review') }}"
                                                                id="review-form" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $details->id }}">
                                                                <div class="fz-12 mb-2">
                                                                    <div class="rating">
                                                                        <label title="Excelent">
                                                                            ☆
                                                                            <input required type="radio" name="ratting"
                                                                                value="5" />
                                                                        </label>
                                                                        <label title="Best">
                                                                            ☆
                                                                            <input required type="radio" name="ratting"
                                                                                value="4" />
                                                                        </label>
                                                                        <label title="Better">
                                                                            ☆
                                                                            <input required type="radio" name="ratting"
                                                                                value="3" />
                                                                        </label>
                                                                        <label title="Very Good">
                                                                            ☆
                                                                            <input required type="radio" name="ratting"
                                                                                value="2" />
                                                                        </label>
                                                                        <label title="Good">
                                                                            ☆
                                                                            <input required type="radio" name="ratting"
                                                                                value="1" />
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="message-text"
                                                                        class="col-form-label">Message:</label>
                                                                    <textarea required class="form-control radius-lg" name="review" id="message-text"></textarea>
                                                                    <span id="validation-message"
                                                                        style="color: red;"></span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button class="details-review-button"
                                                                        type="submit">Submit
                                                                        Review</button>
                                                                </div>

                                                            </form>
                                                        @else
                                                            <a class="customer-login-redirect"
                                                                href="{{ route('customer.login') }}">Login
                                                                to Post
                                                                Your Review</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pro_vide">
                        <h2>ভিডিও</h2>
                        <iframe width="100%" height="315"
                            src="https://www.youtube.com/embed/{{ $details->pro_video }}" title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="related-product-section">
        <div class="container">
            <div class="row">
                <div class="related-title">
                    <h5>Related Product</h5>
                </div>
            </div>
            <div class="row" style="overflow: hidden;">
                <div class="col-sm-12">
                    <div class="product-inner owl-carousel related_slider">
                        @foreach ($products as $key => $value)
                            <div class="product_item wist_item wow fadeInDown" data-wow-duration="1.5s"
                                data-wow-delay="0.{{ $key }}s">
                                <div class="product_item_inner">
                                    @if ($value->old_price)
                                        <div class="sale-badge">
                                            <div class="sale-badge-inner">
                                                <div class="sale-badge-box">
                                                    <span class="sale-badge-text">
                                                        <p>@php
                                                            $discount =
                                                                (($value->old_price - $value->new_price) * 100) /
                                                                $value->old_price;
                                                        @endphp
                                                            {{ number_format($discount, 0) }}% OFF</p>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="pro_img">
                                        <a href="{{ route('product', $value->slug) }}">
                                            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                                alt="{{ $value->name }}" />
                                        </a>
                                    </div>
                                    <div class="pro_des">
                                        <div class="pro_name">
                                            <a
                                                href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                        </div>
                                        <div class="pro_price">
                                            <p>
                                                <del>৳ {{ $value->old_price }}</del>
                                                ৳ {{ $value->new_price }} @if ($value->old_price)
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                    <div class="pro_btn">

                                        <div class="cart_btn order_button">
                                            <a href="{{ route('product', $value->slug) }}" class="addcartbutton">Buy
                                                Now</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="pro_btn">

                                        <form action="{{ route('cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $value->id }}" />
                                            <input type="hidden" name="qty" value="1" />
                                            <button type="submit">Buy Now</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endsection @push('script')
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('public/frontEnd/js/zoomsl.min.js') }}"></script>

    <script>
        function setprice(delp, regp) {
            $('#delp').html(delp);
            $('#regp').html(regp);
        }
        $(document).ready(function() {
            $(".details_slider").owlCarousel({
                margin: 15,
                items: 1,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 1000000000,
                autoplayHoverPause: true,
            });
            $(".indicator-item").on("click", function() {
                var slideIndex = $(this).data("id");
                $(".details_slider").trigger("to.owl.carousel", slideIndex);
            });

        });

        function setimg(indx) {
            $(".selector-item_label").css("border", '1px solid #ddd');
            $("#smimg" + indx).css("border", '4px solid #e4002b');
            $(".details_slider").trigger("to.owl.carousel", indx);
        }

        $(document).ready(function() {
            setimg(0); // Auto-select the first color on load
        });
    </script>
    <!--Data Layer Start-->
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            ecommerce: null
        });
        dataLayer.push({
            event: "view_item",
            ecommerce: {
                currency: "BDT",
                value: Number("<?php echo $details->new_price; ?>"),
                items: [{
                    item_name: "{{ $details->name }}",
                    item_id: Number("<?php echo $details->id; ?>"),
                    price: Number("<?php echo $details->new_price; ?>"),
                    item_brand: "{{ $details->brand ? $details->brand->name : '' }}",
                    item_category: "{{ $details->category->name }}",
                    item_variant: Number("<?php echo $details->pro_unit; ?>"),
                    currency: "BDT",
                    quantity: $('#getqty').val()
                }]

            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            document.getElementById('addcart').addEventListener('submit', function(event) {
                window.dataLayer = window.dataLayer || [];
                dataLayer.push({
                    ecommerce: null
                });
                dataLayer.push({
                    event: "add_to_cart",
                    ecommerce: {
                        currency: "BDT",
                        value: Number("<?php echo $details->new_price; ?>"),
                        items: [{
                            item_name: "{{ $details->name }}",
                            item_id: Number("<?php echo $details->id; ?>"),
                            price: Number("<?php echo $details->new_price; ?>"),
                            item_brand: "{{ $details->brand ? $details->brand->name : '' }}",
                            item_category: "{{ $details->category->name }}",
                            item_variant: Number("<?php echo $details->pro_unit; ?>"),
                            currency: "BDT",
                            quantity: $('#getqty').val()
                        }]
                    }
                });
            });
        });
    </script>


    <!-- Data Layer End-->
    <script>
        $(document).ready(function() {
            $(".related_slider").owlCarousel({
                margin: 10,
                items: 6,
                loop: true,
                dots: true,
                nav: true,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                    },
                    600: {
                        items: 3,
                        nav: false,
                    },
                    1000: {
                        items: 6,
                        nav: true,
                        loop: true,
                    },
                },
            });
            // $('.owl-nav').remove();
        });
    </script>
    <script>
        $(document).ready(function() {
            // $(".minus").click(function() {
            //     var $input = $(this).parent().find("input");
            //     var count = parseInt($input.val()) - 1;
            //     count = count < 1 ? 1 : count;
            //     $input.val(count);
            //     $input.change();
            //     return false;
            // });
            // $(".plus").click(function() {
            //     var $input = $(this).parent().find("input");
            //     $input.val(parseInt($input.val()) + 1);
            //     $input.change();
            //     return false;


            $(".minus").click(function() {
                var $input = $(this).parent().find("input");
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                
                var product_type = "{{ $details->type }}";
                var single_product_stock = "{{ $details->stock }}";
                var product_id = "{{ $details->id }}";
                var size = $('.select_product_size:checked').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ url('get/quantity/cart') }}",
                    data: {
                        product_id: product_id,
                        size: size
                    },
                    success: function(data) {
                        if (product_type == 1) {
                            if (data < count) {
                                $('#addtocart_btn_m').addClass('disabled');
                                $('#order_now_btn_m').addClass('disabled');

                                $('.product_stock_out').removeClass('d-none');
                            } else {
                                $('#addtocart_btn_m').removeClass('disabled');
                                $('#order_now_btn_m').removeClass('disabled');

                                $('.product_stock_out').addClass('d-none');
                            }
                        }else{
                            if(single_product_stock < count){
                                $('#addtocart_btn_m').addClass('disabled');
                                $('#order_now_btn_m').addClass('disabled');

                                $('.product_stock_out').removeClass('d-none');
                            }else {
                                $('#addtocart_btn_m').removeClass('disabled');
                                $('#order_now_btn_m').removeClass('disabled');

                                $('.product_stock_out').addClass('d-none');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
                return false;
            });

            $(".plus").click(function() {
                var $input = $(this).parent().find("input");
                var count = parseInt($input.val()) + 1;
                $input.val(count);
                $input.change();

                var product_type = "{{ $details->type }}";
                var single_product_stock = "{{ $details->stock }}";
                var product_id = "{{ $details->id }}";
                var size = $('.select_product_size:checked').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ url('get/quantity/cart') }}",
                    data: {
                        product_id: product_id,
                        size: size
                    },
                    success: function(data) {
                        if (product_type == 1) {
                            if (data < count) {
                                $('#addtocart_btn_m').addClass('disabled');
                                $('#order_now_btn_m').addClass('disabled');

                                $('.product_stock_out').removeClass('d-none');
                            } else {
                                $('#addtocart_btn_m').removeClass('disabled');
                                $('#order_now_btn_m').removeClass('disabled');

                                $('.product_stock_out').addClass('d-none');
                            }
                        }else{
                            if(single_product_stock < count){
                                $('#addtocart_btn_m').addClass('disabled');
                                $('#order_now_btn_m').addClass('disabled');

                                $('.product_stock_out').removeClass('d-none');
                            }else {
                                $('#addtocart_btn_m').removeClass('disabled');
                                $('#order_now_btn_m').removeClass('disabled');

                                $('.product_stock_out').addClass('d-none');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
                return false;
            });

        });
    </script>

    <script>
        function sendSuccess() {
            // size validation
            size = document.forms["formName"]["product_size"].value;
            if (size != "") {
                // access
            } else {
                toastr.warning("Please select any size");
                return false;
            }
            color = document.forms["formName"]["product_color"].value;
            if (color != "") {
                // access
            } else {
                toastr.error("Please select any color");
                return false;
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".rating label").click(function() {
                $(".rating label").removeClass("active");
                $(this).addClass("active");
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".thumb_slider").owlCarousel({
                margin: 15,
                items: 4,
                loop: true,
                dots: false,
                nav: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
            });
        });
    </script>

    <script type="text/javascript">
        $(".block__pic").imagezoomsl({
            zoomrange: [3, 3]
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.select_product_size', function() {
                let product_id = "{{ $details->id }}";
                let size = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ url('get/quantity/cart') }}",
                    data: {
                        product_id: product_id,
                        size: size
                    },
                    success: function(data) {
                        if (data == 0) {
                            $('#addtocart_btn_m').addClass('disabled');
                            $('#order_now_btn_m').addClass('disabled');

                            $('.product_stock_out').removeClass('d-none');
                        } else {
                            $('#addtocart_btn_m').removeClass('disabled');
                            $('#order_now_btn_m').removeClass('disabled');

                            $('.product_stock_out').addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
