<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Productsize;
use App\Models\Productcolor;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\BulkQuantity;
use Carbon\Carbon;

class CartController extends Controller
{
    // display none
    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            $customer = $request->user();

            $validated = $request->validate([
                'product_id' => 'required|integer',
                'color' => 'required|string',
                'shippingcharge_id' => 'required|integer',
                'cart_details' => 'required|array',
                'cart_details.*.size' => 'required|string',
                'cart_details.*.quantity' => 'required|integer|min:1',
            ]);

            // Fetch product image safely
            $productImage = Productimage::where('product_id', $validated['product_id'])->first()->image;

            // Fetch product name safely
            $product = Product::find($validated['product_id']);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid product ID'
                ], 404);
            }

            // Create Cart
            $cart = new Cart();
            $cart->user_id           = $customer->id;
            $cart->product_id        = $validated['product_id'];
            $cart->color             = $validated['color'];
            $cart->shippingcharge_id = $validated['shippingcharge_id'];
            $cart->image             = $productImage;
            $cart->product_name      = $product->name;
            $cart->save();

            // Save Cart Details
            $savedDetails = [];
            foreach ($validated['cart_details'] as $detail) {

                // size price check
                $sizeData = Productsize::where('product_id', $validated['product_id'])
                    ->where('size', $detail['size'])
                    ->first();

                if (!$sizeData) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid size for this product: ' . $detail['size']
                    ], 400);
                }

                $cartDetail = new CartDetails();
                $cartDetail->cart_id  = $cart->id;
                $cartDetail->size     = $detail['size'];
                $cartDetail->quantity = $detail['quantity'];
                if ($product->order_by == 1) {
                    $total_quantity = $request->total_quantity;
                    $bulkqty = BulkQuantity::where('product_id', $validated['product_id'])
                        ->where('min_qty', '<=', $total_quantity)
                        ->where('max_qty', '>=', $total_quantity)
                        ->first();

                    if ($bulkqty) {
                        $cartDetail->price = $bulkqty->price;
                    }
                } else {
                    $cartDetail->price    = $sizeData->SalePrice;
                }

                $cartDetail->save();

                $savedDetails[] = $cartDetail;
            }

             DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Added To Cart',
                'cart' => $cart,
                'cart_details' => $savedDetails,
            ], 201);
        } catch (ValidationException $e) {
             DB::rollBack();
            return response()->json([
                'status' => 'error',
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


    public function productAddToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            $today = Carbon::today();

            $customer = $request->user();

            $validated = $request->validate([
                'product_id' => 'required|integer',
                // 'shippingcharge_id' => 'required|integer',
                'cart_details' => 'required|array',
                'cart_details.*.size' => 'required|string',
                'cart_details.*.color_id' => 'required|integer',
                'cart_details.*.quantity' => 'required|integer|min:1',
                'total_quantity' => 'sometimes|integer|min:1' // optional for bulk pricing
            ]);



            // Fetch product
            $product = Product::find($validated['product_id']);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid product'
                ], 404);
            }

            // Fetch flash sale
            $setting = GeneralSetting::first();

            // product image
            $productImage = Productimage::where('product_id', $product->id)->value('image');


            $cart = Cart::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                ],
                [
                    // 'shippingcharge_id' => $validated['shippingcharge_id'],
                    'image' => $productImage,
                    'product_name' => $product->name,
                    'slug' => $product->slug,
                ]
            );

            $savedDetails = [];

            foreach ($validated['cart_details'] as $detail) {

                // Validate size
                $sizeData = Productsize::where('product_id', $product->id)
                    ->where('size', $detail['size'])
                    ->first();
                if (!$sizeData) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid size: ' . $detail['size']
                    ], 400);
                }

                // Validate color
                $productColor = Productcolor::where('product_id', $product->id)
                    ->where('color_id', $detail['color_id'])
                    ->first();
                if (!$productColor) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid color'
                    ], 400);
                }
                //  same size + color already exists
                $cartDetailQuery = CartDetails::where('user_id', $customer->id)
                    ->where('cart_id', $cart->id)
                    ->where('size', $detail['size'])
                    ->where('color_id', $productColor->color_id);

                if ($cartDetailQuery->exists()) {
                    $cartDetail = $cartDetailQuery->first();
                    $cartDetail->quantity = $detail['quantity'];
                    $cartDetail->save();
                } else {
                    $cartDetail = new CartDetails();
                    $cartDetail->cart_id = $cart->id;
                    $cartDetail->user_id = $customer->id;
                    $cartDetail->product_id = $product->id;
                    $cartDetail->size = $detail['size'];
                    $cartDetail->color_id = $productColor->color_id;
                    $cartDetail->color = $productColor->color;
                    $cartDetail->color_image = $productColor->Image;
                    $cartDetail->quantity = $detail['quantity'];

                    // Price set
                    if ($product->order_by == 1 && isset($validated['total_quantity'])) {
                        $total_quantity = $validated['total_quantity'];

                        $bulkqty = BulkQuantity::where('product_id', $product->id)
                            ->where('min_qty', '<=', $total_quantity)
                            ->where('max_qty', '>=', $total_quantity)
                            ->first();

                        $price = $bulkqty ? $bulkqty->price : $sizeData->SalePrice;


                        if (
                            $product->topsale == 1 &&
                            Carbon::today()->between(
                                Carbon::parse($setting->flash_sale_start_date),
                                Carbon::parse($setting->flash_sale_end_date)
                            )
                        ) {
                            $price -= (($price * $setting->flash_sale_percentage) / 100);
                        }

                        $cartDetail->price = round($price);
                        $cartDetail->regular_price = $bulkqty ? $bulkqty->price : $sizeData->SalePrice;

                    } else {
                        // $cartDetail->price = $sizeData->SalePrice;
                        if (
                            $product->topsale == 1 &&
                            $today->between(
                                Carbon::parse($setting->flash_sale_start_date),
                                Carbon::parse($setting->flash_sale_end_date)
                            )
                        ) {

                            $salePrice = $sizeData->RegularPrice;
                            $discountPercentage = $setting->flash_sale_percentage;

                            $discountAmount = (($salePrice * $discountPercentage) / 100);
                            $finalPrice = $salePrice - $discountAmount;

                            $cartDetail->price = round($finalPrice);
                            $cartDetail->regular_price = $sizeData->RegularPrice;

                        } else {
                            $cartDetail->price = $sizeData->SalePrice;
                            $cartDetail->regular_price = $sizeData->RegularPrice;
                        }
                    }

                    $cartDetail->save();
                }

                $savedDetails[] = $cartDetail;



            }


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Added to cart successfully',
                'cart' => $cart,
                'cart_details' => $savedDetails
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

     public function productGetqty(Request $request)
    {
        try {

            $user = Auth::guard('customer')->user();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated',
                    'data'    => [],
                ], 401);
            }

            $cartItems = CartDetails::where('user_id', $user->id)
                ->where('product_id',$request->product_id)
                ->where('color_id',$request->color_id)
                ->where('size', $request->size)
                ->get();

            // $sizes = Productsize::where('product_id',$request->product_id)
            //     ->where('size',$request->size)
            //     ->get();

            // $colors = Productcolor::where('product_id',$request->product_id)
            //     ->where('color_id',$request->color_id)
            //     ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status'  => 'empty',
                    'message' => 'Qty Product is empty',
                    'data'    => [],
                ], 200);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Qty Products',
                'data'    => $cartItems,
                // 'size'    => $sizes,
                // 'color'   => $colors,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function cartProducts()
    {
        try {
            $user = Auth::guard('customer')->user();
            $today = Carbon::today();
            $setting = GeneralSetting::first();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated',
                    'data'    => [],
                ], 401);
            }

            $cartItems = Cart::where('user_id', $user->id)
                ->with('cartdetails')
                ->get();

            // যদি cart empty হয়
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status'  => 'empty',
                    'message' => 'Cart is empty',
                    'data'    => []
                ], 200);
            }

            $discount = [
                'product_price' => 0,
                'final_price'   => 0,
                'discount'      => 0,
            ];

            foreach ($cartItems as $cart) {
                foreach ($cart->cartdetails as $detail) {
                    // Calculate prices
                    $discount['product_price'] += $detail->quantity * $detail->regular_price;
                    $discount['final_price'] += $detail->quantity * $detail->price;

                    $sizeData = Productsize::where('product_id', $detail->product_id)
                        ->where('size', $detail->size)
                        ->first();

                    $detail->stock = $sizeData ? $sizeData->stock : 0;
                }
            }

            $discount['discount'] = $discount['product_price'] - $discount['final_price'];

            return response()->json([
                'status'       => 'success',
                'message'      => 'Cart Products',
                'data'         => $cartItems,
                'priceSummary' => $discount
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    // public function cartProducts()
    // {
    //     try {

    //         $user = Auth::guard('customer')->user();
    //         $today = Carbon::today();
    //         $setting = GeneralSetting::first();

    //         if (!$user) {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'Unauthenticated',
    //                 'data'    => [],
    //             ], 401);
    //         }

    //         $cartItems = Cart::where('user_id', $user->id)
    //             ->with('cartdetails')
    //             ->get();

    //         $discount = [];
    //         $discount['product_price'] = 0;
    //         $discount['final_price'] = 0;
    //         // calculate prices from cart items
    //         foreach ($cartItems as $cart) {
    //             foreach ($cart->cartdetails as $detail) {
    //                 $discount['product_price'] += $detail->quantity * $detail->regular_price;
    //                 $discount['final_price'] += $detail->quantity * $detail->price;
    //             }
    //         }

    //        $discount['discount'] = $discount['product_price'] - $discount['final_price'];


    //         // যদি cart empty হয়
    //         if ($cartItems->isEmpty()) {
    //             return response()->json([
    //                 'status'  => 'empty',
    //                 'message' => 'Cart is empty',
    //                 'data'    => []
    //             ], 200);
    //         }

    //         // Response
    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Cart Products',
    //             'data'    => $cartItems,
    //             'priceSummary' => $discount
    //         ], 200);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function cartOrderProducts(Request $request)
    {
        $request->validate([
            'cart_ids'   => 'required|array|min:1',
            'cart_ids.*' => 'integer'
        ]);

        $user = Auth::guard('customer')->user();
        $today = Carbon::today();
        $setting = GeneralSetting::first();

        $cartItems = Cart::where('user_id', $user->id)
            ->whereIn('id', $request->cart_ids)
            ->with('cartdetails')
            ->get();

        $discount = [];
            $discount['product_price'] = 0;
            $discount['final_price'] = 0;
            // calculate prices from cart items
            foreach ($cartItems as $cart) {
                foreach ($cart->cartdetails as $detail) {
                    $discount['product_price'] += $detail->quantity * $detail->regular_price;
                    $discount['final_price'] += $detail->quantity * $detail->price;
                }
            }

           $discount['discount'] = $discount['product_price'] - $discount['final_price'];

        return response()->json([
            'status'    => $cartItems->isEmpty() ? 'error' : 'success',
            'message'   => $cartItems->isEmpty() ? 'Cart is empty' : 'Cart Products',
            'data'      => $cartItems,
            'priceSummary' => $discount ?? [],
            'order_condition' => $setting->order_condition
        ]);
    }



public function cartRemove($id)
{
    DB::beginTransaction();

    try {
        $user_id = Auth::id();

        $cart = Cart::where('user_id', $user_id)
                    ->where('id', $id)
                    ->firstOrFail();

        CartDetails::where('cart_id', $cart->id)->delete();

        $cart->delete();

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Cart product removed successfully'
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => 'Failed to remove cart product',
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function cartDetailsUpdate(Request $request, $id){
    $cartDetails = CartDetails::find($id);
    $cartDetails->quantity = $request->quantity;
    $cartDetails->save();

    return response()->json([
                'status'  => 'success',
                'message' => 'Update To Cart',
            ], 201);
}

public function cartDetailsDelete($id)
{
    DB::beginTransaction();

    try {
        $cartDetail = CartDetails::findOrFail($id);

        $cart = Cart::findOrFail($cartDetail->cart_id);

        $cartDetailsCount = CartDetails::where('cart_id', $cart->id)->count();

        $cartDetail->delete();

        if ($cartDetailsCount === 1) {
            $cart->delete();
        }

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Cart item deleted successfully',
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Cart item not found',
        ], 404);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to delete cart item',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

}
