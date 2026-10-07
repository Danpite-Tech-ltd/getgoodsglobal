<?php

namespace App\Http\Controllers\Admin;
use App\Models\Productsize;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\District;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Courierapi;
use App\Models\GeneralSetting;
use App\Models\SmsGateway;
use Session;
use Cart;
use Toastr;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index($slug,Request $request){
        $startDate = $request->orderstart_date ? Carbon::parse($request->orderstart_date)->startOfDay()->format('Y-m-d H:i:s') : null;
        $endDate   = $request->orderend_date ? Carbon::parse($request->orderend_date)->endOfDay()->format('Y-m-d H:i:s') : null;

        if($slug == 'all'){
            $order_status = (object) [
                'name' => 'All',
                'orders_count'=> Order::count(),
            ];

            $show_data = Order::latest()->with('shipping','status');

            // Keyword search
            if($request->keyword){
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }

            // Date filter
            if($startDate && $endDate){
                $show_data = $show_data->whereBetween('created_at', [$startDate, $endDate]);
            }
                
            $show_data = $show_data->paginate(10)->appends($request->query());

        }else{
            $order_status = OrderStatus::where('slug',$slug)->withCount('orders')->first();

            $show_data = Order::where('order_status', $order_status->id)
                ->latest()
                ->with('shipping','status');

            // Keyword search
            if($request->keyword){
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }

            // Date filter
            if($startDate && $endDate){
                $show_data = $show_data->whereBetween('created_at', [$startDate, $endDate]);
            }

            $show_data = $show_data->paginate(10)->appends($request->query());
        }

        $users = User::get();
        $steadfast = Courierapi::where(['status'=>1, 'type'=>'steadfast'])->first();
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        // pathao courier
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/countries/1/city-list');
            $pathaocities = $response->json();
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
                ])->get($pathao_info->url . '/api/v1/stores');
            $pathaostore = $response2->json();
        } else {
            $pathaocities = [];
            $pathaostore = [];
        }
        return view('backEnd.order.index',compact('show_data','order_status','users', 'steadfast','pathaostore','pathaocities'));
    }

    public function pathaocity(Request $request)
    {
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/cities/'.$request->city_id.'/zone-list');
            $pathaozones = $response->json();
            return response()->json($pathaozones);
        } else {
            return response()->json([]);
        }
    }
    public function pathaozone(Request $request)
    {
        $pathao_info = Courierapi::where(['status'=>1, 'type'=>'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/zones/'.$request->zone_id.'/area-list');
            $pathaoareas = $response->json();
            return response()->json($pathaoareas);
        } else {
             return response()->json([]);
        }
    }

    public function order_pathao(Request $request)
    {
        $order_id = $request->order_ids;

        if(isset($order_id)){

            $order = Order::with('shipping')->find($order_id);
            $order_count = OrderDetails::select('order_id')->where('order_id', $order->id)->count();

            $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
            if ($pathao_info) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $pathao_info->token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($pathao_info->url . '/api/v1/orders', [
                    'store_id' => $request->pathaostore,
                    'merchant_order_id' => $order->invoice_id,
                    'sender_name' => 'Test',
                    'sender_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_name' => $order->shipping ? $order->shipping->name : '',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '',
                    'recipient_city' => $request->pathaocity,
                    'recipient_zone' => $request->pathaozone,
                    'recipient_area' => $request->pathaoarea,
                    'delivery_type' => 48,
                    'item_type' => 2,
                    'special_instruction' => 'Special note- product must be check after delivery',
                    'item_quantity' => 1,
                    'item_weight' => 0.5,
                    'amount_to_collect' => round($order->amount),
                    'item_description' => 'Special note- product must be check after delivery',
                ]);
            }
            if ($response->status() == '200') {
                Toastr::success($response['data']['consignment_id'], 'Courier Tracking ID');
                return redirect()->back();
            } else {
                Toastr::error($response['message'], 'Courier Order Faild');
                return response()->json(['status' => 'failed', 'message' => $response['message'], 'Courier Order Faild']);
            }
            return redirect()->back();
        }else{
            Toastr::error('Order Id should not be empty');

            return redirect()->back();
        }



    }

    public function invoice($invoice_id){
        $order = Order::where(['invoice_id'=>$invoice_id])->with('orderdetails','payment','shipping','customer')->firstOrFail();
        return view('backEnd.order.invoice',compact('order'));
    }

    public function process($invoice_id){
        $data = Order::where(['invoice_id'=>$invoice_id])->select('id','invoice_id','order_status')->with('orderdetails')->first();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        return view('backEnd.order.process',compact('data','shippingcharge'));
    }

    public function order_process(Request $request)
    {

        $link = OrderStatus::find($request->status)->slug;
        $order = Order::find($request->id);
        $courier = $order->order_status;
        $order->order_status = $request->status;
        $order->admin_note = $request->admin_note;
        $order->save();

        $shipping_update = Shipping::where('order_id', $order->id)->first();
        $shippingfee = ShippingCharge::find($request->area);
        if ($shippingfee->name != $request->area) {
            if ($order->shipping_charge > $shippingfee->amount) {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            } else {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            }
        }

        $shipping_update->name = $request->name;
        $shipping_update->phone = $request->phone;
        $shipping_update->address = $request->address;
        $shipping_update->area = $shippingfee->name;
        $shipping_update->save();

        if ($request->status == 5 && $courier != 5) {
            $courier_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
            if ($courier_info) {
                $consignmentData = [
                    'invoice' => $order->invoice_id,
                    'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
                    'cod_amount' => $order->amount
                ];
                $client = new Client();
                $response = $client->post('$courier_info->url', [
                    'json' => $consignmentData,
                    'headers' => [
                        'Api-Key' => '$courier_info->api_key',
                        'Secret-Key' => '$courier_info->secret_key',
                        'Accept' => 'application/json',
                    ],
                ]);

                $responseData = json_decode($response->getBody(), true);
            } else {
                return "ok";
            }
            Toastr::success('Success', 'Order status change successfully');
            return redirect('admin/order/' . $link);
        }
        Toastr::success('Success', 'Order status change successfully');
        return redirect('admin/order/' . $link);
    }

    public function destroy(Request $request){
        // Restore stock before deleting
        $details = OrderDetails::where('order_id', $request->id)->get();
        foreach ($details as $detail) {
            if ($detail->product_size) {
                $q = Productsize::where('product_id', $detail->product_id)
                    ->where('size', $detail->product_size);
                $ps = Productsize::where('product_id', $detail->product_id)
                    ->where('size', $detail->product_size)->first();
                if ($ps && $ps->color_id) {
                    $q->where('color_id', $ps->color_id);
                }
                $q->increment('stock', $detail->qty);
            }
        }

        Order::where('id', $request->id)->delete();
        OrderDetails::where('order_id', $request->id)->delete();
        Shipping::where('order_id', $request->id)->delete();
        Payment::where('order_id', $request->id)->delete();
        Toastr::success('Success','Order delete success successfully');
        return redirect()->back();
    }

    public function order_assign(Request $request){
        $products = Order::whereIn('id', $request->input('order_ids'))->update(['user_id' => $request->user_id]);
        return response()->json(['status'=>'success','message'=>'Order user id assign']);
    }

    public function order_status(Request $request){
        // dd($request);
        $orders = Order::whereIn('id', $request->input('order_ids'))->update(['order_status' => $request->order_status]);

        if($request->order_status == 5){
            $orders = Order::whereIn('id', $request->input('order_ids'))->get();
            // foreach($orders as $order){
            //     $orders_details = OrderDetails::select('id','order_id','product_id')->where('order_id',$order->id)->get();
            //     foreach($orders_details as $order_details){
            //         $product = Product::select('id','stock')->find($order_details->product_id);
            //         $product->stock -= $order_details->qty;
            //         $product->save();
            //     }
            // }
        }
        
        // Stock restore for: Cancel (26), Return (8), Returned (17), Refund (23)
        if (in_array($request->order_status, [26, 8, 17, 23])) {
            $orderIds = (array) $request->input('order_ids');
            $affectedOrders = Order::whereIn('id', $orderIds)->with('orderdetails')->get();
            foreach ($affectedOrders as $ord) {
                foreach ($ord->orderdetails as $detail) {
                    if ($detail->product_size) {
                        $q = Productsize::where('product_id', $detail->product_id)
                            ->where('size', $detail->product_size);
                        // Match color_id for exact variant
                        $ps = Productsize::where('product_id', $detail->product_id)
                            ->where('size', $detail->product_size)->first();
                        if ($ps && $ps->color_id) {
                            $q->where('color_id', $ps->color_id);
                        }
                        $q->increment('stock', $detail->qty);
                    }
                }
            }
        }

        if($request->order_status == 23){
            $user = Shipping::where('order_id', $request->input('order_ids'))->first();
            $order = Order::where('id', $request->input('order_ids'))->with('orderdetails')->first();

            // foreach ($order->orderdetails as $detail) {
            //     Productsize::where('size', $detail->product_size)
            //         ->increment('stock', $detail->qty);
            // }

            $generalsetting = GeneralSetting::where('status',1)->first();
            $sms_gateway = SmsGateway::where('status',1)->first();
            
            
            // $url = $sms_gateway->url;
            // $api_key = $sms_gateway->api_key;
            // $senderid = $sms_gateway->serderid;
            // $number = $user->phone;
            // $message = "Dear {$user->name}, the refund for order #{$order->invoice_id} has been processed and will be credited within 2–7 business days. Thank you for your patience. \r\n{$generalsetting->name}\r\n" . env('APP_URL');
         
            // $data = [
            //     "api_key" => $api_key,
            //     "senderid" => $senderid,
            //     "number" => $number,
            //     "message" => $message
            // ];
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // $response = curl_exec($ch);
            // curl_close($ch);
            
            $response = Http::get('http://bulksmsbd.net/api/smsapi', [
                    'api_key'  => $sms_gateway->api_key,
                    'type'     => 'text',
                    'number'   => $user->phone,
                    'senderid' => $sms_gateway->serderid,
                    'message'  => "Dear {$user->name}, the refund for order #{$order->invoice_id} has been processed and will be credited within 2–7 business days. Thank you for your patience. \r\n{$generalsetting->name}\r\n" . env('APP_URL')
                ]);
        }
        
        if($request->order_status == 2){
            $user = Shipping::where('order_id', $request->input('order_ids'))->first();
            $order = Order::where('id', $request->input('order_ids'))->with('orderdetails')->first();

            $generalsetting = GeneralSetting::where('status',1)->first();
            $sms_gateway = SmsGateway::where('status',1)->first();
            
            
            $response = Http::get('http://bulksmsbd.net/api/smsapi', [
                    'api_key'  => $sms_gateway->api_key,
                    'type'     => 'text',
                    'number'   => $user->phone,
                    'senderid' => $sms_gateway->serderid,
                    'message'  => "Dear {$user->name}, your order #{$order->invoice_id} has been Confirmed successfully. Thank you. \r\n{$generalsetting->name}\r\n" . env('APP_URL')
                ]);
                \Log::info('SMS Response:', [
                    'status' => $response->status(),
                    'body' => $response->body()
            ]);
        }
        
        
        
        return response()->json(['status'=>'success','message'=>'Order status change successfully']);
    }

    public function bulk_destroy(Request $request){
        $orders_id = $request->order_ids;
        foreach($orders_id as $order_id){
            // Restore stock before deleting
            $details = OrderDetails::where('order_id', $order_id)->get();
            foreach ($details as $detail) {
                if ($detail->product_size) {
                    $q = Productsize::where('product_id', $detail->product_id)
                        ->where('size', $detail->product_size);
                    $ps = Productsize::where('product_id', $detail->product_id)
                        ->where('size', $detail->product_size)->first();
                    if ($ps && $ps->color_id) {
                        $q->where('color_id', $ps->color_id);
                    }
                    $q->increment('stock', $detail->qty);
                }
            }

            Order::where('id', $order_id)->delete();
            OrderDetails::where('order_id', $order_id)->delete();
            Shipping::where('order_id', $order_id)->delete();
            Payment::where('order_id', $order_id)->delete();
        }
        return response()->json(['status'=>'success','message'=>'Order delete successfully']);
    }
    public function order_print(Request $request){
        $orders = Order::whereIn('id', $request->input('order_ids'))->with('orderdetails','payment','shipping','customer')->get();
        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }
    public function slip_print(Request $request){
        $orders = Order::whereIn('id', $request->input('order_ids'))->with('orderdetails','payment','shipping','customer')->get();
        $view = view('backEnd.order.slip', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }

    public function order_Xlsprint()
    {
        //
    }
    public function bulk_courier($slug, Request $request)
    {

        if($slug=='pathao'){
            $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();



                $orders_id = $request->order_ids;

                foreach ($orders_id as $order_id) {
                    $order = Order::with('shipping')->find($order_id);
                    $order_count = OrderDetails::select('order_id')->where('order_id', $order->id)->count();

                    $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
                    if ($pathao_info) {
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $pathao_info->token,
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ])->post($pathao_info->url . '/api/v1/orders', [
                            'store_id' => '147283',
                            'merchant_order_id' => $order->invoice_id,
                            'sender_name' => 'Test',
                            'sender_phone' => $order->shipping ? $order->shipping->phone : '',
                            'recipient_name' => $order->shipping ? $order->shipping->name : '',
                            'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
                            'recipient_name' => $order->shipping ? $order->shipping->name : '',
                            'recipient_address' => implode(', ', array_filter([
                                $order->shipping ? $order->shipping->address : null,
                                $order->shipping ? $order->shipping->city : null,
                                $order->shipping ? $order->shipping->district : null,
                            ])),
                            'delivery_type' => 48,
                            'item_type' => 2,
                            'special_instruction' => '',
                            'item_quantity' => 1,
                            'item_weight' => $order->weight ? $order->weight : '0.5',
                            'amount_to_collect' => round($order->payment_due_amount),
                            'item_description' => OrderDetails::where('order_id', $order->id)->first()->product_name,
                        ]);
                    }

                    if ($response->status() == '200') {
                        $order->order_status=5;
                        $order->shipping_method = 'Pathao';
                        $order->update();
                    }
                }




                $message = 'Your order place to courier successfully';
                $status = 'success';


                return response()->json(['status' => $status, 'message' => $message]);

        }
        elseif($slug == 'same_day'){
            $orders_id = $request->order_ids;

            foreach ($orders_id as $order_id) {
                $order = Order::with('shipping')->find($order_id);
                    $order->order_status=5;
                    $order->shipping_method = 'same-day';
                    $order->update();
            }
            
           $message = 'Your order place to courier successfully';
                $status = 'success';


            return response()->json(['status' => $status, 'message' => $message]);
        }
        else{
            $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();

            $data=[];
            if ($courier_info) {
                $orders_id = $request->order_ids;

                foreach ($orders_id as $order_id) {
                    $order = Order::find($order_id);

                    $ress = Http::withHeaders([
                        'Api-Key' => $courier_info->api_key,
                        'Secret-Key' => $courier_info->secret_key,
                        'Content-Type' => 'application/json'

                    ])->post('https://portal.packzy.com/api/v1/create_order', [
                        'invoice' => $order->invoice_id,
                        'recipient_name' => $order->shipping ? $order->shipping->name : '',
                            'recipient_address' => implode(', ', array_filter([
                            $order->shipping ? $order->shipping->address : null,
                            $order->shipping ? $order->shipping->city : null,
                            $order->shipping ? $order->shipping->district : null,
                        ])),
                        'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
                        'cod_amount' => $order->payment_due_amount,
                        'note' => 'as fast as possible',
                        'item_weight' => $order->weight ? $order->weight : '0.5',
                    ]);


                    $res = json_decode($ress->getBody()->getContents());


                    if (isset($res->consignment)) {

                        if ($res->consignment->status == 'in_review') {
                            $order = Order::find($order_id);
                            $order->order_status = 5;
                            $order->shipping_method = 'Steadfast';
                            $order->update();

                        } else {
                            $message = 'Your order place to courier failed';
                            $status = 'failed';
                        }
                    } else {
                        $message = 'Your order place to courier failed';
                        $status = 'failed';
                    }

                    $message = 'Your order place to courier successfully';
                    $status = 'success';

                }


                return response()->json(['status' => $status, 'message' => $message]);
            } else {
                return "stop";
            }
        }

    }


        public function stock_report(Request $request){
            $productsizeQuery = Productsize::with('product.category', 'product.brand', 'color')
                ->whereHas('product', function($q){
                    $q->where('status', 1);
                });

            if ($request->keyword) {
                $productsizeQuery = $productsizeQuery->whereHas('product', function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->keyword . "%");
                });
            }
            if ($request->category_id) {
                $productsizeQuery = $productsizeQuery->whereHas('product', function($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }
            if ($request->start_date && $request->end_date) {
                $productsizeQuery = $productsizeQuery->whereBetween('updated_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            // Stock filter — size level এ
            if ($request->stock_filter !== null && $request->stock_filter !== '') {
                if ($request->stock_filter == '1') {
                    $productsizeQuery = $productsizeQuery->where('stock', '>', 0); // Stock In
                } elseif ($request->stock_filter == '0') {
                    $productsizeQuery = $productsizeQuery->where('stock', '<=', 0); // Stock Out
                }
            }
            
            if ($request->filter !== null && $request->filter !== '') {
                if ($request->filter == 'high'){
                    $productsizeQuery = $productsizeQuery->orderBy('stock', 'DESC');
                } elseif($request->filter == 'low'){
                    $productsizeQuery = $productsizeQuery->orderBy('stock', 'ASC');
                }
            }

            // Dynamic per-page/show entries (20, 50, 100, all)
            $show = $request->show ?? 100;
            if ($show === 'all') {
                $totalCount = (clone $productsizeQuery)->count();
                $per_page = $totalCount > 0 ? $totalCount : 100;
            } else {
                $per_page = (int)$show > 0 ? (int)$show : 100;
            }

            $sizes = $productsizeQuery->paginate($per_page)->appends($request->query());
            $categories = Category::where('status',1)->get();

            return view('backEnd.reports.stock', compact('sizes', 'categories'));
        }
    public function order_report(Request $request)
    {
        // Retrieve active users
        $users = User::where('status', 1)->get();

        // Initialize the query for orders with related shipping and order details
        $orders = OrderDetails::with('shipping', 'order')
            ->whereHas('order', function ($query) {
                $query->where('order_status', 9); // Only include orders with status 6
            });

        // Apply user filter if provided
        if ($request->filter) {
            if ($request->filter == 'sale') {
                $orders = $orders
                    ->select('product_id', 'product_size', 'product_color')
                    ->selectRaw('SUM(qty) as total_sale')
                    ->selectRaw('SUM(qty * sale_price) as total_amount')
                    ->groupBy('product_id', 'product_size', 'product_color');
            }
        }
        
        if ($request->filter_qty) {
            if ($request->filter_qty == 'high') {
                if ($request->filter == 'sale') {
                    $orders = $orders->orderByRaw('SUM(qty) DESC');
                } else {
                    $orders = $orders->orderBy('qty', 'DESC');
                }
            }
        
            if ($request->filter_qty == 'low') {
                if ($request->filter == 'sale') {
                    $orders = $orders->orderByRaw('SUM(qty) ASC');
                } else {
                    $orders = $orders->orderBy('qty', 'ASC');
                }
            }
        }


        // Apply keyword filter if provided
        if ($request->keyword) {
            $orders = $orders->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        // Apply date range filter if both start_date and end_date are provided
        if ($request->start_date && $request->end_date) {
            $orders = $orders->whereBetween('updated_at', [$request->start_date, $request->end_date]);
        }

        // Calculate the total purchase, total item, and total sales over the entire dataset (before pagination)
        $totalPurchase = $orders->sum(\DB::raw('purchase_price * qty'));
        $total_item = $orders->sum('qty');
        $total_sales = $orders->sum(\DB::raw('sale_price * qty'));
        $discounts = Order::sum('discount');

        // Dynamic per-page/show entries (20, 50, 100, all)
        $show = $request->show ?? 100;
        if ($show === 'all') {
            if ($request->filter == 'sale') {
                $totalCount = (clone $orders)->get()->count();
            } else {
                $totalCount = (clone $orders)->count();
            }
            $per_page = $totalCount > 0 ? $totalCount : 100;
        } else {
            $per_page = (int)$show > 0 ? (int)$show : 100;
        }

        // Now apply pagination (this won't affect the totals as they are calculated before pagination)
        $orders = $orders->paginate($per_page)->appends($request->query());

        // Return the view with the necessary data
        return view('backEnd.reports.order', compact('orders', 'users', 'totalPurchase', 'total_item', 'total_sales', 'discounts'));
    }


    public function order_create(){
        $products = Product::select('id','name','new_price','product_code')->where(['status'=>1])->get();
        $cartinfo  = Cart::instance('pos_shopping')->content();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        return view('backEnd.order.create',compact('products','cartinfo','shippingcharge'));
    }

    public function order_store(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'area'=>'required',
        ]);

        if(Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',','',$subtotal);
        $subtotal = str_replace('.00', '',$subtotal);
        $discount = Session::get('pos_discount')+Session::get('product_discount');
        $shippingfee  = ShippingCharge::find($request->area);

        $exits_customer = Customer::where('phone',$request->phone)->select('phone','id')->first();
        if($exits_customer){
            $customer_id = $exits_customer->id;
        }else{
            $password = rand(111111,999999);
            $store              = new Customer();
            $store->name        = $request->name;
            $store->slug        = $request->name;
            $store->phone       = $request->phone;
            $store->password    = bcrypt($password);
            $store->verify      = 1;
            $store->status      = 'active';
            $store->save();
            $customer_id = $store->id;
        }

         // order data save
        $order                   = new Order();
        $order->invoice_id       = rand(11111,99999);
        $order->amount           = ($subtotal + $shippingfee->amount) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee->amount;
        $order->customer_id      =  $customer_id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $request->name;
        $shipping->phone       =   $request->phone;
        $shipping->address     =   $request->address;
        $shipping->area        =   $shippingfee->name;
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

       // order details data save
        foreach(Cart::instance('pos_shopping')->content() as $cart){
            $order_details                   =   new OrderDetails();
            $order_details->order_id         =   $order->id;
            $order_details->product_id       =   $cart->id;
            $order_details->product_name     =   $cart->name;
            $order_details->purchase_price   =   $cart->options->purchase_price ?? 0;
            $order_details->product_discount =   $cart->options->product_discount ?? 0;
            $order_details->sale_price       =   $cart->price;
            $order_details->regular_price    =   $cart->options->regular_price ?? $cart->price;
            $order_details->qty              =   $cart->qty;
            $order_details->product_color    =   $cart->options->product_color ?? null;
            $order_details->product_color_image = $cart->options->product_color_image ?? null;
            $order_details->product_size     =   $cart->options->product_size ?? null;
            $order_details->save();
        }
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('admin/order/pending');
    }
    public function get_product_variants(Request $request)
    {
        $product = Product::with(['image', 'procolors.color', 'prosizes.color'])
            ->where('id', $request->id)
            ->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        // Build color => sizes+price map from productsizes
        $variants = [];
        $colors = \App\Models\Productcolor::where('product_id', $product->id)
            ->with('color')
            ->get();

        foreach ($colors as $pc) {
            $sizes = \App\Models\Productsize::where('product_id', $product->id)
                ->where('color_id', $pc->color_id)
                ->get(['id', 'size', 'SalePrice', 'RegularPrice', 'PurchasePrice', 'stock', 'color_id']);

            $mappedSizes = [];
            foreach ($sizes as $sz) {
                $mappedSizes[] = [
                    'id'             => $sz->id,
                    'size'           => $sz->size,
                    'price'          => $sz->SalePrice !== null ? (float)$sz->SalePrice : (float)$product->new_price,
                    'sale_price'     => (float)$sz->SalePrice,
                    'regular_price'  => (float)$sz->RegularPrice,
                    'purchase_price' => (float)$sz->PurchasePrice,
                    'stock'          => $sz->stock,
                    'color_id'       => $sz->color_id,
                ];
            }

            $colorName = $pc->color->colorName ?? $pc->color ?? '';
            $colorImage = $pc->Image ?? '';
            $colorImageUrl = $colorImage ? asset($colorImage) : ($product->image ? asset($product->image->image) : '');

            $variants[] = [
                'color_id'        => $pc->color_id,
                'color_name'      => $colorName,
                'color_image'     => $colorImage,
                'color_image_url' => $colorImageUrl,
                'sizes'           => $mappedSizes,
            ];
        }

        if (empty($variants)) {
            $sizes = \App\Models\Productsize::where('product_id', $product->id)
                ->get(['id', 'size', 'SalePrice', 'RegularPrice', 'PurchasePrice', 'stock', 'color_id']);

            if ($sizes->isNotEmpty()) {
                $mappedSizes = [];
                foreach ($sizes as $sz) {
                    $mappedSizes[] = [
                        'id'             => $sz->id,
                        'size'           => $sz->size,
                        'price'          => $sz->SalePrice !== null ? (float)$sz->SalePrice : (float)$product->new_price,
                        'sale_price'     => (float)$sz->SalePrice,
                        'regular_price'  => (float)$sz->RegularPrice,
                        'purchase_price' => (float)$sz->PurchasePrice,
                        'stock'          => $sz->stock,
                        'color_id'       => $sz->color_id,
                    ];
                }
                $variants[] = [
                    'color_id'        => null,
                    'color_name'      => 'Default',
                    'color_image'     => '',
                    'color_image_url' => $product->image ? asset($product->image->image) : '',
                    'sizes'           => $mappedSizes,
                ];
            }
        }

        return response()->json([
            'status'   => 'success',
            'product'  => [
                'id'        => $product->id,
                'name'      => $product->name,
                'new_price' => $product->new_price,
                'image'     => $product->image ? asset($product->image->image) : '',
            ],
            'variants' => $variants,
        ]);
    }

    public function cart_add(Request $request){
        $product = Product::with(['image', 'procolors.color', 'prosizes.color'])
            ->where('id', $request->id)
            ->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $qty = 1;
        $price = $product->new_price;
        $purchase_price = $product->purchase_price;
        $product_color = $request->color_name ?? '';
        $product_color_image = $request->color_image ?? '';
        $color_id = $request->color_id ?? null;
        $product_size = $request->size ?? '';

        // If color/size not explicitly provided, choose first variant as default
        if (empty($product_color) && empty($product_size)) {
            $firstColor = $product->procolors->first();
            if ($firstColor) {
                $color_id = $firstColor->color_id;
                $product_color = $firstColor->color->colorName ?? $firstColor->color ?? '';
                $product_color_image = $firstColor->Image ?? '';

                $firstSize = $product->prosizes->where('color_id', $color_id)->first();
                if ($firstSize) {
                    $product_size = $firstSize->size;
                    if ($firstSize->SalePrice !== null && (float)$firstSize->SalePrice > 0) {
                        $price = (float)$firstSize->SalePrice;
                    }
                    if ($firstSize->PurchasePrice !== null) {
                        $purchase_price = (float)$firstSize->PurchasePrice;
                    }
                }
            } else {
                $firstSize = $product->prosizes->first();
                if ($firstSize) {
                    $product_size = $firstSize->size;
                    if ($firstSize->SalePrice !== null && (float)$firstSize->SalePrice > 0) {
                        $price = (float)$firstSize->SalePrice;
                    }
                    if ($firstSize->PurchasePrice !== null) {
                        $purchase_price = (float)$firstSize->PurchasePrice;
                    }
                }
            }
        } elseif ($request->filled('size_price') && (float)$request->size_price > 0) {
            $price = (float)$request->size_price;
        }

        $regular_price = (float)($product->old_price ?: $price);
        if (isset($firstSize) && $firstSize && $firstSize->RegularPrice !== null && (float)$firstSize->RegularPrice > 0) {
            $regular_price = (float)$firstSize->RegularPrice;
        }

        $image = $product_color_image ?: ($product->image->image ?? '');

        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $price,
            'options' => [
                'slug'                => $product->slug,
                'image'               => $image,
                'old_price'           => $product->old_price,
                'regular_price'       => $regular_price,
                'purchase_price'      => $purchase_price,
                'product_discount'    => 0,
                'product_color'       => $product_color,
                'product_color_image' => $product_color_image,
                'color_id'            => $color_id,
                'product_size'        => $product_size,
                // Unique key so the same product can be added multiple times as separate rows
                '_uid'                => uniqid('', true),
            ],
        ]);
        return response()->json(compact('cartinfo'));
    }

    public function cart_update_variant(Request $request)
    {
        $cart = Cart::instance('pos_shopping')->get($request->rowId);
        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Cart item not found'], 404);
        }

        $price = $cart->price;
        if ($request->filled('size_price') && (float)$request->size_price > 0) {
            $price = (float)$request->size_price;
        }

        $options = $cart->options ? (is_array($cart->options) ? $cart->options : $cart->options->toArray()) : [];

        if ($request->has('color_name')) {
            $options['product_color'] = $request->color_name;
        }
        if ($request->has('color_image')) {
            $options['product_color_image'] = $request->color_image;
            if (!empty($request->color_image)) {
                $options['image'] = $request->color_image;
            }
        }
        if ($request->has('color_id')) {
            $options['color_id'] = $request->color_id;
        }
        if ($request->has('size')) {
            $options['product_size'] = $request->size;
            $psQuery = Productsize::where('product_id', $cart->id)->where('size', $request->size);
            if (!empty($options['color_id'])) {
                $psQuery->where('color_id', $options['color_id']);
            }
            $ps = $psQuery->first();
            if ($ps && $ps->RegularPrice) {
                $options['regular_price'] = (float)$ps->RegularPrice;
            }
        }
        if ($request->filled('purchase_price')) {
            $options['purchase_price'] = (float)$request->purchase_price;
        }
        if ($request->filled('regular_price')) {
            $options['regular_price'] = (float)$request->regular_price;
        }

        Cart::instance('pos_shopping')->update($request->rowId, [
            'price'   => $price,
            'options' => $options,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function cart_content(){
        $cartinfo = Cart::instance('pos_shopping')->content();
        $productIds = $cartinfo->pluck('id')->unique();
        $cartProducts = Product::whereIn('id', $productIds)
            ->with(['procolors.color', 'prosizes.color'])
            ->get()
            ->keyBy('id');
        return view('backEnd.order.cart_content', compact('cartinfo', 'cartProducts'));
    }
    public function cart_details(){
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.cart_details',compact('cartinfo'));
    }
    public function cart_increment(Request $request){
        $cartItem = Cart::instance('pos_shopping')->get($request->id);
        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Cart item not found'], 404);
        }

        // Check stock availability
        $productId  = $cartItem->id;
        $colorId    = $cartItem->options->color_id ?? null;
        $sizeName   = $cartItem->options->product_size ?? null;
        $currentQty = $cartItem->qty;

        $stockQuery = \App\Models\Productsize::where('product_id', $productId);
        if ($sizeName) {
            $stockQuery->where('size', $sizeName);
        }
        if ($colorId) {
            $stockQuery->where('color_id', $colorId);
        }
        $sizeRecord = $stockQuery->first();
        $dbStock = $sizeRecord ? (int)$sizeRecord->stock : 0;

        // In edit mode, DB stock is already reduced by the existing order qty.
        // Effective total available = dbStock (remaining) + currentQty (already in this order).
        // We can increment as long as there is at least 1 remaining in DB (dbStock > 0).
        $effectiveMax = $dbStock + $currentQty;

        if ($dbStock <= 0 || $currentQty >= $effectiveMax) {
            return response()->json(['status' => 'out_of_stock', 'message' => 'Stock not available', 'stock' => $dbStock]);
        }

        $qty = $currentQty + 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json(['status' => 'success', 'cartinfo' => $cartinfo]);
    }
    public function cart_decrement(Request $request){
        $qty = $request->qty - 1;
        if ($qty < 1) $qty = 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_remove(Request $request){
        $remove = Cart::instance('pos_shopping')->remove($request->id);
        $cartinfo = Cart::instance('pos_shopping')->content();
        return response()->json($cartinfo);
    }
    public function product_discount(Request $request){
        $discount = (float)($request->discount ?? 0);
        $cart = Cart::instance('pos_shopping')->get($request->id);
        if (!$cart) {
            $cart = Cart::instance('pos_shopping')->content()->where('rowId', $request->id)->first();
        }
        if ($cart) {
            $options = $cart->options ? (is_array($cart->options) ? $cart->options : $cart->options->toArray()) : [];
            $options['product_discount'] = $discount;
            $cartinfo = Cart::instance('pos_shopping')->update($request->id, [
                'options' => $options,
            ]);
            return response()->json($cartinfo);
        }
        return response()->json(['status' => 'error', 'message' => 'Cart item not found'], 404);
    }
    public function cart_shipping(Request $request){
         $shipping = ShippingCharge::where(['status'=>1,'id'=>$request->id])->first()->amount;
        Session::put('pos_shipping', $shipping);
        return response()->json($shipping);
    }

    public function cart_clear(Request $request){
        $cartinfo = Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        return redirect()->back();
    }
    public function order_edit($invoice_id){
        $products = Product::select('id','name','new_price','product_code')->where(['status'=>1])->get();
        $shippingcharge = ShippingCharge::where('status',1)->get();
        $order = Order::where('invoice_id',$invoice_id)->first();
        Cart::instance('pos_shopping')->destroy();
        $shippinginfo  = Shipping::where('order_id',$order->id)->first();
        Session::forget('product_discount');
        Session::forget('pos_discount');
        Session::put('pos_shipping', $order->shipping_charge);
        $orderdetails = OrderDetails::where('order_id',$order->id)->get();
        foreach($orderdetails as $ordetails){
            // Resolve color_id from Productsize for proper stock tracking
            $colorId = null;
            $regularPrice = $ordetails->regular_price;
            if ($ordetails->product_size) {
                $psQuery = Productsize::where('product_id', $ordetails->product_id)
                    ->where('size', $ordetails->product_size);
                $ps = $psQuery->first();
                if ($ps) {
                    $colorId = $ps->color_id;
                    if (!$regularPrice && $ps->RegularPrice) {
                        $regularPrice = (float)$ps->RegularPrice;
                    }
                }
            }
            if (!$regularPrice) {
                $prod = Product::find($ordetails->product_id);
                $regularPrice = $prod && $prod->old_price ? (float)$prod->old_price : (float)$ordetails->sale_price;
            }

            $prodImage = Product::find($ordetails->product_id)?->image?->image ?? '';
            $displayImage = $ordetails->product_color_image ?: $prodImage;

            Cart::instance('pos_shopping')->add([
                'id'    => $ordetails->product_id,
                'name'  => $ordetails->product_name,
                'qty'   => $ordetails->qty,
                'price' => $ordetails->sale_price,
                'options' => [
                    'slug'                => Product::where('id', $ordetails->product_id)->value('slug') ?? '',
                    'image'               => $displayImage,
                    'old_price'           => (float)$regularPrice,
                    'regular_price'       => (float)$regularPrice,
                    'purchase_price'      => $ordetails->purchase_price,
                    'product_discount'    => $ordetails->product_discount ?? 0,
                    'details_id'          => $ordetails->id,
                    'product_color'       => $ordetails->product_color,
                    'product_color_image' => $ordetails->product_color_image,
                    'product_size'        => $ordetails->product_size,
                    'color_id'            => $colorId,
                ],
            ]);
        }
        $cartinfo  = Cart::instance('pos_shopping')->content();
        $productIds = $cartinfo->pluck('id')->unique();
        $cartProducts = Product::whereIn('id', $productIds)
            ->with(['procolors.color', 'prosizes.color'])
            ->get()
            ->keyBy('id');
        return view('backEnd.order.edit',compact('products','cartinfo','shippingcharge','shippinginfo','order','cartProducts'));
    }

    public function order_update(Request $request)
{
    $this->validate($request, [
        'name'    => 'required',
        'phone'   => 'required',
        'address' => 'required',
        'area'    => 'required',
    ]);

    if (Cart::instance('pos_shopping')->count() <= 0) {
        Toastr::error('Your shopping cart is empty', 'Failed!');
        return redirect()->back();
    }

    $order = Order::where('id', $request->order_id)->first();

    // ── STEP 1: Revert stock for OLD order details before applying new values ──
    $oldDetails = OrderDetails::where('order_id', $order->id)->get();
    foreach ($oldDetails as $old) {
        if ($old->product_size) {
            $q = Productsize::where('product_id', $old->product_id)
                ->where('size', $old->product_size);
            if ($old->product_color) {
                // Try to match color_id via productsize
                $ps = Productsize::where('product_id', $old->product_id)
                    ->where('size', $old->product_size)
                    ->first();
                if ($ps && $ps->color_id) {
                    $q->where('color_id', $ps->color_id);
                }
            }
            $q->increment('stock', $old->qty);
        }
    }

    // ── STEP 2: Stock limit check on new cart items ──
    $cartItemsPostCheck = $request->input('cart_items', []);
    foreach (Cart::instance('pos_shopping')->content() as $cart) {
        $postRowCheck = $cartItemsPostCheck[$cart->rowId] ?? [];
        $sizeName = !empty($postRowCheck['size']) ? $postRowCheck['size'] : ($cart->options->product_size ?? null);
        $colorId  = !empty($postRowCheck['color_id']) ? $postRowCheck['color_id'] : ($cart->options->color_id ?? null);
        if ($sizeName) {
            $sqQuery = Productsize::where('product_id', $cart->id)->where('size', $sizeName);
            if ($colorId) {
                $sqQuery->where('color_id', $colorId);
            }
            $sz = $sqQuery->first();
            $available = $sz ? (int)$sz->stock : 0;
            if ($cart->qty > $available) {
                // Restore old stock (undo step 1) and abort
                foreach ($oldDetails as $old) {
                    if ($old->product_size) {
                        $q2 = Productsize::where('product_id', $old->product_id)->where('size', $old->product_size);
                        $ps2 = Productsize::where('product_id', $old->product_id)->where('size', $old->product_size)->first();
                        if ($ps2 && $ps2->color_id) $q2->where('color_id', $ps2->color_id);
                        $q2->decrement('stock', $old->qty);
                    }
                }
                Toastr::error('"' . $cart->name . '" এর পর্যাপ্ত stock নেই। Available: ' . $available, 'Stock Error!');
                return redirect()->back();
            }
        }
    }

    // ── STEP 3: Calculate amounts ──
    $subtotal = Cart::instance('pos_shopping')->subtotal();
    $subtotal = str_replace([',', '.00'], '', $subtotal);
    $shippingfee  = ShippingCharge::find($request->area);

    // ── STEP 4: Handle customer ──
    $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
    if ($exits_customer) {
        $customer_id = $exits_customer->id;
    } else {
        $password           = rand(111111, 999999);
        $store              = new Customer();
        $store->name        = $request->name;
        $store->slug        = $request->name;
        $store->phone       = $request->phone;
        $store->password    = bcrypt($password);
        $store->verify      = 1;
        $store->status      = 'active';
        $store->save();
        $customer_id = $store->id;
    }

    // ── STEP 5: Update Order ──
    $newAmount               = (float)$subtotal + $shippingfee->amount;
    $alreadyPaid             = (float)($order->paid_partial_payment_amount ?? 0);
    $order->amount           = $newAmount;
    $order->discount         = 0;
    $order->shipping_charge  = $shippingfee->amount;
    $order->customer_id      = $customer_id;
    $order->note             = $request->note;
    $order->admin_note       = $request->admin_note;
    $order->refund_paid_amount = $request->refund_paid_amount;
    $order->weight           = $request->weight;
    // Recalculate due amount properly
    $order->payment_due_amount = max(0, $newAmount - $alreadyPaid);
    $order->save();

    // ── STEP 6: Update Shipping ──
    $shipping              = Shipping::where('order_id', $request->order_id)->first();
    $shipping->order_id    = $order->id;
    $shipping->customer_id = $customer_id;
    $shipping->name        = $request->name;
    $shipping->phone       = $request->phone;
    $shipping->address     = $request->address;
    $shipping->area        = $shippingfee->name;
    $shipping->save();

    // ── STEP 7: Update Payment ──
    $payment                 = Payment::where('order_id', $request->order_id)->first();
    $payment->order_id       = $order->id;
    $payment->customer_id    = $customer_id;
    $payment->amount         = $order->amount;
    $payment->save();

    // ── STEP 8: Delete removed items ──
    $cartRowIds = [];
    foreach (Cart::instance('pos_shopping')->content() as $cart) {
        if (!empty($cart->options->details_id)) {
            $cartRowIds[] = $cart->options->details_id;
        }
    }
    OrderDetails::where('order_id', $order->id)
        ->whereNotIn('id', array_filter($cartRowIds))
        ->delete();

    // ── STEP 9: Upsert cart items + deduct NEW stock ──
    $cartItemsPost = $request->input('cart_items', []);

    foreach (Cart::instance('pos_shopping')->content() as $cart) {
        $detailsId = $cart->options->details_id ?? null;
        $order_details = null;
        if ($detailsId) {
            $order_details = OrderDetails::where('id', $detailsId)->where('order_id', $order->id)->first();
        }

        if (!$order_details) {
            $order_details             = new OrderDetails();
            $order_details->order_id   = $order->id;
            $order_details->product_id = $cart->id;
        }

        // Get POST form data for this cart row (submitted from selects in cart table)
        $postRow = $cartItemsPost[$cart->rowId] ?? [];

        // Resolve color/size from POST first (most up-to-date), then cart session, then existing order_details
        $postColorId   = $postRow['color_id']   ?? null;
        $postColorName = $postRow['color_name']  ?? null;
        $postColorImg  = $postRow['color_image'] ?? null;
        $postSize      = $postRow['size']        ?? null;

        // color_name: POST hidden input -> cart session -> existing order_details
        $productColor = (!empty($postColorName))
            ? $postColorName
            : (!empty($cart->options->product_color)
                ? $cart->options->product_color
                : ($order_details->product_color ?? null));

        // color_image: POST hidden input -> cart session -> existing order_details
        $productColorImage = (!empty($postColorImg))
            ? $postColorImg
            : (!empty($cart->options->product_color_image)
                ? $cart->options->product_color_image
                : ($order_details->product_color_image ?? null));

        // size: POST select -> cart session -> existing order_details
        $productSize = (!empty($postSize))
            ? $postSize
            : (!empty($cart->options->product_size)
                ? $cart->options->product_size
                : ($order_details->product_size ?? null));

        // color_id: POST select -> cart session
        $resolvedColorId = (!empty($postColorId))
            ? $postColorId
            : ($cart->options->color_id ?? null);

        // If we have color_id from POST but no color_name, resolve it from DB
        if (!empty($resolvedColorId) && empty($productColor)) {
            $colorObj = \App\Models\Color::find($resolvedColorId);
            if ($colorObj) {
                $productColor = $colorObj->colorName ?? $colorObj->color ?? null;
            }
        }

        // If color_image is still empty but color_id is present, try Productcolor
        if (empty($productColorImage) && !empty($resolvedColorId)) {
            $pColor = \App\Models\Productcolor::where('product_id', $cart->id)->where('color_id', $resolvedColorId)->first();
            if ($pColor && $pColor->Image) {
                $productColorImage = $pColor->Image;
            }
        }

        // If color_image is still empty but product_color is present, try finding Image from Productcolor
        if (empty($productColorImage) && !empty($productColor)) {
            $colorRecord = \App\Models\Color::where('colorName', $productColor)->first();
            if ($colorRecord) {
                $pColor = \App\Models\Productcolor::where('product_id', $cart->id)->where('color_id', $colorRecord->id)->first();
                if ($pColor && $pColor->Image) {
                    $productColorImage = $pColor->Image;
                }
            }
        }

        // Determine regular_price: cart options -> existing order_details -> Productsize -> Product
        $regularPrice = $cart->options->regular_price ?? null;
        if (!$regularPrice && !empty($order_details->regular_price)) {
            $regularPrice = $order_details->regular_price;
        }
        if (!$regularPrice) {
            $sizeName = $productSize;
            if ($sizeName) {
                $psQuery = Productsize::where('product_id', $cart->id)->where('size', $sizeName);
                if (!empty($resolvedColorId)) {
                    $psQuery->where('color_id', $resolvedColorId);
                }
                $ps = $psQuery->first();
                if ($ps && $ps->RegularPrice) {
                    $regularPrice = (float)$ps->RegularPrice;
                }
            }
        }
        if (!$regularPrice) {
            $oldPrice = Product::where('id', $cart->id)->value('old_price');
            $regularPrice = $oldPrice ? (float)$oldPrice : (float)$cart->price;
        }

        $order_details->product_name        = $cart->name;
        $order_details->purchase_price      = $cart->options->purchase_price ?? ($order_details->purchase_price ?? 0);
        $order_details->product_discount    = 0;
        $order_details->sale_price          = $cart->price;
        $order_details->regular_price       = (float)$regularPrice;
        $order_details->qty                 = $cart->qty;
        $order_details->product_color       = $productColor;
        $order_details->product_color_image = $productColorImage;
        $order_details->product_size        = $productSize;
        $order_details->save();

        // Deduct stock for the new quantities
        $sizeName = $productSize;
        $colorId  = $resolvedColorId;
        if ($sizeName) {
            $deductQuery = Productsize::where('product_id', $cart->id)->where('size', $sizeName);
            if ($colorId) {
                $deductQuery->where('color_id', $colorId);
            }
            $deductQuery->decrement('stock', $cart->qty);
        }
    }

    // ── STEP 10: Clear Cart & Session ──
    Cart::instance('pos_shopping')->destroy();
    Session::forget('pos_shipping');
    Session::forget('pos_discount');
    Session::forget('product_discount');

    $order_slug = OrderStatus::find($order->order_status)->slug;

    Toastr::success('Thanks, Order updated successfully', 'Success!');
    return redirect('admin/order/' . $order_slug);
}


    public function maplist()
    {
        $districts = District::select('district')->distinct()->pluck('district');;
        return view('backEnd.order.maplist',compact('districts'));
    }

}
