<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function wishlist()
    {
        $wishlistItems = Cart::instance('wishlist')->content();

        return view('frontEnd.layouts.pages.wishlist', compact('wishlistItems'));
    }

    public function addToWishlist(Request $request)
    {
        // Check if the product exists in the database
        $product = Product::find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check if the product is already in the wishlist
        $wishlistItem = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product->id;
        });

        if ($wishlistItem->isNotEmpty()) {
            return response()->json(['message' => 'Product already in Wishlist'], 200);
        }

        // Add the product to the wishlist
        Cart::instance('wishlist')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
            ],
        ]);
        $data = Cart::instance('wishlist')->count();
        return response()->json([
            'message' => 'Wishlist Added successfully',
            'count' => $data,
        ], 200);
    }

    public function delete_wishlist(Request $request)
    {
        // Remove the item from the wishlist
        Cart::instance('wishlist')->remove($request->id);
        $data = Cart::instance('wishlist')->count();

        return response()->json([
            'message' => 'Product removed from wishlist successfully',
            'count' => $data,
        ], 200);
    }
    public function moveToCart(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->new_price,
                'purchase_price' => $product->purchase_price,
                'product_size' => $request->product_size ?? '',
                'product_color' => $request->product_color ?? '',
                'pro_unit' => $product->pro_unit,
                'partial_pay' => $product->pre_booking,
            ],
        ]);

        // Remove the item from the wishlist
        Cart::instance('wishlist')->remove($request->rowId);
        $wishlist = Cart::instance('wishlist')->count();
        $cart = Cart::instance('shopping')->count();


        return response()->json([
            'message' => 'Product moved to cart successfully',
            'wishlist' => $wishlist,
            'cart_count' => $cart,
        ], 200);
    }
}
