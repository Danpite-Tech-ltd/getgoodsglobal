@extends('frontEnd.layouts.master') @section('title', 'Home') @push('seo')
<meta name="app-url" content="" />
<meta name="robots" content="index, follow" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<!-- Open Graph data -->
<meta property="og:title" content="" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="{{ asset($generalsetting->white_logo) }}" />
<meta property="og:description" content="" />
@endpush @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet" />
<style>
    .wishlist_bg {
        background: #00008B !important;
    }
</style>
@endpush @section('content')



<!-- Your existing slider-section starts here -->
<section class="slider-section">
    <div class="container-fluid">
        <div class="row">

            <!-- Slider Section -->
            <div class="col-lg-12 col-md-12 col-12 p-0">
                <div class="home-slider-container">
                    <div class="main_slider owl-carousel">
                        @foreach ($sliders as $key => $value)
                            <a href="{{ $value->link }}">
                                <div class="slider-item">
                                    <img class="img-fluid" src="{{ asset($value->image) }}" alt="" />
                                </div>
                                <!-- slider item -->
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- slider end -->

<!--50% Off Product-->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark"> FLASH SALES </span>
                            </div>

                            <div class="">
                                <div class="offer_timer " id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if ($value->old_price)
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

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
                                            @if ($value->old_price)
                                                <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                <div class="pro_btn">

                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton btn btn-danger">Buy Now</a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">

                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <button type="submit" class="">Buy Now</button>
                                    </form>
                                </div>
                            @endif

                            <!-- wishlist -->
                            <div class="pro_btn my-1">
                                <button type="button" onclick="addTowishlist('{{ $value->id }}')"
                                    class="wishlist_bg">ADD WISHLIST</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!--New Arrival Product-->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark"> NEW ARRIVALS</span>
                            </div>

                            <div class="">
                                <div class="offer_timer " id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @php
                        $new_products = App\Models\Product::where('status', 1)->latest()->get();
                    @endphp
                    @foreach ($new_products as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if ($value->old_price)
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

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
                                            @if ($value->old_price)
                                                <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                <div class="pro_btn">

                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton btn btn-danger">Buy Now</a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">

                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <button type="submit" class="">Buy Now</button>
                                    </form>
                                </div>
                            @endif

                            <!-- wishlist -->
                            <div class="pro_btn my-1">
                                <button type="button" onclick="addTowishlist('{{ $value->id }}')"
                                    class="wishlist_bg">ADD WISHLIST</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--<div class="col-sm-12">-->
            <!--   <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a> -->
            <!--</div>-->
        </div>
    </div>
</section>

<!--Top Categories-->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark"> Top Categories </span>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>

            <!--<div class="col-sm-12">-->
            <!--    <div class="categorycarosel  owl-carousel">-->
            <!--        @foreach ($menucategories as $key => $value)
-->
            <!--            <div class="cat_item text-center">-->
            <!--                <div class="cat_img" style="text-align: -webkit-center;">-->
            <!--                    <a href="{{ route('category', $value->slug) }}">-->
            <!--                        <img src="{{ asset($value->image) }}" alt="" style="border:1px solid;border-radius: 50%;height:100px;width:100px;" />-->
            <!--                    </a>-->
            <!--                </div>-->
            <!--                <div class="cat_name">-->
            <!--                    <a href="{{ route('category', $value->slug) }}" class="text-truncate">-->
            <!--                        {{ $value->name }}-->
            <!--                    </a>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--
@endforeach-->
            <!--    </div>-->
            <!--</div>-->

            <div class="col-sm-12">
                <div class="cat_sliders">
                    @foreach ($frontcategory as $key => $value)
                        <div class="cat_item text-center">
                            <div class="cat_img" style="text-align: -webkit-center;">
                                <a href="{{ route('category', $value->slug) }}">
                                    <img src="{{ asset($value->image) }}" alt=""
                                        style="border:1px solid;border-radius: 50%;height:100px;width:100px;" />
                                </a>
                            </div>
                            <div class="cat_name">
                                <a href="{{ route('category', $value->slug) }}" class="text-truncate">
                                    {{ Str::limit($value->name,15) }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



        </div>
    </div>
</section>

<!--top trending Product-->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark"> TOP TRENDING</span>
                            </div>

                            <div class="">
                                <div class="offer_timer " id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($feature_products as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if ($value->old_price)
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

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
                                            @if ($value->old_price)
                                                <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                <div class="pro_btn">

                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton btn btn-danger">Buy Now</a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">

                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <button type="submit" class="">Buy Now</button>
                                    </form>
                                </div>
                            @endif
                            <!-- wishlist -->
                            <div class="pro_btn my-1">
                                <button type="button" onclick="addTowishlist('{{ $value->id }}')"
                                    class="wishlist_bg">ADD WISHLIST</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--<div class="col-sm-12">-->
            <!--   <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a> -->
            <!--</div>-->
        </div>
    </div>
</section>


<!-- deal of the Product-->
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark"> DEAL OF THE DAY</span>
                            </div>

                            <div class="">
                                <div class="offer_timer " id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($dealofthe_products as $key => $value)
                        <div class="product_item wist_item">
                            <div class="product_item_inner">
                                @if ($value->old_price)
                                    <div class="sale-badge">
                                        <div class="sale-badge-inner">
                                            <div class="sale-badge-box">
                                                <span class="sale-badge-text">
                                                    <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

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
                                            @if ($value->old_price)
                                                <del>৳ {{ $value->old_price }}</del>
                                            @endif

                                            ৳ {{ $value->new_price }}

                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if (!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                <div class="pro_btn">

                                    <div class="cart_btn order_button">
                                        <a href="{{ route('product', $value->slug) }}"
                                            class="addcartbutton btn btn-danger">Buy Now</a>
                                    </div>
                                </div>
                            @else
                                <div class="pro_btn">

                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $value->id }}" />
                                        <input type="hidden" name="qty" value="1" />
                                        <button type="submit" class="">Buy Now</button>
                                    </form>
                                </div>
                            @endif
                            <!-- wishlist -->
                            <div class="pro_btn my-1">
                                <button type="button" onclick="addTowishlist('{{ $value->id }}')"
                                    class="wishlist_bg">ADD WISHLIST</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--<div class="col-sm-12">-->
            <!--   <a href="{{ route('hotdeals') }}" class="view_more_btn" style="float:left">View More</a> -->
            <!--</div>-->
        </div>
    </div>
</section>



@foreach ($homeproducts as $homecat)
    <section class="homeproduct">
        <div class="container">
            <div class="row">
                <div class="col-sm-12" style="display: flex;justify-content: space-between;">
                    <div class="sec_title">
                        <h3 class="section-title-header">
                            <span class="section-title-name text-dark">{{ Str::limit($homecat->name,25) }}</span>

                        </h3>
                    </div>
                    <div class="show_more_btn pt-2">
                        <a href="{{ route('category', $homecat->slug) }}" class="view_more_btn background_color">View
                            More</a>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="product_sliders">
                        @foreach ($homecat->products->take(6) as $key => $value)
                            <div class="product_item wist_item">
                                <div class="product_item_inner">


                                    @if ($value->old_price)
                                        <div class="sale-badge ">
                                            <div class="sale-badge-inner">
                                                <div class="sale-badge-box">

                                                    <span class="sale-badge-text">
                                                        <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

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
                                                @if ($value->old_price)
                                                    <del>৳ {{ $value->old_price }}</del>
                                                @endif

                                                ৳ {{ $value->new_price }}

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
                                            <button type="submit" class="">Buy Now</button>
                                        </form>
                                    </div>
                                @endif
                                <!-- wishlist -->
                                <div class="pro_btn my-1">
                                    <button type="button" onclick="addTowishlist('{{ $value->id }}')"
                                        class="wishlist_bg">ADD WISHLIST</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>



            </div>

        </div>
    </section>
@endforeach








<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="">
                                <span class="section-title-name text-dark">Blog post</span>
                            </div>
                            <div class="">
                                <div class="offer_timer" id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($blog as $key => $blogs)
                        @if ($blogs->status == 1)
                            <div class="card p-2">
                                <img src="{{ asset($blogs->b_image) }}" alt="" class="img-fluid" />
                                <div>
                                    <a href="{{ route('single_blog', $blogs->id) }}">
                                        <h6 class="mt-3 mb-3" style="font-weight: bold;">{{ $blogs->b_title }}</h6>
                                    </a>
                                    <p>{{ Str::limit($blogs->b_short_des, 100) }} <a
                                            href="{{ route('single_blog', $blogs->id) }}" class="text-danger">Read
                                            more...</a></p>
                                    {{-- <a href="" class="btn btn-danger mt-3" style="background:#e4002b;">Read More</a> --}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>




@endsection @push('script')
<script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('public/frontEnd/js/jquery.syotimer.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: true,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 1500,
            autoplayTimeout: 4000,

            navText: ["<i class='fa-solid fa-angle-left'></i>",
                "<i class='fa-solid fa-angle-right'></i>"
            ],
        });

        $(".categorycarosel").owlCarousel({
            loop: true,
            margin: 10,
            autoplay: false,
            responsive: {
                0: {
                    items: 3
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 8
                }
            }
        });

        $(".blogcarousell").owlCarousel({
            loop: true,
            margin: 10,
            autoplay: false,
            responsive: {
                0: {
                    items: 2
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 4
                }
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".hotdeals-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".category-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 5,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 8,
                    nav: true,
                    loop: false,
                },
            },
        });

        $(".product_slider").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 5,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },
        });
    });
</script>

<script>
    $("#simple_timer").syotimer({
        date: new Date(2015, 0, 1),
        layout: "hms",
        doubleNumbers: true,
        effectType: "opacity",

        periodUnit: "d",
        periodic: true,
        periodInterval: 1,
    });
</script>
@endpush
