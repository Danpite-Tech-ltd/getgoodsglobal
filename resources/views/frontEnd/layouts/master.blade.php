<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title') - {{$generalsetting->name}}</title>
        <!-- App favicon -->

        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="Super Ecommerce Favicon" />
        <meta name="author" content="Super Ecommerce" />
        <link rel="canonical" href="" />
        @stack('seo')
        @stack('css')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js"
            integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.css" integrity="sha512-phGxLIsvHFArdI7IyLjv14dchvbVkEDaH95efvAae/y2exeWBQCQDpNFbOTdV1p4/pIa/XtbuDCnfhDEIXhvGQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- toastr css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

         <link rel="stylesheet" href="{{asset('public/frontEnd/css/mobile-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/wsit-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/style.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/responsive.css')}}" />
        <link rel="stylesheet" href="{{asset('public/frontEnd/css/main.css')}}" />

        <style>
            @media only screen and (min-width: 320px) and (max-width: 767px){
                   #google_translate_element{
                       left:55%!important;
                   }
               }
            .background_color{
                background: #006400;
            }
            #mainskiky{
                margin-top:56px;
            }
            #mainheadertp{
                position: relative;
                z-index: 999999;
            }
        </style>

    </head>
    <body class="gotop">
        @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp

        <div class="logo-area background_color p-0" id="mainheadertp">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <a href="{{url('customer/login')}}" style="color: white;font-weight: bold;line-height: 49px;font-size: 18px;float: left;">
                            <i class="fas fa-location-dot"></i> {{$contact->address}}
                        </a>
                    </div>
                    <div class="col-lg-2">
                        <a style="color: white;font-weight: bold;line-height: 49px;font-size: 18px;float: left;">
                            <i class="fa fa-clock"></i> 9 AM - 7 PM
                        </a>

                    </div>
                    <div class="col-lg-2">
                        <a href="tel:{{ App\Models\Contact::first()->phone }}" style="color: white;font-weight: bold;line-height: 49px;font-size: 18px;float: left;">
                            <i class="fa fa-phone"></i> {{ App\Models\Contact::first()->phone }}
                        </a>

                    </div>
                    <div class="col-lg-3">
                        <div class="main-search">
                            <form action="{{route('search')}}">
                                <input type="text" placeholder="Search Product..." class="search_keyword search_click" name="keyword" />
                                <button>
                                    <i data-feather="search"></i>
                                </button>
                            </form>
                            <div class="search_result"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @if (Auth::guard('customer')->check())
                            <a href="{{ route('customer.account') }}" style="color: white;font-weight: bold;line-height: 49px;font-size: 18px;float: right;">
                                <i class="fas fa-user"></i> My Account
                            </a>
                        @else
                            <a href="{{url('customer/login')}}" style="color: white;font-weight: bold;line-height: 49px;font-size: 18px;float: right;">
                                <i class="fas fa-user"></i> My Account
                            </a>
                        @endif

                    </div>

                </div>
            </div>
        </div>

        <div class="mobile-menu" style="background:">
                <div class="mobile-menu-logo">
                    <div class="logo-image ">
                        <img src="{{asset($generalsetting->white_logo)}}" alt="" style="width:120px" />
                    </div>
                    <div class="mobile-menu-close">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <ul class="first-nav text-white" >
                    @foreach($menucategories as $scategory)
                    <li class="parent-category">
                        <a href="{{url('category/'.$scategory->slug)}}" class="menu-category-name" style="font-size: 20px">
                            <img  class="rounded" src="{{asset($scategory->image)}}" alt="" class="side_cat_img" style="width:40px"/>
                            {{$scategory->name}}
                        </a>
                        @if($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                        @endif
                        <ul class="second-nav" style="display: none;">
                            @foreach($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory ">
                                <a href="{{url('subcategory/'.$subcategory->slug)}}" class="menu-subcategory-name">{{$subcategory->subcategoryName}}</a>
                                @if($subcategory->childcategories->count() > 0)
                                <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                @endif
                                <ul class="third-nav" style="display: none;">
                                    @foreach($subcategory->childcategories as $childcat)
                                    <li class="childcategory"><a href="{{url('products/'.$childcat->slug)}}" class="menu-childcategory-name">{{$childcat->childcategoryName}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
        <header id="navbar_top background_color">
            <div class="mobile-header sticky ">
                <div class="mobile-logo bd-danger">
                    <div class="menu-logo mx-1">
                        <a href="{{route('home')}}"><img src="{{asset($generalsetting->white_logo)}}" alt="" /></a>
                    </div>
                    <div class="me"></div>
                <div style="display: flex;justify-content: end;">
                    <div class="main-search" style="width: 122px;">
                        <form action="https://onekkisubd.com/search">
                            <input type="text" style="width:84%" class="search_keyword search_click" name="keyword" placeholder="Search...">
                            <button style="width:100%!important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </form>
                        <div class="search_result"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="main-header" id="mainskiky">
                <div class="logo-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="logo-header">
                                    {{-- <div class="all-categories dropdown">
                                        <button class="btn btn-danger dropdown-toggle w-100" type="button">ALL CATEGORIES <i class="fas fa-bars"></i></button>
                                        <ul class="dropdown-menu">
                                            @foreach ($menucategories as $value)
                                                <li><a class="dropdown-item" href="{{ route('category', $value->slug) }}">{{ $value->name }}</a></li><br>
                                            @endforeach
                                        </ul>
                                    </div> --}}
                                    <style>
                                        .navbar-dark .navbar-nav .nav-link { color: #fff; }
                                        .dropdown-menu { display: none; opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease; }
                                        .dropdown:hover > .dropdown-menu, .dropdown-menu.show { display: block; opacity: 1; transform: translateY(0); }
                                        .mega-menu { width: 600px; padding: 20px; }
                                        .nested-dropdown { position: absolute; left: 100%; top: 0; display: none; min-width: 200px; background: #fff; padding: 10px; border: 1px solid #ddd; }
                                        .dropdown-item:hover > .nested-dropdown { display: block; }
                                    </style>
                                    <nav class="navbar navbar-expand-lg">
                                        <div class="container-fluid">
                                            <div class="dropdown">
                                                <a class="nav-link dropdown-toggle btn p-2 text-white" style="background: #006400" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars"></i> CATEGORIES
                                                </a>
                                                <div class="category-dropdown-menu dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <div class="sidebar-menu pt-2">
                                                        <ul class="hideshow">
                                                            @foreach ($menucategories as $key => $category)
                                                                <li>
                                                                    <a style="" href="{{ route('category', $category->slug) }}">
                                                                        {{-- <img src="{{ asset($category->image) }}" alt=""
                                                                            style="width: 30px; height: 30px;" /> --}}
                                                                        {{ $category->name }}
                                                                    </a>
                                                                    <ul class="sidebar-submenu">
                                                                        @foreach ($category->subcategories as $key => $subcategory)
                                                                            <li>
                                                                                <a href="{{ route('subcategory', $subcategory->slug) }}">
                                                                                    {{ $subcategory->subcategoryName }} <i
                                                                                        class="fa-solid fa-chevron-right"></i>
                                                                                </a>
                                                                                <ul class="sidebar-childmenu">
                                                                                    @foreach ($subcategory->childcategories as $key => $childcat)
                                                                                        <li>
                                                                                            <a href="{{ route('products', $childcat->slug) }}">
                                                                                                {{ $childcat->childcategoryName }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                    <div class="main-logo">
                                        <a href="{{route('home')}}"><img src="{{asset($generalsetting->dark_logo)}}" alt="" /></a>
                                    </div>
                                    <div class="main-search">
                                        <nav class="navbar navbar-expand-lg navbar-light bg-none " style="justify-content: center;font-size: 18px;padding: 0px;">
                                            <ul class="navbar-nav ">
                                                <li class="nav-item active">
                                                    <a class="nav-link" href="{{url('/')}}" style="    padding: 3px 30px;">Home </a>
                                                </li>
                                                <!--<li class="nav-item">-->
                                                <!--    <a class="nav-link" href="{{url('blog-list')}}" style="    padding: 3px 30px;">Blogs</a>-->
                                                <!--</li>-->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{route('customer.order_track')}}" style="    padding: 3px 30px;">Track Order</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{url('page/return-policy')}}" style="    padding: 3px 30px;">Return Policy</a>
                                                </li>

                                                 <li class="nav-item ">
                                                    <a class="nav-link " href="{{url('shop')}}" style="    padding: 3px 30px; ">Shop</a>
                                                </li>
                                            </ul>

                                        </nav>
                                    </div>

                                    <div class="header-list-items">
                                        <ul>
                                            <li>
                                                <a href="{{ route('wishlist') }}" class="mx-2">
                                                    <p class="margin-shopping">
                                                        <i class="fa-regular fa-heart" style=" width: 20px; height: 20px;"></i>
                                                        <span id="wishlistCount">{{Cart::instance('wishlist')->count()}}</span>
                                                    </p>
                                                </a>
                                            </li>

                                            <li class="cart-dialog" id="cart-qty">


                                                <a href="{{route('customer.checkout')}}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-cart-shopping" style=" width: 20px; height: 20px;"></i>
                                                        <span>{{Cart::instance('shopping')->count()}}</span>
                                                    </p>
                                                </a>
                                                <div class="cshort-summary">
                                                    <ul>
                                                        @foreach(Cart::instance('shopping')->content() as $key=>$value)
                                                        <li>
                                                            <a href=""><img src="{{asset($value->options->image)}}" alt="" /></a>
                                                        </li>
                                                        <li><a href="">{{Str::limit($value->name, 30)}}</a></li>
                                                        <li>Qty: {{$value->qty}}</li>
                                                        <li>
                                                            <p>৳{{$value->price}}</p>
                                                            <button class="remove-cart cart_remove" data-id="{{$value->rowId}}"><i data-feather="x"></i></button>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    <p><strong>TOTAL : ৳{{$subtotal}}</strong></p>
                                                    <a href="{{route('customer.checkout')}}" class="go_cart">BUY NOW </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-header end -->
        </header>
        <div id="content">
            @yield('content')
        </div>
            <!-- content end -->
        <!--    <footer>-->
        <!--    <div class="footer-top">-->
        <!--        <div class="container">-->
        <!--            <div class="row">-->
        <!--                <div class="col-sm-4 mb-3 mb-sm-0">-->
        <!--                    <div class="footer-about">-->
        <!--                        <a href="{{route('home')}}">-->
        <!--                            <img src="{{asset($generalsetting->dark_logo)}}" alt="" />-->
        <!--                        </a>-->
        <!--                        <p>{{$contact->address}}</p>-->
        <!--                        <a href="tel:{{$contact->hotline}}" class="footer-hotlint">{{$contact->hotline}}</a>-->
        <!--                    </div>-->
        <!--                </div>-->
                        <!-- col end -->
        <!--                <div class="col-sm-3 mb-3 mb-sm-0 col-6">-->
        <!--                    <div class="footer-menu">-->
        <!--                        <ul>-->
        <!--                            <li class="title"><a>Useful Link</a></li>-->
        <!--                            <li>-->
        <!--                                <a href="{{route('contact')}}"> <a href="{{route('contact')}}">Contact Us</a></a>-->
        <!--                            </li>-->
        <!--                            @foreach($pages as $page)-->
        <!--                            <li><a href="{{route('page',['slug'=>$page->slug])}}">{{$page->name}}</a></li>-->
        <!--                            @endforeach-->
        <!--                        </ul>-->
        <!--                    </div>-->
        <!--                </div>-->
                        <!-- col end -->
        <!--                <div class="col-sm-2 mb-3 mb-sm-0 col-6">-->
        <!--                    <div class="footer-menu">-->
        <!--                        <ul>-->
        <!--                            <li class="title"><a>Link</a></li>-->
        <!--                            @foreach($pagesright as $key=>$value)-->
        <!--                            <li>-->
        <!--                                <a href="{{route('page',['slug'=>$value->slug])}}">{{$value->name}}</a>-->
        <!--                            </li>-->
        <!--                            @endforeach-->
        <!--                        </ul>-->
        <!--                    </div>-->
        <!--                </div>-->

                        <!-- col end -->
        <!--                <div class="col-sm-3 mb-3 mb-sm-0">-->
        <!--                    <div class="footer-menu">-->
        <!--                        <ul>-->
        <!--                            <li class="title stay_conn"><a>Stay Connected</a></li>-->
        <!--                        </ul>-->
        <!--                        <ul class="social_link">-->
        <!--                            @foreach($socialicons as $value)-->
        <!--                            <li class="social_list">-->
        <!--                                <a class="mobile-social-link" href="{{$value->link}}"><i class="{{$value->icon}}"></i></a>-->
        <!--                            </li>-->
        <!--                            @endforeach-->
        <!--                        </ul>-->
                                <!--<div class="d_app">-->
                                <!--    <h2>Download App</h2>-->
                                <!--    <a href="">-->
                                <!--        <img src="{{asset('public/frontEnd/images/app-download.png')}}" alt="" />-->
                                <!--    </a>-->
                                <!--</div>-->
        <!--                    </div>-->
        <!--                </div>-->
                        <!-- col end -->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--    <div class="footer-bottom background_color">-->
        <!--        <div class="container">-->
        <!--            <div class="row">-->
        <!--                <div class="col-sm-12">-->
        <!--                    <div class="copyright">-->
        <!--                        <p>Copyright © {{ date('Y') }} {{$generalsetting->name}} | All rights reserved | Developed by <a href="https://danpite.tech/" target="_blank" class="text-light text-decoration-underline">Danpite.tech</a></p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</footer>-->



<footer class="footer text-white">
    <div class="container">
        <div class="row text-md-start">
            <div class="col-md-4 mb-4">
                <h5>Get In Touch</h5>
                <div class="divider"></div>
                <p class="text-white"><i class="fas fa-map-marker-alt"></i> {{$contact->address}}</p>
                <p class="text-white"><i class="fas fa-envelope"></i> {{$contact->email}}</p>
                <p class="text-white"><a href="tel:{{$contact->phone}}"><i class="fas fa-phone"></i> {{$contact->phone}} </a> </p>
                <div class="footer-icons">
                    @foreach($socialicons as $value)
                        <li class="social_list">
                            <a href="{{$value->link}}"><i class="{{$value->icon}}"></i></a>
                        </li>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Popular Links</h5>
                <div class="divider"></div>
                <p><a href="{{ url('/') }}"><i class="fas fa-arrow-right"></i> Home</a></p>
                <p><a href="{{url('page/about-us')}}"><i class="fas fa-arrow-right"></i> About Us</a></p>
                <p><a href="{{url('page/contact-us')}}"><i class="fas fa-arrow-right"></i> Contact Us</a></p>
                <p><a href="{{url('page/order-procedure')}}"><i class="fas fa-arrow-right"></i> Order procedure</a></p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <div class="divider"></div>
                @php
                    $quick_links = App\Models\CreatePage::where('status',1)->whereNot('slug','contact-us')->whereNot('slug','about-us')->whereNot('slug','order-procedure')->get();
                @endphp

                @foreach($quick_links as $page)
                    <p><a href="{{route('page',['slug'=>$page->slug])}}"><i class="fas fa-arrow-right"></i> {{$page->name}}</a></p>
                @endforeach
            </div>
        </div>
    </div>
    <div class="text-center" style="background:#051427; padding:20px;">
        <p class="text-white text-center footer_mobile_margin">Copyright © {{ date('Y') }} {{$generalsetting->name}} | All rights reserved | Developed by <a href="https://danpite.tech/" target="_blank" class="text-white fw-bold">Danpite.Tech</a></p>
    </div>
</footer>




        <div class="footer_nav">
            <ul>
                <li>
                    <a href="{{ url('/') }}">
                        <span>
                                {{-- <i class="fa-solid fa-bars"></i> --}}
                            <img src="{{ asset('public/home.png') }}" alt="" width="30">
                        </span>

                    </a>
                </li>

                <li>
                    <a class="toggle">
                        <span>
                            {{-- <i class="fa-regular fa-heart" style=" width: 20px; height: 20px;"></i> --}}
                            <img src="{{ asset('public/menu.png') }}" alt="" width="30">

                        </span>

                    </a>
                </li>

                <li class="mobile_home">
                    <a href="https://wa.me/+88{{App\Models\Contact::first()->phone}}?text={{ request()->fullUrl() }}">
                        <span>
                             <img src="{{ asset('public/live-chat.png') }}" alt="" width="50">

                        </span>
                        {{-- <span class="text-dark"><i class="fa-solid fa-shop"></i></span> <span class="text-dark">Shop</span> --}}
                    </a>
                </li>

                <li>
                    <a href="{{route('customer.order_track')}}">
                        <span>
                            {{-- <i class="fa-solid fa-cart-shopping" style=" width: 20px; height: 20px;"></i> --}}
                            <img src="{{ asset('public/truck.png') }}" alt="" width="30">

                        </span>
                    </a>
                </li>
                @if(Auth::guard('customer')->user())
                <li>
                    <a href="{{url('/')}}">
                        <span>
                            {{-- <i class="fa-solid fa-user"></i> --}}
                            <img src="{{ asset('public/truck.png') }}" alt="" width="30">
                        </span>

                    </a>
                </li>
                @else
                <li>
                    <a href="{{url('/')}}">
                        <span>
                            {{-- <i class="fa-solid fa-user" style=" width: 20px; height: 20px;"></i> --}}
                            <img src="{{ asset('public/back.png') }}" alt="" width="30">
                        </span>

                    </a>
                </li>
                @endif
            </ul>
        </div>
        {{-- <div class="footer_nav">
            <ul>
                <li>
                    <a class="toggle">
                        <span>

                            <img src="https://rashifashionbd.com/public/ihome.png" alt="" width="30">
                        </span>

                    </a>
                </li>

                <li>
                    <a href="{{ route('wishlist') }}">
                        <span>
                            <i class="fa-regular fa-heart" style=" width: 20px; height: 20px;"></i>
                        </span>

                    </a>
                </li>

                <li class="mobile_home">
                    <a href="{{route('shop')}}">
                        <span class="text-dark"><i class="fa-solid fa-shop"></i></span> <span class="text-dark">Shop</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('customer.checkout')}}">
                        <span>
                            <i class="fa-solid fa-cart-shopping" style=" width: 20px; height: 20px;"></i>
                        </span>
                    </a>
                </li>
                @if(Auth::guard('customer')->user())
                <li>
                    <a href="{{route('customer.account')}}">
                        <span>
                            <i class="fa-solid fa-user"></i>
                        </span>

                    </a>
                </li>
                @else
                <li>
                    <a href="{{route('customer.login')}}">
                        <span>
                            <i class="fa-solid fa-user" style=" width: 20px; height: 20px;"></i>
                        </span>

                    </a>
                </li>
                @endif
            </ul>
        </div> --}}
        <html lang="en">


    <style>
    @media only screen and (min-width:320px) and (max-width:767px) {
        .footer_mobile_margin{
            margin-bottom: 60px
        }
    }
    .footer {
            background-color: #0b2341;
            padding-top: 50px;
        }
        .footer h5 {
            font-weight: bold;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            color: #ff4081;
        }
        .footer-icons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .footer-icons a {
            display: flex;
            width: 40px;
            height: 40px;
            background: #78037a;
            color: white;
            border-radius: 5px;
            align-items: center;
            justify-content: center;
        }
        .divider {
            width: 50px;
            height: 3px;
            background: #ff4081;
            margin-bottom: 10px;
        }
    .whats-app-btn{
        position: fixed;
        bottom: 20px;
        right: 20px;
        margin-bottom: 50px;
        z-index: 1000;
        cursor: pointer;
        background-color: transparent;
        background: rgb(45, 183, 66);
        height: 56px;
        width: 56px;
        border-radius: 50%;
        transition: all .4s linear!important;
        & svg {
            color: white;
            width: 36px;
            height: 36px;
        }
        & .fa-whatsapp {
            transition: all .4s linear!important;
            position: absolute;
            top: calc(50% - 18px);
            left: calc(50% - 18px);
        }
        & .fa-x{
            transition: all .4s linear!important;
            position: absolute;
            width: 0px;
            height: 0px;
        }
    }
    /* .all-categories {
                background: red;
                color: white;
                text-align: center;
                padding: 10px;
            } */
    .modal.show .modal-dialog {
        transform: none;
        position: absolute;
        top: 50%;
        left: 50%;
    }
    .wa__popup_chat_box {
        font-family: cardo !important;
        border-radius: 5px 5px 8px 8px;
        -webkit-border-radius: 5px 5px 8px 8px;
        -moz-border-radius: 5px 5px 8px 8px;
        bottom: 132px;
        box-shadow: 0 10px 10px 4px rgba(0,0,0,.04);
        -webkit-box-shadow: 0 10px 10px 4px rgba(0,0,0,.04);
        -moz-box-shadow: 0 10px 10px 4px rgba(0,0,0,.04);
        font-family: Arial,Helvetica,sans-serif;
        max-width: calc(100% - 50px);
        opacity: 0;
        overflow: hidden;
        position: fixed;
        right: 25px;
        -ms-transform: translateY(50px);
        transform: translateY(50px);
        -webkit-transform: translateY(50px);
        -moz-transform: translateY(50px);
        transition: all .4s ease;
        -webkit-transition: all .4s ease;
        -moz-transition: all .4s ease;
        visibility: hidden;
        width: 351px;
        will-change: transform,visibility,opacity;
        z-index: 999999998;
        background-color: white;
        & .modal-header{
            background: #2db742;
            padding: 16px 16px;
            column-gap: 20px;
            & h3 {
                color: white;
                font-size: 18px;
                font-family: cardo !important;
            }
            & p {
                font-size: 12px;
                color: rgb(217, 235, 198);
            }
            & strong{
                font-size: 15px;
            }
        }
        & .modal-body {
            padding: 13px 20px 12px 19px;
            background-color: #ffffff;
            & strong{
                color: #a5abb7;
                font-size: 15px;
                font-weight: 500;
                padding: 0 3px;
            }
            & a {
                background: #f5f7f9;
                border-left: 2px solid #2db742;
                border-radius: 2px 4px 2px 4px;
                -webkit-border-radius: 2px 4px 2px 4px;
                -moz-border-radius: 2px 4px 2px 4px;
                display: table;
                padding: 13px 22px 14px 12px;
                position: relative;
                text-decoration: none;
                width: 100%;
                margin-top: 14px;
                transition: all .4s ease!important;
                & div p{
                    & span:first-child {
                        color: #363c47;
                        font-size: 14px;
                        line-height: 1.188em!important;
                    }
                    & span:last-child {
                        color: #989b9f;
                        font-size: 11px;
                        line-height: 1.125em!important;
                        padding: 2px 0 0;
                    }
                }
                &:hover{
                    background: #fff;
                    box-shadow: 0 7px 15px 1px rgba(55,62,70,.07);
                    -webkit-box-shadow: 0 7px 15px 1px rgba(55,62,70,.07);
                    -moz-box-shadow: 0 7px 15px 1px rgba(55,62,70,.07);
                }
            }
        }
    }
    .wa__popup_chat_box:active, .wa__popup_chat_box:focus, .wa__popup_chat_box:hover {
        box-shadow: 0 10px 10px 4px rgba(32,32,37,.23);
        -webkit-box-shadow: 0 10px 10px 4px rgba(32,32,37,.23);
        -moz-box-shadow: 0 10px 10px 4px rgba(32,32,37,.23);
    }
    .wa__popup_chat_box.wa__active {
        opacity: 1;
        -ms-transform: translate(0);
        transform: translate(0);
        -webkit-transform: translate(0);
        -moz-transform: translate(0);
        visibility: visible;
    }
    .wa__btn_popup_txt {
        background-color: #f5f7f9;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        color: #43474e;
        font-size: 16px;
        letter-spacing: -.03em;
        line-height: 1.5;
        margin-right: 7px;
        padding: 8px 12px;
        position: absolute;
        right: 100%;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        transition: all .4s ease;
        -webkit-transition: all .4s ease;
        -moz-transition: all .4s ease;
        width: 85px;
        display: flex;
        opacity: 1;
        visibility: visible;
        font-family: "cardo" !important;
    }
    .popup_txt_hide {
        transform: translateY(50%);
        visibility: hidden;
        opacity: 0;
    }
    </style>

    <!-- Button trigger modal -->
      <button type="button" class="whats-app-btn">
        <i class="fa-brands fa-whatsapp"></i>
        <i class="fa-solid fa-x"></i>
        <div class="wa__btn_popup_txt "><span>Need <br> Help?</span></div>
      </button>

    <!-- Modal -->
    <div class=" wa__popup_chat_box ">
        <div class="modal-header">
            <div><img src="{{ asset('public/frontEnd/images/whatsapp-svgrepo-com.svg') }}" alt="WhatsApp Chat" width="45px"></div>
            <div>
                <h3 class="modal-title" id="exampleModalLabel">Start a chat with <span class="text-capitalize">{{ env('APP_NAME') }}</span>! </h3>
                <p>Hi! Click one of our member below to chat on <strong> WhatsApp</strong></p>
            </div>
        </div>
        <div class="modal-body">
            <strong>We Will Reply shortly</strong>
            <a href="https://wa.me/+88{{App\Models\Contact::first()->phone}}?text={{ request()->fullUrl() }}" class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="column-gap: 8px">
                    <img src="{{ asset('public/frontEnd/images/whatsapp-white-border.png') }}" alt="WhatsApp Chat" width="40px">
                    <p class="d-flex flex-column"><span>live chat</span><span class="text-capitalize">{{ env('APP_NAME') }}</span></p>
                </div>
                <div>
                    <img src="{{ asset('public/frontEnd/images/whatsapp.png') }}" alt="WhatsApp Chat" width="20px">
                </div>
            </a>
        </div>
        <div class="modal-footer justify-content-center pb-3">
            <a href=""><i color="#a9a9a9" class="fa-solid fa-bolt"></i></a>
        </div>
    </div>

    <script>
        var btn = document.querySelector('.whats-app-btn');
        if (btn) {
            btn.addEventListener('click', function () {
                var popup = document.querySelector('.wa__popup_chat_box');
                var whatsapp = document.querySelector('.fa-whatsapp');
                var need_help = document.querySelector('.wa__btn_popup_txt');
                var fa_x = document.querySelector('.fa-x');
                if (popup && whatsapp) {
                    popup.classList.toggle('wa__active');
                    need_help.classList.toggle('popup_txt_hide');

                    if (popup.classList.contains('wa__active')) {
                        // Add rotation animation
                        whatsapp.style.transform = 'rotate(360deg)'; // One full rotation
                        whatsapp.style.width = '0px';
                        whatsapp.style.height = '0px';
                        whatsapp.style.top = '50%';
                        whatsapp.style.left = '50%';

                        fa_x.style.transform = 'rotate(-360deg)'; // One full rotation
                        fa_x.style.width = '26px';
                        fa_x.style.height = '26px';
                        fa_x.style.top = 'calc(50% - 13px)';
                        fa_x.style.left = 'calc(50% - 13px)';
                    }
                    else{
                        whatsapp.style.transform = 'rotate(-360deg)'; // One full rotation
                        whatsapp.style.width = '36px';
                        whatsapp.style.height = '36px';
                        whatsapp.style.top = 'calc(50% - 18px)';
                        whatsapp.style.left = 'calc(50% - 18px)';

                        fa_x.style.transform = 'rotate(360deg)'; // One full rotation
                        fa_x.style.width = '0px';
                        fa_x.style.height = '0px';
                        fa_x.style.top = '50%';
                        fa_x.style.left = '50%';
                    }
                }
            });
        }
    </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wsit-menu.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/mobile-menu-init.js')}}"></script>
        <script src="{{asset('public/frontEnd/js/wow.min.js')}}"></script>
        <script>
            new WOW().init();
        </script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

         <script>
        window.dataLayer = window.dataLayer || [];

        dataLayer.push({
         'event': 'Pageview',
         'pagePath': window.location.href,
         'pageTitle': document.title,
         'visitorType': 'customer'
        });
        </script>

        <!-- feather icon -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
        <script>
            feather.replace();
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {!! Toastr::message() !!} @stack('script')
        <script>
            $(window).scroll( function(){
                if($(window).scrollTop() > 2) {
                    $('#mainskiky').css('margin-top','0px');
                }else{
                    $('#mainskiky').css('margin-top','56px');
                }

            });
            $(".quick_view").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('quickview')}}",
                        success: function (data) {
                            if (data) {
                                $("#custom-modal").html(data);
                                $("#custom-modal").show();
                                $("#loading").hide();
                                $("#page-overlay").show();
                            }
                        },
                    });
                }
            });
        </script>
        <!-- quick view end -->
        <!-- cart js start -->
        <script>
            $(".addcartbutton").on("click", function () {
                var id = $(this).data("id");
                var qty = 1;
                if (id) {
                    $.ajax({
                        cache: "false",
                        type: "GET",
                        url: "{{url('add-to-cart')}}/" + id + "/" + qty,
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                toastr.success('Success', 'Product add to cart successfully');
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });
            $(".cart_store").on("click", function () {
                var id = $(this).data("id");
                var qty = $(this).parent().find("input").val();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id, qty: qty ? qty : 1 },
                        url: "{{route('cart.store')}}",
                        success: function (data) {
                            if (data) {
                                toastr.success('Success', 'Product add to cart succfully');
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_remove").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.remove')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart() + cart_summary();
                            }
                        },
                    });
                }
            });

            $(".cart_increment").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{ route('cart.increment') }}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);

                                cart_count();
                                mobile_cart();

                                setTimeout(function () {
                                    location.reload();
                                }, 300);
                            }
                        },
                    });
                }
            });


            $(".cart_decrement").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{ route('cart.decrement') }}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);

                                cart_count();
                                mobile_cart();

                                setTimeout(function () {
                                    location.reload();
                                }, 300);
                            }
                        },
                    });
                }
            });

            function cart_count() {
                $.ajax({
                    type: "GET",
                    url: "{{route('cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $("#cart-qty").html(data);
                        } else {
                            $("#cart-qty").empty();
                        }
                    },
                });
            }
            function mobile_cart() {
                $.ajax({
                    type: "GET",
                    url: "{{route('mobile.cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $(".mobilecart-qty").html(data);
                        } else {
                            $(".mobilecart-qty").empty();
                        }
                    },
                });
            }
            function cart_summary() {
                $.ajax({
                    type: "GET",
                    url: "{{route('shipping.charge')}}",
                    dataType: "html",
                    success: function (response) {
                        $(".cart-summary").html(response);
                    },
                });
            }
        </script>
        <!-- cart js end -->
        <script>
            $(".search_click").on("keyup change", function () {
                var keyword = $(".search_keyword").val();
                $.ajax({
                    type: "GET",
                    data: { keyword: keyword },
                    url: "{{route('livesearch')}}",
                    success: function (products) {
                        if (products) {
                            $(".search_result").html(products);
                        } else {
                            $(".search_result").empty();
                        }
                    },
                });
            });
            $(".msearch_click").on("keyup change", function () {
                var keyword = $(".msearch_keyword").val();
                $.ajax({
                    type: "GET",
                    data: { keyword: keyword },
                    url: "{{route('livesearch')}}",
                    success: function (products) {
                        if (products) {
                            $("#loading").hide();
                            $(".search_result").html(products);
                        } else {
                            $(".search_result").empty();
                        }
                    },
                });
            });
        </script>
        <!-- search js start -->
        <script>
            $(document).ready(function () {
                // Set up CSRF token globally for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
            function addTowishlist(id) {
                $.ajax({
                    url: "{{ route('addToWishlist') }}",
                    method: "POST",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        $('#wishlistCount').html(response.count);
                        toastr.success('Success', response.message);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseText);
                    }
                });
            }


            function remove_wishlist(id){
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('delete.wishlist')}}",
                    success: function (data)
                    {
                        if (data) {
                            $('#wishlistCount').html(data.count);
                            $('#item' + id).remove();
                            toastr.success('Success', 'Product remove from wishlist successfully');
                        }
                    },
                });
            }

            function cart_remove(id) {
                $.ajax({
                type: "GET",
                data: { id: id },
                url: "{{route('cart.remove')}}",
                success: function (data) {
                    $('#cart' + id).remove();
                    toastr.success('Success', 'Product removed from cart successfully');
                    loadCartItems();
                    cartCount();

                    // Check if the current URL contains "checkout"
                    if (window.location.href.includes("checkout")) {
                        location.reload(); // Reload the page
                    }
                },
            });
        }
        </script>
        <script>
            $(".district").on("change", function () {
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('districts')}}",
                    success: function (res) {
                        if (res) {
                            $(".area").empty();
                            $(".area").append('<option value="">Select..</option>');
                            $.each(res, function (key, value) {
                                $(".area").append('<option value="' + key + '" >' + value + "</option>");
                            });
                        } else {
                            $(".area").empty();
                        }
                    },
                });
            });
        </script>
        <script>
            $(".toggle").on("click", function () {
                $("#page-overlay").show();
                $(".mobile-menu").addClass("active");
            });

            $("#page-overlay").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
                $(".feature-products").removeClass("active");
            });

            $(".mobile-menu-close").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
            });

            $(".mobile-filter-toggle").on("click", function () {
                $("#page-overlay").show();
                $(".feature-products").addClass("active");
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".parent-category").each(function () {
                    const menuCatToggle = $(this).find(".menu-category-toggle");
                    const secondNav = $(this).find(".second-nav");

                    menuCatToggle.on("click", function () {
                        menuCatToggle.toggleClass("active");
                        secondNav.slideToggle("fast");
                        $(this).closest(".parent-category").toggleClass("active");
                    });
                });
                $(".parent-subcategory").each(function () {
                    const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                    const thirdNav = $(this).find(".third-nav");

                    menuSubcatToggle.on("click", function () {
                        menuSubcatToggle.toggleClass("active");
                        thirdNav.slideToggle("fast");
                        $(this).closest(".parent-subcategory").toggleClass("active");
                    });
                });
            });
        </script>

        <script>
            var menu = new MmenuLight(document.querySelector("#menu"), "all");

            var navigator = menu.navigation({
                selectedClass: "Selected",
                slidingSubmenus: true,
                // theme: 'dark',
                title: "ক্যাটাগরি",
            });

            var drawer = menu.offcanvas({
                // position: 'left'
            });

            //  Open the menu.
            document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
                evnt.preventDefault();
                drawer.open();
            });
        </script>

        <script>

            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $(".scrolltop:hidden").stop(true, true).fadeIn();
                } else {
                    $(".scrolltop").stop(true, true).fadeOut();
                }
            });
            $(function () {
                $(".scroll").click(function () {
                    $("html,body").animate({ scrollTop: $(".gotop").offset().top }, "1000");
                    return false;
                });
            });
        </script>
        <script>
            $(".filter_btn").click(function(){
               $(".filter_sidebar").addClass('active');
               $("body").css("overflow-y", "hidden");
            })
            $(".filter_close").click(function(){
               $(".filter_sidebar").removeClass('active');
               $("body").css("overflow-y", "auto");
            })
        </script>

<script>
  window.addEventListener('load', function() {
    const inputs = document.querySelectorAll('input[type="text"]');

    inputs.forEach(input => {
      const originalPlaceholder = input.placeholder;

      input.addEventListener('focus', () => {
        input.placeholder = '';
      });

      input.addEventListener('blur', () => {
        input.placeholder = originalPlaceholder;
      });
    });
  });
</script>
    </body>
</html>
