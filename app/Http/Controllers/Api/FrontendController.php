<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Review;
use App\Models\GoogleTagManager;
use App\Models\EcomPixel;
use App\Models\CreatePage;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Subscription;
use App\Models\Testimonial;
use App\Models\OrderStatus;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomeDistrict;
use App\Models\CustomeThana;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Mail;
use DB;
use App\Models\SocialMedia;

class FrontendController extends Controller
{




     public function dashboardOverview()
    {
        $user_id = Auth::user()->id;
        // return $user_id;
        
        $allOrders = Order::where('customer_id', $user_id)->count();
        $pendingOrders = Order::where('customer_id', $user_id)
            ->whereIn('order_status', [1, 22])
            ->count();
        $ProcessingOrders = Order::where(['customer_id' => $user_id, 'order_status'=> 3])->count();
        $completeOrders = Order::where(['customer_id' => $user_id, 'order_status' => 9])->count();
        $dispachedOrders = Order::where(['customer_id' => $user_id, 'order_status' => 5])->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard Overview',
            'data' => [
                'allOrders' => $allOrders,
                'pendingOrders' => $pendingOrders,
                'ProcessingOrders' => $ProcessingOrders,
                'completeOrders' => $completeOrders,
                'dispachedOrders' => $dispachedOrders,
            ],
        ], 200);
    }

    public function userProfile()
    {
        $user = Auth::user();

        $customer = Customer::where('id', $user->id)->select('id','name', 'email', 'phone', 'city', 'district', 'address', 'image')->first();

        return response()->json([
            'status' => true,
            'message' => 'User Profile',
            'data' => $customer,
        ]);
    }

    // public function userOrderHistory()
    // {
    //     $user = Auth::user();

    //     $orders = Order::where('customer_id', $user->id)->with('orderDetails', 'status')->get();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User Order History',
    //         'data' => $orders,
    //     ]);
    // }
    public function userOrderHistory(Request $request)
    {
        $user_id = Auth::id();
        $keyword = $request->keyword;
        $status  = $request->status;
    
        $query = Order::where('customer_id', $user_id)
            ->select(
                'id',
                'invoice_id',
                'amount',
                'paid_partial_payment_amount',
                'payment_due_amount',
                'order_status',
                'created_at'
            )
            ->with([
                'orderDetails' ,
                // => function ($q) {
                //     $q->select(
                //         'id',
                //         'order_id'
                //     );
                // },
                'status' 
            ]);
    
        // Status filter (only if provided)
        if (!is_null($status)) {
            $query->where('order_status', $status);
        }
    
        // Invoice search (optional)
        if (!empty($keyword)) {
            $query->where('invoice_id', 'like', "%{$keyword}%");
        }
    
        $orders = $query->latest()->get();
    
        return response()->json([
            'status'  => true,
            'message' => 'Order History List',
            'data'    => $orders
        ]);
    }
    
    
    public function orderStatus()
    {

        $orders = OrderStatus::where('status', 1)->get();

        return response()->json([
            'status' => true,
            'message' => 'User Order History',
            'data' => $orders,
        ]);
    }

    public function userSettings(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($user->id),
            ],

            'emergency_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers', 'phone')->ignore($user->id),
            ],
            'address' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $imageName = $user->image;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('settings/'), $imageName);
        }

        Customer::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->emergency_number,
            'address' => $request->address,
            'district' => $request->district,
            'city' => $request->city,
            'image' => $imageName ? 'public/settings/' . $imageName : $user->image
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Settings Updated',
        ]);
    }

    public function userUpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([

                'status' => false,
                'message' => 'Current password is incorrect',
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully',
        ]);
    }


    public function shippingArea()
    {
        $shippingArea = ShippingCharge::all();

        return response()->json([
            'status' => true,
            'message' => 'Shipping Area',
            'data' => $shippingArea,
        ]);
    }
    
   public function payments()
    {
        $user_id = Auth::id();
    
        $payments = Payment::with('order', 'order.status')
                    ->where('customer_id', $user_id)
                    ->select('id', 'order_id', 'amount', 'payment_method', 'payment_id', 'payment_status', 'created_at', 'updated_at')
                    ->latest()
                    ->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Payments List',
            'data' => $payments
        ]);
    }
    
    public function refunds(){
        $user_id = Auth::id();
    
        $refunds = Order::where('customer_id', $user_id)->where('order_status', 17)
                    ->select('id', 'invoice_id', 'paid_partial_payment_amount', 'refund_paid_amount', 'admin_note', 'created_at', 'updated_at')
                    ->latest()
                    ->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Refunds List',
            'data' => $refunds
        ]);
        
    }
    
    public function delivery(Request $request)
    {
        $user_id = Auth::id();
        $keyword = $request->keyword;
        $status = $request->status;
    
        // Start query builder for the user's orders
        $query = Order::where('customer_id', $user_id)
                      ->select('id', 'invoice_id', 'amount', 'paid_partial_payment_amount', 'order_status', 'payment_due_amount', 'shipping_method', 'created_at', 'updated_at')
                      ->with(['shipping' => function($query) {
                          $query->select('id', 'order_id', 'area'); 
                      }]);
    
        // Filter by slug
        // cancel
        if ($status == 1) {
            $query->where('order_status', 4);
        }
        
        // delivered
        if ($status == 2) {
            $query->where('order_status', 9);
        }
        
        // on-delivery
        if ($status == 3) {
            $query->where('order_status', 10);
        }
        
        // processing
        if ($status == 4) {
            $query->where('order_status', 2);
        }

    
        // Filter by keyword if provided
        if ($keyword) {
            $query->where('invoice_id', 'like', "%{$keyword}%");
        }
    
        $orders = $query->latest()->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Delivery List',
            'data' => $orders
        ]);
    }

    public function invoice($invoice_id){
        $user_id = Auth::id();
        $orders = Order::where('customer_id', $user_id)->where('invoice_id', $invoice_id)->with('orderDetails', 'status', 'shipping')->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Invoice',
            'data' => $orders
        ]);
    }
    
    public function district()
    {
        $districts = CustomeDistrict::select('id','districtName')->get();

        return response()->json([
            'status' => true,
            'message' => 'District',
            'data' => $districts,
        ]);
    }
    public function thana()
    {
        $thanas = CustomeThana::select('id','district_id','thanaName')->get();

        return response()->json([
            'status' => true,
            'message' => 'Thana',
            'data' => $thanas,
        ]);
    }


}
