@extends('frontEnd.layouts.master')
@section('title', 'Wishlist')
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}" />
@endpush
@section('content')

    <style>
        #content {
            width: 100%;
            padding-top: 64px !important;
        }
        .wishlist-product {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
        @media only screen and (min-width: 320px) and (max-width: 767px) {
            .main_product_inner {
                grid-template-columns: 1fr 1fr !important;
            }
        }
    </style>

    <section class="product-section">
        <div class="container">
            <div class="sorting-section my-2">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>My Wishlist</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="wishlist-product main_product_inner">
                        @forelse (Cart::instance('wishlist')->content() as $key => $value)
                            <div class="product_item wist_item  wow fadeInDown" data-wow-duration="1.5s"
                                data-wow-delay="0.{{ $key }}s">
                                <div class="product_item_inner">
                                    @if ($value->old_price)
                                        <div class="sale-badge">
                                            <div class="sale-badge-inner">
                                                <div class="sale-badge-box">
                                                    <span class="sale-badge-text">
                                                        <p> @php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="pro_img">
                                        <a href="{{ route('product', $value->options->slug) }}">
                                            <img src="{{ asset($value->options->image) }}"
                                                alt="{{ $value->name }}" />
                                        </a>

                                    </div>
                                    <div class="pro_des">
                                        <div class="pro_name">
                                            <a
                                                href="{{ route('product', $value->options->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                        </div>
                                        <div class="pro_price">
                                            <p>
                                                <!--<del>৳ {{ $value->old_price }}</del>-->
                                                ৳ {{ $value->price }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="pro_btn my-1">
                                    <button type="button" onclick="remove_wishlist('{{ $value->rowId }}')" class="bg-danger">REMOVE</button>
                                </div>

                                {{-- <div class="pro_btn my-1">
                                    <button type="button" onclick="moveToCart('{{ $value->id }}' , '{{ $value->rowId }}')">ADD
                                        TO CART</button>
                                </div> --}}

                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
@endpush
