<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(){
        $coupons = Coupon::all();
        return view('backEnd.coupon.coupon' , [
            'coupons' => $coupons,
        ]);
    }

    public function store(Request $request){
        Coupon::insert([
            'coupon_name' => $request->coupon_name,
            'coupon_type' => $request->coupon_type,
            'amount' => $request->amount,
            'count' => $request->quantity,
            'validity' => $request->validity,
            'unique_id' => $request->coupon_type . uniqid() . $request->amount . $request->validity,
            'created_at' => Carbon::now(),
        ]);
        return back()->with('success' , 'Coupon added successfully');
    }
    
    public function update(Request $request, $id)
    {   
        // dd('djfhd');
        $request->validate([
            'coupon_name' => 'required',
            'coupon_type' => 'required',
            'amount' => 'required',
            'count' => 'required',
        ]);
    
        $coupon = Coupon::findOrFail($id);
    
        $coupon->coupon_name = $request->coupon_name;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->amount = $request->amount;
        $coupon->count = $request->count;
        $coupon->validity = $request->validity;
    
        $coupon->save();
    
        return redirect()->back()->with('success', 'Coupon Updated!');
    }
    
    function delete($id){
        Coupon::find($id)->delete();
        CouponUser::where('coupon_id' , $id)->delete();
        return back()->with('cpn_dlt' , 'Coupon deleted successfully');
    }

    public function apply(Request $request){

        $coupon = $request->coupon;
        $pack = Coupon::where('coupon_name' , $coupon)->first();
        
        // $exists = CouponUser::where('customer_id', $request->customer)
        //     ->where('coupon_id', $pack->id)
        //     ->exists();

        // if ($exists) {
        //     return response()->json([ 'success' => false, 'message' => 'You Have Already Use This Coupon' ]);
        //     die();
        // }

        if($coupon == Coupon::where('coupon_name' , $coupon)->exists()){
            if ($pack->count > 0) {
                if ($pack->validity < Carbon::now()) {
                    return response()->json([ 'success' => false, 'message' => 'Expired Coupon' ]);
                }
                else{
                    if ($pack->coupon_type == 2) {
                        $total = $request->amount - (($request->amount / 100) * $pack->amount);
                        $discount =  ($request->amount / 100) * $pack->amount;      
                    }
                    else{
                        $total = $request->amount - $pack->amount;
                        $discount = $pack->amount;      
                    }
                    return response()->json([ 
                        'success' => true,
                        'message' => 'Coupon Added Successfully.',
                        'amount' => $total,
                        'discount' => round($discount),
                        'id' => $pack->id,
                    ]);
                }
            }
            else{
                return response()->json([ 'success' => false, 'message' => 'Coupon Usage Limit Reached!' ]);
            }
        }
        else{
            return response()->json([ 'success' => false, 'message' => 'Invalid Coupon' ]);
        }
    }

}
