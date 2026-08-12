<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Print</title>
    <link rel="stylesheet" href="{{asset('public/frontEnd/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('public/frontEnd/css/all.min.css')}}" />
</head>
<body>
    <style>
        .customer-invoice {
            page-break-before: always;
        }
        body{
            background:#F1F2F5
        }
        .customer-invoice {
            margin: 25px 0;
        }
        .invoice_btn{
            margin-bottom: 15px;
        }
        p{
            margin:0;
        }
        td{
            font-size: 14px;
        }
        
        /* Invoice Container Styling */
        .invoice-innter {
            width: 760px;
            margin: 0 auto;
            background: #fff;
            overflow: hidden;
            padding: 40px;
            padding-top: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        /* Header Section */
        .invoice-header-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .logo-section {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .logo-section img {
            width: 180px;
            height: auto;
        }
        
        .invoice-title-center {
            text-align: center;
            margin: 20px 0 30px 0;
            /*border-bottom: 2px solid #333;*/
            padding-bottom: 5px;
        }
        
        .invoice-title-center h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
            letter-spacing: 2px;
        }
        
        /* Invoice Meta Information */
        .invoice-meta-info {
            text-align: right;
            margin-bottom: 25px;
        }
        
        .invoice-meta-info p {
            font-size: 13px;
            line-height: 1.6;
            color: #333;
            margin: 3px 0;
        }
        
        /* Address Section */
        .address-container {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .address-left {
            display: table-cell;
            width: 75%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .address-right {
            display: table-cell;
            width: 25%;
            vertical-align: top;
            padding-left: 20px;
        }
        
        .address-box p {
            font-size: 13px;
            line-height: 1.4;
            color: #333;
            /*margin: 2px 0;*/
        }
        
        .address-box p strong {
            font-weight: 600;
        }
        
        .address-box .label {
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }
        
        /* Table Styling */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border: 1px solid #333;
        }
        
        .invoice-table thead {
            background: #fff;
            border-bottom: 2px solid #333;
        }
        
        .invoice-table thead th {
            padding: 10px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            color: #000;
            border: 1px solid #333;
        }
        
        .invoice-table tbody td {
            padding: 10px;
            font-size: 13px;
            color: #333;
            border: 1px solid #333;
            text-align: center;
            /*vertical-align: top;*/
        }
        
        .invoice-table tbody td:nth-child(2) {
            text-align: left;
        }
        
        .invoice-table tbody td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            vertical-align: middle;
            margin-right: 8px;
        }
        
        /* Summary Section */
        .invoice-summary {
            margin-top: 0;
            margin-bottom: 30px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table tr {
            border: none;
        }
        
        .summary-table td {
            padding: 8px 10px;
            font-size: 13px;
            text-align: right;
            border: 1px solid #333;
        }
        
        .summary-table td:first-child {
            font-weight: 600;
            color: #000;
            text-align: right;
            width: 70%;
        }
        
        .summary-table td:last-child {
            font-weight: 600;
            color: #333;
            width: 30%;
        }
        
        .summary-table tr:last-child td {
            /*background: #f5f5f5;*/
            /*font-weight: bold;*/
        }
        
        /* Payment Mode Section */
        .payment-mode-section {
            display: flex;
            gap:20px;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .payment-left {
            background:#E0E0E0;
            width: 50%;
            vertical-align: top;
            border: 1px solid #333;
            padding: 8px 10px;
        }
        
        .payment-right {
            width: 50%;
            vertical-align: top;
            border: 1px solid #333;
            padding: 8px 10px;
            background:#E0E0E0;
        }
        
        .payment-left p,
        .payment-right p {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: #000;
        }
        
        /* Footer Section */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
        }
        
        .footer-note {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .footer-note p {
            font-size: 13px;
            line-height: 1.8;
            color: #333;
            margin: 5px 0;
        }
        
        .footer-note p:first-child {
            font-weight: 600;
            color: #000;
        }
        
        .footer-signature {
            text-align: center;
            margin-top: 20px;
        }
        
        .footer-signature p {
            font-size: 12px;
            color: #333;
            margin: 3px 0;
        }
        
        .footer-line {
            border-top: 1px solid #000;
            width: 100%;
            margin: 40px 0 10px 0;
        }

        @page {
            margin: 0px;
        }
        
        @media print {
            body {
                background: #fff;
            }
            .invoice-innter {
                margin-left: 0 !important;
                border: none;
                box-shadow: none;
            }
            .invoice_btn {
                margin-bottom: 0 !important;
            }
            td {
                font-size: 13px;
            }
            p {
                margin: 0;
            }
            header, footer, .no-print, .left-side-menu, .navbar-custom {
                display: none !important;
            }
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-3 text-center">
                <button onclick="printFunction()" class="no-print btn btn-xs btn-success waves-effect waves-light">
                    <i class="fa fa-print"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>

    @foreach($orders as $order)
    <section class="customer-invoice">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 mt-3">
                    <div class="invoice-innter">
                        
                        <!-- Logo Section -->
                        <div class="logo-section">
                            <img src="{{asset($generalsetting->dark_logo)}}" alt="Company Logo">
                        </div>
                        
                        <!-- Invoice Title -->
                        <div class="invoice-title-center">
                            <h2>INVOICE</h2>
                        </div>
                        
                        <!-- Invoice Meta Info -->
                        <div class="invoice-meta-info">
                            <p><strong>Invoice No :</strong> {{$order->invoice_id}}</p>
                            <p><strong>Invoice Date:</strong> {{$order->created_at->format('M d, Y')}}</p>
                        </div>
                        
                        <!-- Address Section -->
                        <div class="address-container">
                            <div class="address-left">
                                <div class="address-box">
                                    <p class="label">To:</p>
                                    <p><strong>{{$order->shipping?$order->shipping->name:''}}</strong></p>
                                    <p>{{$order->shipping?$order->shipping->phone:''}}</p>
                                    <p><strong>Delivery Address:</strong></p>
                                    <p>{{ implode(', ', array_filter([
                                        $order->shipping?->address,
                                        $order->shipping?->city,
                                        $order->shipping?->district
                                    ])) }}</p>
                                    <!--<p>{{$order->shipping?$order->shipping->area:''}}</p>-->
                                </div>
                            </div>
                            <div class="address-right">
                                <div class="address-box">
                                    <p class="label">From:</p>
                                    <p><strong>{{$generalsetting->name}}</strong></p>
                                    <p>{{$contact->phone}}</p>
                                    <p>{{$contact->address}}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products Table -->
                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">SL</th>
                                    <th style="width: 35%;">Product Description</th>
                                    <th style="width: 12%;">Regular<br>Price</th>
                                    <th style="width: 12%;">Discount</th>
                                    <th style="width: 12%;">Price</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 11%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderdetails as $key=>$value)
                                @php
                                    $product = App\Models\Productsize::where('product_id', $value->product_id)->where('size', $value->product_size)->first();
                                    $discount = $value->regular_price - $value->sale_price;
                                @endphp
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td style="text-align: left;display:flex;gap:2px;">
                                        <!--@if($value->product_color == null)-->
                                        <!--    <img src="{{asset(App\Models\Productimage::where('product_id',$value->product_id)->first()->image)}}" alt="Product">-->
                                        <!--@else-->
                                        <!--    <img src="{{asset(App\Models\Productcolor::where('product_id',$value->product_id)->where('color', $value->product_color)->first()->Image)}}" alt="Product">-->
                                        <!--@endif-->
                                         <img src="{{asset($value->product_color_image)}}" alt="Product">
                                        <p class="m-0">
                                            {{$value->product_name}} <br>
                                            ( {{ $value->product_color }}, {{ $value->product_size }} )
                                        </p>
                                    </td>
                                    <td>৳{{number_format($value->regular_price, 0)}}</td>
                                    <td>৳{{number_format($discount, 0)}}</td>
                                    <td>৳{{number_format($value->sale_price, 0)}}</td>
                                    <td>{{$value->qty}}</td>
                                    <td>৳{{number_format($value->sale_price * $value->qty, 0)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Summary Section -->
                        <div class="invoice-summary">
                            <table class="summary-table">
                                <tr>
                                    <td>Sub-Total:</td>
                                    <td>৳{{number_format($order->orderdetails->sum(function($item) { return $item->sale_price * $item->qty; }), 0)}}</td>
                                </tr>
                                <!--<tr>-->
                                <!--    <td>Regular Price:</td>-->
                                <!--    <td>৳{{number_format($order->orderdetails->sum(function($item) { return $item->regular_price * $item->qty; }), 0)}}</td>-->
                                <!--</tr>-->
                                <tr>
                                    <td>Coupon Discount:</td>
                                    <td>- ৳{{number_format($order->coupon_discount, 0)}}</td>
                                </tr>
                                <tr>
                                    <td>Shipping Charge:</td>
                                    <td>৳{{number_format($order->shipping_charge, 0)}}</td>
                                </tr>
                                @if($order->cod_charge)
                                <tr>
                                    <td>COD/MFS charge:</td>
                                    <td>৳{{number_format($order->cod_charge, 0)}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Total:</td>
                                    <td>৳{{number_format($order->amount , 0)}}</td>
                                </tr>
                                <tr>
                                    <td>Paid:</td>
                                    <td>৳{{number_format($order->paid_partial_payment_amount ?? 0, 0)}}</td>
                                </tr>
                                <!--<tr>-->
                                <!--    <td>Amount To Be Collected:</td>-->
                                <!--    <td>৳{{number_format($order->amount, 0)}}</td>-->
                                <!--</tr>-->
                            </table>
                        </div>
                        
                        <!-- Payment Mode -->
                        <div class="payment-mode-section">
                            <div class="payment-left">
                                <p>Payment Method: {{$order->payment?strtoupper($order->payment->payment_method):''}}</p>
                            </div>
                            <div class="payment-right">
                                <p style="text-align: right;">Due: <span style="margin-left:30px;">৳{{number_format($order->amount - $order->paid_partial_payment_amount, 0)}}</span></p>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="invoice-footer">
                            <div class="footer-note">
                                <p>Dear {{$order->shipping?$order->shipping->name:'Customer'}}, thank you for shopping with us!</p>
                                <p style="margin-top: 10px;">If any items are missing or damaged, return the package to your delivery agent or submit a claim with an unpacking Proof on our website 
                                    <a href="http://getgoods.com.bd/">http://getgoods.com.bd/</a> within 24 hours. Claims after 24 hours cannot be processed.
                                </p>
                            </div>
                            
                            <div class="footer-signature">
                                <p>Sincerely,</p>
                                <p><strong>{{$generalsetting->name}} Team</strong></p>
                            </div>
                            
                            <div class="footer-line"></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach

    <script>
        function printFunction() {
            window.print();
        }
    </script>
</body>
</html>