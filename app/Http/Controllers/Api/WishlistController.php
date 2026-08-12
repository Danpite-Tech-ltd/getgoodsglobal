<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function wishlist()
    {
        $user = Auth::guard('customer')->user();
    
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: customer not logged in'
            ], 401);
        }
    
        $wishlist = Wishlist::where('user_id', $user->id)
            ->with('product', 'product.image') 
            ->get();
    
        return response()->json([
            'status' => true,
            'message' => 'Wishlist loaded successfully',
            'data' => $wishlist,
        ], 200);
    }



    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $user = auth()->user(); 

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user',
            ], 401);
        }

        $user_id = $user->id;
        $product_id = $request->product_id;

        $exist = Wishlist::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($exist) {
            return response()->json([
                'status' => false,
                'message' => 'Product already exists in wishlist',
            ], 422);
        }

        $wishlist = Wishlist::create([
            'user_id' => $user_id,
            'product_id' => $product_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added to wishlist',
            'data' => $wishlist,
        ], 200);
    }



    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user'
            ], 401);
        }

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found in wishlist'
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from wishlist'
        ], 200);
    }
}
