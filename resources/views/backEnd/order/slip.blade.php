<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Print</title>
    <link rel="stylesheet" href="{{asset('public/frontEnd/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('public/frontEnd/css/all.min.css')}}" />
    
    <style>
body{
    font-family: Arial, sans-serif;
}

.page{
    width: 210mm;
    margin: auto;
}

.label-grid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:10px;
}

.customer-invoice{
    border:1px solid #ddd;
    padding:6px 8px;
    width:288px;
    height: 384px;
    position:relative;
}

.logo-section {
    text-align: center;
    margin-bottom: 10px;
    margin-top:3px;
}

.logo-section img {
    width: 125px;
    height: auto;
}
.logo-section p {
    font-size: 15px;
    margin-bottom:4px;
}

.logo-section .company-name {
    margin:4px 0;
}



.invoice-row{
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-top:5px;
}

.barcode{
    display:flex;
    align-items:center;
    justify-content:center;
    margin:5px 0;
}

.address{
    font-size:13px;
    
}

.bottom{
    display:flex;
    justify-content:space-between;
    margin-top:15px;
    margin-bottom:20px;
    font-size:14px;
}

.due{
    border:1px dashed #000;
    width:120px;
    text-align:center;
    padding:5px;
    margin:auto;
    margin-top:-8px;
    font-size:13px;
}

@media print{
    body{
        margin:0;
    }

    .customer-invoice{
        page-break-inside: avoid;
    }
}
</style>

</head>
<body>
   
   @php
        $contacts = App\Models\Contact::first();
   @endphp

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-3 mb-3 text-center">
                <button onclick="printFunction()" class="no-print btn btn-xs btn-success waves-effect waves-light">
                    <i class="fa fa-print"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>

    <div class="page">

        <div class="label-grid">
    
            @foreach($orders as $order)
            
            <div class="customer-invoice">
            
                <div class="logo-section">
                    <img src="{{asset($generalsetting->dark_logo)}}" alt="Company Logo">
                </div>
            
            
                <div class="invoice-row">
                    <div>
                        <b>INVOICE</b><br>
                        {{ $order->invoice_id }}
                    </div>
            
                    <div>
                        <b>Date</b><br>
                        {{ $order->created_at->format('Y-m-d') }}
                    </div>
                </div>
            
                <div class="barcode">
                    {!! DNS1D::getBarcodeHTML($order->invoice_id, 'C128') !!}
                </div>
            
                    <div style="font-size:14px;margin-bottom: 10px;">
                        Courier : <b>{{ $order->shipping_method ?? 'Not Selected' }}</b>
                    </div>
                <div class="invoice-row">
                    <div style="font-weight: 700;">
                        Shipping Address:
                    </div>
                    <div>
                        
                    </div>
                </div>
            
                <div class="address">
                    <b>{{$order->shipping?$order->shipping->name:''}}</b><br>
                    {{$order->shipping?$order->shipping->phone:''}}<br>
                    {{ implode(', ', array_filter([
                        $order->shipping?->address,
                        $order->shipping?->city,
                        $order->shipping?->district
                    ])) }}
                </div>
            
                <div class="bottom">
                    <div>
                        Orders: {{$order->orderdetails->count()}} ({{ $order->invoice_id }})
                    </div>
            
                    <div>
                        Weight : {{ $order->weight }}kg
                    </div>
                </div>
            
                <div class="due">
                    Due : ৳{{ $order->payment_due_amount }}
                </div>
                
                <div>
                    <!--<p class="company-name m-0">{{$generalsetting->name}}</p>-->
                    <div style="display:flex; align-items:center;justify-content:space-between; margin-top:6px;font-size:11px;">
                        <p class="m-0">Mobile: {{ $contacts->phone }}</p>
                        <p class="m-0">Email: {{ $contacts->email }}</p>
                    </div>
                    <p class="m-0" style="font-size:11px;text-align: center;">{{ $contact->address }}</p>
                </div>
            
            </div>
            
            @endforeach
    
    </div>
    </div>

    <script>
        function printFunction() {
            window.print();
        }
    </script>
</body>
</html>