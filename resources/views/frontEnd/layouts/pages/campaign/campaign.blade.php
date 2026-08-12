<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $generalsetting->name }}</title>
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" type="image/x-icon" />
    <!-- fot awesome -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/all.css" />
    <!-- core css -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/bootstrap.min.css" />

    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/animate.css" />
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/owl.theme.default.css" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/owl.carousel.min.css" />
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/select2.min.css" />
    <!-- common css -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/style.css" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/responsive.css" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/campaign/css') }}/landing.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: "Roboto", sans-serif;
        }
    </style>

    @foreach($pixels as $pixel)
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{{$pixel->code}}}');
    fbq('track', 'PageView');
    </script>
    <noscript>
    <img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{{$pixel->code}}}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    @endforeach


    <meta name="app-url" content="{{ route('campaign', $campaign_data->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $campaign_data->description }}" />
    <meta name="keywords" content="{{ $campaign_data->slug }}" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="{{ $campaign_data->name }}" />
    <meta name="twitter:title" content="{{ $campaign_data->name }}" />
    <meta name="twitter:description" content="{{ $campaign_data->description }}" />
    <meta name="twitter:creator" content="hellodinajpur.com" />
    <meta property="og:url" content="{{ route('campaign', $campaign_data->slug) }}" />
    <meta name="twitter:image" content="{{ asset($campaign_data->image_one) }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $campaign_data->name }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('campaign', $campaign_data->slug) }}" />
    <meta property="og:image" content="{{ asset($campaign_data->image_one) }}" />
    <meta property="og:description" content="{{ $campaign_data->description }}" />
    <meta property="og:site_name" content="{{ $campaign_data->name }}" />
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section>
        <div class="container">
            {{-- logo --}}
            <div class="text-center mt-3">
                <a href="#">
                    <img src="{{ asset($generalsetting->dark_logo) }}" alt="" width="300">
                </a>
            </div>
            <div>
                <p style="font-size: 28px;margin: 10px 0;text-align: center;">{{ $campaign_data->name }}</p>
            </div>

            {{-- banner --}}
            <style>
                .owl-nav {
                    position: absolute;
                    top: 35%;
                    width: 100%;
                    display: flex;
                    justify-content: space-between;
                }

                .owl-nav button {
                    background: #fff !important;
                    border-radius: 50%;
                    border: 1px solid #ccc !important;
                    width: 40px;
                    height: 40px;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                }

                .owl-nav button span {
                    font-size: 24px;
                    color: #000;
                }

                .owl-dots {
                    display: flex !important;
                    justify-content: center;
                    margin-top: 10px;
                    gap: 6px;
                }

                .owl-dot span {
                    width: 5px;
                    height: 5px;
                    background: #bbb;
                    display: inline-block;
                    border-radius: 50%;
                    transition: background 0.3s;
                }

                .owl-dot.active span {
                    background: #000;
                }
            </style>

            <div class="col-lg-12 col-md-12 col-12 p-0">
                <div class="home-slider-container">
                    <div class="landing_slider owl-carousel">
                        @foreach (App\Models\LandingBanner::where('campaign_id',$campaign_data->id)->get() as $key => $value)
                        <div class="slider-item">
                            <img class="img-fluid" src="{{ asset($value->image) }}" alt="" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center my-4">
            <a href="#order_form" class="btn btn-order">এখনই অর্ডার করুন</a>
        </div>

        {{-- faq --}}
        <div class="container" style="background: #018241;padding:10px;border-radius: 10px">
            <div class="faq-header text-white pt-3">FAQ:</div>

            <div class="accordion" id="faqAccordion">
                @if ($campaign_data->faq_question_one)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="fas fa-chevron-right faq-icon"></i>
                                {{ $campaign_data->faq_question_one }}
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $campaign_data->faq_answar_one !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($campaign_data->faq_question_two)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-chevron-right faq-icon"></i>
                                {{ $campaign_data->faq_question_two }}
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $campaign_data->faq_answar_two !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($campaign_data->faq_question_three)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-chevron-right faq-icon"></i>
                                {{ $campaign_data->faq_question_three }}
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $campaign_data->faq_answar_three !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($campaign_data->faq_question_four)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-chevron-right faq-icon"></i>
                                {{ $campaign_data->faq_question_four }}
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $campaign_data->faq_answar_four !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if ($campaign_data->faq_question_five)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="fas fa-chevron-right faq-icon"></i>
                                {{ $campaign_data->faq_question_five }}
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! $campaign_data->faq_answar_five !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- video --}}
        @if ($campaign_data->video)
            <div class="container pt-5">
                <div class="row">
                    <div class="col-12">
                        <iframe width="100%" class="landing_youtube" height="315"
                            src="https://www.youtube.com/embed/{{ $campaign_data->video }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        @endif

        {{-- pricing --}}
        <div class="container py-5">
            <h2 class="pricing-title">Pricing:</h2>
            <div class="row">
                <div class="col-12 col-md-4 pricing_mobile_margin">
                    <div class="pricing-card">
                        <img src="{{ asset($campaign_data->image_one) }}" alt="Product 1">
                    </div>
                </div>
                <div class="col-12 col-md-4 pricing_mobile_margin">
                    <div class="pricing-card">
                        <img src="{{ asset($campaign_data->image_two) }}" alt="Product 2">
                    </div>
                </div>
                <div class="col-12 col-md-4 pricing_mobile_margin">
                    <div class="pricing-card">
                        <img src="{{ asset($campaign_data->image_three) }}" alt="Product 3">
                    </div>
                </div>
            </div>
        </div>

        {{-- reviews --}}
        <div class="container">
            <div class="col-lg-12 col-md-12 col-12 p-0">
                <h3 class="text-center mb-3 fw-bold">Reviews:</h3>
                <div class="home-slider-container">
                    <div class="landing_review_slider owl-carousel">
                        @foreach (App\Models\CampaignReview::where('campaign_id',$campaign_data->id)->get() as $key => $value)
                        <div class="slider-item">
                            <img class="img-fluid"
                                src="{{ asset($value->image) }}"
                                alt="" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- order --}}
        <form action="{{ route('landingpage.ordersave') }}" method="POST" id="order_form">
            @csrf
            <div class="container mx-auto my-5">
                <div style="border: 1px solid #000; border-radius: 10px;">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="my-4 text-center fw-bold">অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি সম্পূর্ণ
                                পূরন করুন।</h3>
                        </div>
                        <div class="col-12 col-md-6 px-4 mb-4">
                            <h4 class="mb-4">Billing Details</h4>

                            <div class="mb-3">
                                <label class="form-label">আপনার নাম লিখুন *</label>
                                <input type="text" class="form-control" placeholder="" name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">আপনার ঠিকানা লিখুন *</label>
                                <input type="text" class="form-control" placeholder="বাড়ি নং, রোড নং, থানা, জেলা"
                                    name="address">
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">আপনার মোবাইল নাম্বারটি লিখুন *</label>
                                <input type="text" class="form-control" placeholder="" name="phone">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <h5 class="mt-4 mb-3">Shipping</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        @foreach ($shippingcharge as $key => $value)
                                            <tr>
                                                <td style="width: 80%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="shipping" id="shipping_{{ $key }}"
                                                            data-amount="{{ $value->amount }}"
                                                            {{ $loop->first ? 'checked' : '' }}
                                                            value="{{ $value->amount }}">
                                                        <label class="form-check-label"
                                                            for="shipping_{{ $key }}">
                                                            {{ $value->name }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-end">৳ {{ $value->amount }}</td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>



                            {{-- select package --}}

                            @if ($product->type == 1)
                                <div class="container py-5">
                                <h5 class="fw-bold mb-4">কোন প্যাকেজটি নিতে চান সিলেক্ট করুন</h5>
                            
                                <!-- Color নির্বাচন -->
                                <div class="my-3">
                                    @foreach ($productcolors as $key => $value)
                                        <div class="form-check d-inline-block me-3 mb-2">
                                            <label class="form-check-label d-flex align-items-center" style="cursor: pointer;">
                                                
                                                <input class="form-check-input me-2 mb-2 color-radio"
                                                    type="radio"
                                                    name="color"
                                                    value="{{ $value->color_id }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                            
                                                {{ $value->color }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            
                                <!-- Size / Package -->
                                @foreach ($productsizes as $key => $value)
                                    <div class="form-check size-item" data-color="{{ $value->color_id }}">
                                        
                                        <input class="form-check-input"
                                            type="radio"
                                            name="package"
                                            id="package_{{ $key }}"
                                            value="{{ $value->size }}"
                                            data-price="{{ $value->SalePrice }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                            
                                        <label class="form-check-label w-100" for="package_{{ $key }}">
                                            <div class="package-card">
                                                
                                                <div class="package-label">
                                                    {{ $product->name }} – {{ $value->size }}
                                                </div>
                            
                                                <small class="text-muted d-block mb-2">
                                                    (ঢাকার ভিতর {{ $shippingcharge->first()->amount }} টাকা,
                                                    ঢাকার বাইরে {{ $shippingcharge->last()->amount }})
                                                </small>
                            
                                                <div class="price">৳ {{ $value->SalePrice }}</div>
                            
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <script>
                            document.addEventListener("DOMContentLoaded", function () {
                            
                                const colorRadios = document.querySelectorAll('.color-radio');
                                const sizeItems = document.querySelectorAll('.size-item');
                            
                                function filterSizes(colorId) {
                            
                                    let firstVisibleRadio = null;
                            
                                    sizeItems.forEach(item => {
                                        if (item.getAttribute('data-color') == colorId) {
                                            item.style.display = '';
                            
                                            // প্রথম visible radio detect
                                            if (!firstVisibleRadio) {
                                                firstVisibleRadio = item.querySelector('input[type="radio"]');
                                            }
                            
                                        } else {
                                            item.style.display = 'none';
                                        }
                                    });
                            
                                    if (firstVisibleRadio) {
                                        firstVisibleRadio.checked = true;
                            
                                        firstVisibleRadio.dispatchEvent(new Event('change'));
                                    }
                                }
                            
                                const checkedColor = document.querySelector('.color-radio:checked');
                                if (checkedColor) {
                                    filterSizes(checkedColor.value);
                                }
                            
                                // on color change
                                colorRadios.forEach(radio => {
                                    radio.addEventListener('change', function () {
                                        filterSizes(this.value);
                                    });
                                });
                            
                            });
                            </script>
                            @elseif ($product->type == 0)
                                <div class="container py-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="package"
                                            id="package_single" value="{{ $product->id }}"
                                            data-price="{{ $product->new_price }}" checked>

                                        <label class="form-check-label w-100" for="package_single">
                                            <div class="package-card">
                                                <div class="package-label">{{ $product->name }} </div>
                                                <small class="text-muted d-block mb-2">
                                                    (ঢাকার ভিতর {{ $shippingcharge->first()->amount }} টাকা, ঢাকার
                                                    বাইরে {{ $shippingcharge->last()->amount }} টাকা ডেলিভারি চার্জ যোগ
                                                    হবে)
                                                </small>
                                                <div class="price">৳ {{ $product->new_price }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            @endif
                        </div>

                        <!-- Your Order -->
                        @php
                            // $defaultPackage = $productsizes[0];
                            // $defaultShipping = $shippingcharge[0];

                            $defaultPackage =
                                $product->type == 1 && isset($productsizes[0])
                                    ? $productsizes[0]
                                    : (object) ['SalePrice' => $product->new_price];
                            $defaultShipping = isset($shippingcharge[0])
                                ? $shippingcharge[0]
                                : (object) ['amount' => 0];
                        @endphp

                        <div class="col-12 col-md-6 px-4">
                            <h4 class="mb-4">Your Order</h4>

                            <div class="order-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Product</strong>
                                    <strong>Subtotal</strong>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $productImage = App\Models\ProductImage::where(
                                                'product_id',
                                                $product->id,
                                            )->first();
                                        @endphp
                                        <img src="{{ asset($productImage->image) }}" class="product-img me-2"
                                            alt="" style="width: 50px;">
                                        <div>
                                            {{ $product->name }}
                                            <br>
                                            {{-- Quantity buttons --}}
                                            <div class="input-group mt-1" style="width: 120px;">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    id="decreaseQty">−</button>
                                                <input type="text" class="form-control form-control-sm text-center"
                                                    value="1" id="quantity" name="quantity" readonly>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    id="increaseQty">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                                    <input type="hidden" name="product_price" id="inputProductPrice"
                                        value="{{ $defaultPackage->SalePrice }}">
                                    <input type="hidden" name="subtotal" id="inputSubtotal"
                                        value="{{ $defaultPackage->SalePrice }}">
                                    <input type="hidden" name="total" id="inputTotal"
                                        value="{{ $defaultPackage->SalePrice + $defaultShipping->amount }}">

                                    <div>৳ <span id="productPrice">{{ $defaultPackage->SalePrice }}</span></div>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Subtotal</strong>
                                    <strong>৳ <span id="subtotal">{{ $defaultPackage->SalePrice }}</span></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Shipping Charge</strong>
                                    <strong>৳ <span id="shippingCharge">{{ $defaultShipping->amount }}</span></strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total</strong>
                                    <strong>৳ <span
                                            id="total">{{ $defaultPackage->SalePrice + $defaultShipping->amount }}</span></strong>
                                </div>

                                <div class="bg-light p-3 rounded mb-3">
                                    <strong>ক্যাশ অন ডেলিভারি</strong>
                                    <p class="mb-0 mt-2">পণ্য হাতে পেয়ে ডেলিভারি ম্যানকে পেমেন্ট করতে পারবেন।</p>
                                </div>

                                <button class="place-order-btn btn btn-dark w-100 fw-bold">
                                    <i class="bi bi-lock"></i> PLACE ORDER ৳ <span
                                        id="btnTotal">{{ $defaultPackage->SalePrice + $defaultShipping->amount }}</span>
                                </button>
                            </div>
                        </div>


                        {{-- Hidden Data Script --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let packageRadios = document.querySelectorAll('input[name="package"]');
                                let shippingRadios = document.querySelectorAll('input[name="shipping"]');
                                let productPriceSpan = document.getElementById('productPrice');
                                let subtotalSpan = document.getElementById('subtotal');
                                let shippingSpan = document.getElementById('shippingCharge');
                                let totalSpan = document.getElementById('total');
                                let btnTotalSpan = document.getElementById('btnTotal');
                                let quantityInput = document.getElementById('quantity');
                                let btnIncrease = document.getElementById('increaseQty');
                                let btnDecrease = document.getElementById('decreaseQty');


                                function getSelectedPackagePrice() {
                                    let selected = document.querySelector('input[name="package"]:checked');
                                    return parseFloat(selected?.dataset.price || 0);
                                }

                                function getSelectedShippingCharge() {
                                    let selected = document.querySelector('input[name="shipping"]:checked');
                                    return parseFloat(selected?.dataset.amount || 0);
                                }

                                function getQuantity() {
                                    return parseInt(quantityInput.value) || 1;
                                }

                                function updateTotals() {
                                    let unitPrice = getSelectedPackagePrice();
                                    let qty = getQuantity();
                                    let subtotal = unitPrice * qty;
                                    let shipping = getSelectedShippingCharge();
                                    let total = subtotal + shipping;

                                    productPriceSpan.textContent = subtotal;
                                    subtotalSpan.textContent = subtotal;
                                    shippingSpan.textContent = shipping;
                                    totalSpan.textContent = total;
                                    btnTotalSpan.textContent = total;

                                    document.getElementById('inputProductPrice').value = unitPrice;
                                    document.getElementById('inputSubtotal').value = subtotal;
                                    document.getElementById('inputTotal').value = total;
                                }

                                // Event listeners
                                packageRadios.forEach(radio => {
                                    radio.addEventListener('change', updateTotals);
                                });

                                shippingRadios.forEach(radio => {
                                    radio.addEventListener('change', updateTotals);
                                });

                                btnIncrease.addEventListener('click', function() {
                                    let qty = getQuantity();
                                    quantityInput.value = qty + 1;
                                    updateTotals();
                                });

                                btnDecrease.addEventListener('click', function() {
                                    let qty = getQuantity();
                                    if (qty > 1) {
                                        quantityInput.value = qty - 1;
                                        updateTotals();
                                    }
                                });

                                // Initial calculation
                                updateTotals();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </form>
    </section>

    {{-- call and whatsapp --}}
    <div>
        <a href="tel:{{ $contact->phone }}" target="_blank"
            style="position: fixed; bottom: 130px; right: 20px; z-index: 111;">
            <svg width="39" height="39" viewBox="0 0 39 39" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="rgb(3, 35, 231)">
                </circle>
                <path
                    d="M19.3929 14.9176C17.752 14.7684 16.2602 14.3209 14.7684 13.7242C14.0226 13.4259 13.1275 13.7242 12.8292 14.4701L11.7849 16.2602C8.65222 14.6193 6.11623 11.9341 4.47529 8.95057L6.41458 7.90634C7.16046 7.60799 7.45881 6.71293 7.16046 5.96705C6.56375 4.47529 6.11623 2.83435 5.96705 1.34259C5.96705 0.596704 5.22117 0 4.47529 0H0.745882C0.298353 0 0 0.298352 0 0.745881C0 3.72941 0.596704 6.71293 1.93929 9.3981C3.87858 13.575 7.30964 16.8569 11.3374 18.7962C14.0226 20.1388 17.0061 20.7355 19.9896 20.7355C20.4371 20.7355 20.7355 20.4371 20.7355 19.9896V16.4094C20.7355 15.5143 20.1388 14.9176 19.3929 14.9176Z"
                    transform="translate(9.07179 9.07178)" fill="white"></path>
            </svg>
        </a>

        <a href="https://wa.me/+88{{ $contact->phone }}" target="_blank"
            style="position: fixed;bottom: 70px;right: 20px;z-index:111">
            <svg width="39" height="39" viewBox="0 0 39 39" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="#49E670"></circle>
                <path
                    d="M12.9821 10.1115C12.7029 10.7767 11.5862 11.442 10.7486 11.575C10.1902 11.7081 9.35269 11.8411 6.84003 10.7767C3.48981 9.44628 1.39593 6.25317 1.25634 6.12012C1.11674 5.85403 2.13001e-06 4.39053 2.13001e-06 2.92702C2.13001e-06 1.46351 0.83755 0.665231 1.11673 0.399139C1.39592 0.133046 1.8147 1.01506e-06 2.23348 1.01506e-06C2.37307 1.01506e-06 2.51267 1.01506e-06 2.65226 1.01506e-06C2.93144 1.01506e-06 3.21063 -2.02219e-06 3.35022 0.532183C3.62941 1.19741 4.32736 2.66092 4.32736 2.79397C4.46696 2.92702 4.46696 3.19311 4.32736 3.32616C4.18777 3.59225 4.18777 3.59224 3.90858 3.85834C3.76899 3.99138 3.6294 4.12443 3.48981 4.39052C3.35022 4.52357 3.21063 4.78966 3.35022 5.05576C3.48981 5.32185 4.18777 6.38622 5.16491 7.18449C6.42125 8.24886 7.39839 8.51496 7.81717 8.78105C8.09636 8.91409 8.37554 8.9141 8.65472 8.648C8.93391 8.38191 9.21309 7.98277 9.49228 7.58363C9.77146 7.31754 10.0507 7.1845 10.3298 7.31754C10.609 7.45059 12.2841 8.11582 12.5633 8.38191C12.8425 8.51496 13.1217 8.648 13.1217 8.78105C13.1217 8.78105 13.1217 9.44628 12.9821 10.1115Z"
                    transform="translate(12.9597 12.9597)" fill="#FAFAFA"></path>
                <path
                    d="M0.196998 23.295L0.131434 23.4862L0.323216 23.4223L5.52771 21.6875C7.4273 22.8471 9.47325 23.4274 11.6637 23.4274C18.134 23.4274 23.4274 18.134 23.4274 11.6637C23.4274 5.19344 18.134 -0.1 11.6637 -0.1C5.19344 -0.1 -0.1 5.19344 -0.1 11.6637C-0.1 13.9996 0.624492 16.3352 1.93021 18.2398L0.196998 23.295ZM5.87658 19.8847L5.84025 19.8665L5.80154 19.8788L2.78138 20.8398L3.73978 17.9646L3.75932 17.906L3.71562 17.8623L3.43104 17.5777C2.27704 15.8437 1.55796 13.8245 1.55796 11.6637C1.55796 6.03288 6.03288 1.55796 11.6637 1.55796C17.2945 1.55796 21.7695 6.03288 21.7695 11.6637C21.7695 17.2945 17.2945 21.7695 11.6637 21.7695C9.64222 21.7695 7.76778 21.1921 6.18227 20.039L6.17557 20.0342L6.16817 20.0305L5.87658 19.8847Z"
                    transform="translate(7.7758 7.77582)" fill="white" stroke="white" stroke-width="0.2"></path>
            </svg>
        </a>
    </div>

    <script src="{{ asset('public/frontEnd/campaign/js') }}/jquery-2.1.4.min.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/all.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/bootstrap.min.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/owl.carousel.min.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/select2.min.js"></script>
    <script src="{{ asset('public/frontEnd/campaign/js') }}/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- bootstrap js -->
    <script>
        $(document).ready(function() {
            $(".landing_slider").owlCarousel({
                items: 1,
                loop: true,
                dots: true,
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

            $(".landing_review_slider").owlCarousel({
                margin: 20,
                items: 3,
                loop: true,
                dots: true,
                autoplay: true,
                nav: true,
                autoplayHoverPause: false,
                mouseDrag: true,
                smartSpeed: 1500,
                autoplayTimeout: 4000,

                navText: ["<i class='fa-solid fa-angle-left'></i>",
                    "<i class='fa-solid fa-angle-right'></i>"
                ],
            });

            $(".owl-carousel").owlCarousel({
                margin: 15,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                items: 1,
            });
            $('.owl-nav').remove();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        $("#area").on("change", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $('.cartlist').html(response);
                }
            });
        });
    </script>
    <script>
        $(".cart_remove").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart() + cart_summary();
                        }
                    },
                });
            }
        });
        $(".cart_increment").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.increment') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });
    </script>
    <script>
        $('.review_slider').owlCarousel({
            dots: false,
            arrow: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            mouseDrag: true,
            touchDrag: true,
            items: 6,
            responsiveClass: true,
            responsive: {
                300: {
                    items: 1,
                },
                480: {
                    items: 2,
                },
                768: {
                    items: 5,
                },
                1170: {
                    items: 5,
                },
            }
        });
    </script>

    <script>
        $('.campro_img_slider').owlCarousel({
            dots: false,
            arrow: false,
            autoplay: true,
            loop: true,
            margin: 10,
            smartSpeed: 1000,
            mouseDrag: true,
            touchDrag: true,
            items: 3,
            responsiveClass: true,
            responsive: {
                300: {
                    items: 1,
                },
                480: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                1170: {
                    items: 3,
                },
            }
        });
    </script>
</body>

</html>
