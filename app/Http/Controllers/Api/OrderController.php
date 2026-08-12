<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Productsize;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Buy;
use App\Models\Bank;
use App\Models\BuyDetail;
use App\Models\Customer;
use App\Models\SmsGateway;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    
    public function order_print(Request $request, $order_id){
        $orders = Order::where('invoice_id', $order_id)->with('orderdetails','payment','shipping','customer')->get();
        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }
    
    public function orderTrack($invoice_id)
    {
        $orders = Order::where('invoice_id', $invoice_id)
            ->with(['orderDetails', 'status', 'shipping'])
            ->get()
            ->map(function ($order) {

                $groupedProducts = $order->orderDetails
                    ->groupBy('product_id')
                    ->map(function ($items) {
                        $slug = Product::find($items->first()->product_id)->slug;
                        return [
                            'product_id'   => $items->first()->product_id,
                            'product_name' => $items->first()->product_name,
                            'product_slug' => $slug,
                            // 'sale_price'   => $items->first()->sale_price,
                            'variants'     => $items->map(function ($item) {
                                // $regular_price = Productsize::where('product_id', $item->product_id)->where('size', $item->product_size)->first()->RegularPrice;
                                return [
                                    'color'       => $item->product_color,
                                    'color_image' => $item->product_color_image,
                                    'sale_price'   => $item->sale_price,
                                    'regular_price' => $item->regular_price,
                                    'product_price' => $item->regular_price * $item->qty,
                                    'size'        => $item->product_size,
                                    'qty'         => $item->qty,
                                ];
                            })->values()
                        ];
                    })
                    ->values();
    
                $order->products = $groupedProducts;
                unset($order->orderDetails);
    
                return $order;
            });
            
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Order Track',
            'data'    => $orders
        ]);
    }


    public function payment($invoice_id)
    {
        $orders = Order::where('invoice_id', $invoice_id)->select('invoice_id', 'amount', 'paid_partial_payment_amount')->first();
        $banks = Bank::where('status', 1)->get();
        $advanced = GeneralSetting::first()->payment_percentage;
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment',
            'data' => $orders,
            'banks' => $banks,
            'advanced' => $advanced
            
        ]);
    }

    public function payment_submit(Request $request, $invoice_id)
    {   
        try {
            DB::beginTransaction();
            // dd($request->all());
            $advanced = GeneralSetting::first()->payment_percentage;
            $payment_method_id = $request->payment_method_id;
            
            $order = Order::where('invoice_id',$invoice_id)->first();
            $cod = Bank::where('id', $payment_method_id)->first()->cod;
            $cod_charge = round(($order->amount * $cod) / 100);
            
            if($payment_method_id == 5){
                $total_amount = $order->amount + $cod_charge;
    
                // advance amount
                $order->paid_partial_payment_amount = round(($total_amount * $advanced) / 100);
            
                // due amount
                $order->payment_due_amount = $total_amount - $order->paid_partial_payment_amount;
            
                $order->cod_percentage = $cod;
                $order->cod_charge = $cod_charge;
                $order->advanced = $advanced;
                $order->amount = $total_amount;
            }else{
                $order->cod_percentage = $cod;
                $order->cod_charge = $cod_charge;
                
                $order->paid_partial_payment_amount = $order->amount + $cod_charge; 
                $order->payment_due_amount =  0;
                
                $order->amount =  $order->amount + $cod_charge;
            }
            
            
            $order->order_type = $request->payment_method;
            $order->order_status = 22;
            if ($request->file('pay_slip_image')) {
                $image = $request->file('pay_slip_image');
    
                if (!is_null($order->pay_slip_image) && file_exists($order->pay_slip_image)) {
                    unlink($order->pay_slip_image);
                }
    
                $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
                $imagePath          = 'public/uploads/order_slip/';
                $image->move($imagePath, $imageName);
    
                $order->pay_slip_image   = $imagePath . $imageName;
            }
            
            $order->save();
            
            // Update Payment
            $payment                 = Payment::where('order_id', $order->id)->first();
            $payment->order_id       = $order->id;
            $payment->customer_id    = $order->customer_id;
            $payment->payment_method = $request->payment_method;
            
            if($payment_method_id == 5){
                $cod = Bank::where('id', 5)->first()->cod;
                $payment->amount = round(($order->amount * $cod) / 100);
            }else{
                $payment->amount = round(($order->amount * $advanced) / 100);
            }
            
            $payment->payment_status = 'In Review';
            $payment->save();
            
            // if (config('mail.default') !== 'sendmail') {
            //         Mail::to($user->email)->send(new \App\Mail\OrderPlace($order->id ));
            // }
                $sms_gateway = SmsGateway::where('status',1)->first();
                $generalsetting = GeneralSetting::where('status',1)->first();
                
                // $url = $sms_gateway->url ?? '';
                // $api_key = $sms_gateway->api_key ?? '';
                // $senderid = $sms_gateway->serderid ?? '';
                // $number = $request->phone;
                // $message = "Dear {$request->name},  your order #{$invoice_id} has been placed successfully. Thank You. \r\n{$generalsetting->name}\r\n" . env('APP_URL');
             
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
                // return $response;
                
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Order and Payment Success!',
                'order' => $order,
                'payment' => $payment,
            ]);
        } catch (ValidationException $e) {
             DB::rollBack();
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
             DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function apply_coupon(Request $request)
    {
        $coupon = $request->coupon_name;
        $pack = Coupon::where('coupon_name', $coupon)->first();

        // Check if coupon exists
        if (!$pack) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Coupon'
            ]);
        }

        // Check usage count
        if ($pack->count <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon Usage Limit Reached!'
            ]);
        }

        // Check expiration
        if ($pack->validity < Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'Expired Coupon'
            ]);
        }

        // Prepare response
        $discount = $pack->amount;
        $type = $pack->coupon_type == 1 ? 'Solid' : 'Percentage';

        return response()->json([
            'success' => true,
            'message' => 'Coupon Added Successfully.',
            'discount' => $discount,
            'type' => $type,
            'coupon' => $pack
        ]);
    }

    public function orderPlace(Request $request)
    {
        try {
            DB::beginTransaction();
            //  Validate
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'phone'           => 'required|string|max:20',
                'address'         => 'required|string',
                'customer_id'     => 'required|integer',
                'payment_method'  => 'required|string',
                'cart_ids'   => 'required|array|min:1',
                'cart_ids.*' => 'integer|exists:carts,id'
            ]);

            // Find Customer
            $user = Customer::find($request->customer_id);
            $setting = GeneralSetting::first();
            
            $total_quantity = $request->total_quantity;

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated user.',
                ], 401);
            }

            $user_id = $user->id;

            $cartItems = Cart::where('user_id', $user_id)
                ->whereIn('id', $request->cart_ids)
                ->with('cartdetails')
                ->get();
                
            // dd($cartItems);

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status'  => 'empty',
                    'message' => 'Cart is empty',
                ], 400);
            }

            $subtotal = 0;
            foreach ($cartItems as $item) {
                foreach ($item->cartdetails as $detail) {
                    $subtotal += ($detail->price * $detail->quantity);
                }
            }


            // Shipping Area
            $shippingcharge_id = $request->shippingcharge_id;
            $shipping_area = ShippingCharge::find($shippingcharge_id);

            $shippingfee = $shipping_area->amount ?? 0;
            
            
            
            // coupon
            $coupon = Coupon::where('coupon_name', $request->coupon_code)->first();
            $coupon_discount = 0;
            // dd($request->coupon_code);
            if (!empty($request->coupon_code)) {
            
                $coupon = Coupon::where('coupon_name', $request->coupon_code)->first();
            
                if ($coupon) {
            
                    if ($coupon->coupon_type == 1) {
                        $coupon_discount = $coupon->amount;
            
                    } elseif ($coupon->coupon_type == 2) {
                        $coupon_discount = round(($subtotal * $coupon->amount) / 100);
                    }
            
                    // if ($coupon_discount > $subtotal) {
                    //     $coupon_discount = $subtotal;
                    // }
                }
            }


            if (!$shipping_area) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid shipping area',
                ], 422);
            }

            // Total Amount
            $totalAmount = $subtotal + $shippingfee - $coupon_discount;
            // dd( $totalAmount);

            //     CREATE ORDER
            $order = new Order();
            $order->invoice_id       = 0;
            $order->amount           = $totalAmount;
            $order->discount         = 0;
            $order->coupon_name      = $request->coupon_code;
            $order->coupon_discount  = $coupon_discount;
            $order->shipping_charge  = $shippingfee;
            $order->paid_partial_payment_amount = $request->paid_partial_payment_amount ?? 0;
            $order->payment_due_amount = $totalAmount;
            $order->customer_id     = $user_id;
            $order->order_status     = 1;
            $order->note             = $request->note;
            if($request->flash_sale_discount_price){
                $order->flash_sale_title = $setting->flash_sale_title;
                $order->flash_sale_discount_amount  = $request->flash_sale_discount_price;
                $order->flash_sale_discount_percentage  = $setting->flash_sale_percentage;
            }
            $order->save();
           
            $order->invoice_id = 'ORD-' . 20000 + $order->id;
            $order->save();

            //     SHIPPING INFO
            $shipping = new Shipping();
            $shipping->order_id     = $order->id;
            $shipping->customer_id  = $user_id;
            $shipping->name         = $request->name;
            $shipping->phone        = $request->phone;
            $shipping->address      = $request->address;
            $shipping->district     = $request->district;
            $shipping->city         = $request->city;
            $shipping->area         = $shipping_area->name;
            $shipping->save();

            //     PAYMENT INFO
            $payment = new Payment();
            $payment->order_id       = $order->id;
            $payment->customer_id    = $user_id;
            $payment->payment_method = $request->payment_method;
            $payment->amount         = $order->amount;
            $payment->payment_status = 'pending';
            $payment->payment_id = 'GP' . uniqid();
            $payment->save();

            //  ORDER DETAILS
            foreach ($cartItems as $cart) {
                // dd($cart);
                foreach ($cart->cartdetails as $detail) {

                    $orderDetails = new OrderDetails();
                    $orderDetails->order_id       = $order->id;
                    $orderDetails->product_id     = $cart->product_id;
                    $orderDetails->product_name   = Product::find($cart->product_id)->name;
                    $orderDetails->product_color  = $detail->color;
                    $orderDetails->product_color_image  = $detail->color_image;
                    $orderDetails->product_size   = $detail->size;
                    $orderDetails->qty            = $detail->quantity;
                    $orderDetails->sale_price     = $detail->price;
                    $orderDetails->regular_price     = $detail->regular_price;
                    $orderDetails->purchase_price = 0;
                    $orderDetails->save();

                    // STOCK UPDATE
                    $product = Product::find($cart->product_id);


                    Productsize::where('product_id', $cart->product_id)
                        ->where('size', $detail->size)
                        ->decrement('stock', $detail->quantity);
                   
                }
            }
            // if (config('mail.default') !== 'sendmail') {
            //     Mail::to($user->email)->send(new \App\Mail\OrderPlace($order->id ));
            // }



            // Delete related cart details first
            CartDetails::whereIn('cart_id', $cartItems->pluck('id'))
                ->delete();
        
            // Delete selected carts only
            Cart::whereIn('id', $cartItems->pluck('id'))
                ->where('user_id', $user_id)
                ->delete();
            
            DB::commit();
            
            return response()->json([
                'status'  => 'success',
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'validated_input' => $validated,
                
            ], 201);
        } catch (ValidationException $e) {
             DB::rollBack();
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
             DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    public function buyorderPlace(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            //  Validate
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'phone'           => 'required|string|max:20',
                'address'         => 'required|string',
                'customer_id'     => 'required|integer',
                'payment_method'  => 'required|string',
            ]);

            // Find Customer
            $user = Customer::find($request->customer_id);
            $setting = GeneralSetting::first();
            
            $total_quantity = $request->total_quantity;

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated user.',
                ], 401);
            }

            $user_id = $user->id;

            $buyItems = Buy::where('user_id', $user_id)
                ->with('buydetails')
                ->get();

            if ($buyItems->isEmpty()) {
                return response()->json([
                    'status'  => 'empty',
                    'message' => 'Buy is empty',
                ], 400);
            }

            $subtotal = 0;
            foreach ($buyItems as $item) {
                foreach ($item->buydetails as $detail) {
                    $subtotal += ($detail->price * $detail->quantity);
                }
            }


            // Shipping Area
            $shippingcharge_id = $request->shippingcharge_id;
            $shipping_area = ShippingCharge::find($shippingcharge_id);

            $shippingfee = $shipping_area->amount ?? 0;
            
            
            
            
            // coupon
            $coupon = Coupon::where('coupon_name', $request->coupon_code)->first();
            $coupon_discount = 0;
            
            if (!empty($request->coupon_code)) {
            
                $coupon = Coupon::where('coupon_name', $request->coupon_code)->first();
            
                if ($coupon) {
            
                    if ($coupon->coupon_type == 1) {
                        $coupon_discount = $coupon->amount;
            
                    } elseif ($coupon->coupon_type == 2) {
                        $coupon_discount = round(($subtotal * $coupon->amount) / 100);
                    }
            
                    // if ($coupon_discount > $subtotal) {
                    //     $coupon_discount = $subtotal;
                    // }
                }
            }
            
            

            if (!$shipping_area) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid shipping area',
                ], 422);
            }

            // Total Amount
            $totalAmount = $subtotal + $shippingfee - $coupon_discount;
            

            //     CREATE ORDER
            $order = new Order();
            $order->invoice_id       = 0;
            $order->amount           = $totalAmount;
            $order->discount         = 0;
            $order->coupon_name      = $request->coupon_name;
            $order->coupon_discount  = $coupon_discount;
            $order->shipping_charge  = $shippingfee;
            $order->paid_partial_payment_amount = $request->paid_partial_payment_amount ?? 0;
            $order->payment_due_amount = $totalAmount;
            $order->customer_id     = $user_id;
            $order->order_status     = 1;
            $order->note             = $request->note;
            if($request->flash_sale_discount_price){
                $order->flash_sale_title = $setting->flash_sale_title;
                $order->flash_sale_discount_amount  = $request->flash_sale_discount_price;
                $order->flash_sale_discount_percentage  = $setting->flash_sale_percentage;
            }
            $order->save();
            
            // $order->update([
            //     'invoice_id' => 2000 + $order->id
            // ]);
            $order->invoice_id = 'ORD-' . 20000 + $order->id;
            $order->save();

            //     SHIPPING INFO
            $shipping = new Shipping();
            $shipping->order_id    = $order->id;
            $shipping->customer_id = $user_id;
            $shipping->name        = $request->name;
            $shipping->phone       = $request->phone;
            $shipping->address     = $request->address;
            $shipping->district     = $request->district;
            $shipping->city         = $request->city;
            $shipping->area        = $shipping_area->name;
            $shipping->save();

            //     PAYMENT INFO
            $payment = new Payment();
            $payment->order_id       = $order->id;
            $payment->customer_id    = $user_id;
            $payment->payment_method = $request->payment_method;
            $payment->amount         = $order->amount;
            $payment->payment_status = 'pending';
            $payment->payment_id = 'GP' . uniqid();
            $payment->save();

            //  ORDER DETAILS
            foreach ($buyItems as $buy) {
                // dd($buy);
                foreach ($buy->buydetails as $detail) {

                    $orderDetails = new OrderDetails();
                    $orderDetails->order_id       = $order->id;
                    $orderDetails->product_id     = $buy->product_id;
                    $orderDetails->product_name   = Product::find($buy->product_id)->name;
                    $orderDetails->product_color  = $detail->color;
                    $orderDetails->product_color_image  = $detail->color_image;
                    $orderDetails->product_size   = $detail->size;
                    $orderDetails->qty            = $detail->quantity;
                    $orderDetails->sale_price     = $detail->price;
                    $orderDetails->regular_price     = $detail->regular_price;
                    $orderDetails->purchase_price = 0;
                    $orderDetails->save();

                    // STOCK UPDATE
                    $product = Product::find($buy->product_id);

                    
                    Productsize::where('product_id', $buy->product_id)
                        ->where('size', $detail->size)
                        ->decrement('stock', $detail->quantity);
                    
                }
            }
            


            //     CLEAR buy
            BuyDetail::whereIn('buy_id', $buyItems->pluck('id'))->delete();
            Buy::where('user_id', $user_id)->delete();
            
            DB::commit();
            
            return response()->json([
                'status'  => 'success',
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'validated_input' => $validated,
                
            ], 201);
        } catch (ValidationException $e) {
             DB::rollBack();
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
             DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
