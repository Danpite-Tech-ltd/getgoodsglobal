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
@endpush @section('content')
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mt-4 mb-4">
                    Shop Products
                </h2>
            </div>
            <div class="col-sm-12">
                <div class="product_sliders">
                    @foreach ($homeproducts as $homecat)
                        @if (count($homecat->products) > 0)
                            @foreach ($homecat->products as $key => $value)
                                <div class="product_item wist_item">
                                    <div class="product_item_inner">
                                        @if ($value->old_price)
                                            <div class="sale-badge">
                                                <div class="sale-badge-inner">
                                                    <div class="sale-badge-box">
                                                        <span class="sale-badge-text">
                                                            <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF
                                                            </p>

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
                                                    class="addcartbutton">Buy Now </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="pro_btn">

                                            <form action="{{ route('cart.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $value->id }}" />
                                                <input type="hidden" name="qty" value="1" />
                                                <button type="submit" class="bg-danger">Buy Now</button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- wishlist -->
                                    <div class="pro_btn my-1">
                                        <button type="button" onclick="addTowishlist('{{ $value->id }}')">ADD
                                            WISHLIST</button>
                                    </div>
                                </div>
                            @endforeach
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
            autoplayTimeout: 3000,

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
        doubleNumbers: false,
        effectType: "opacity",

        periodUnit: "d",
        periodic: true,
        periodInterval: 1,
    });
</script>
@endpush
