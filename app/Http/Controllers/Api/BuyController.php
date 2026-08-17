<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Buy;
use App\Models\BuyDetail;
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

class BuyController extends Controller
{

    public function productAddToBuy(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $today = Carbon::today();

            $customer = $request->user();

            $validated = $request->validate([
                'product_id' => 'required|integer',
                // 'shippingcharge_id' => 'required|integer',
                'buy_details' => 'required|array',
                'buy_details.*.size' => 'required|string',
                'buy_details.*.color_id' => 'required|integer',
                'buy_details.*.quantity' => 'required|integer|min:1',
                'total_quantity' => 'sometimes|integer|min:1' // optional for bulk pricing
            ]);
            // return response()->json([
            //         'status' => 'error',
            //         'message' => $request->total_quantity
            //     ], 404);

            $buy_exist = Buy::where('user_id',Auth::guard('customer')->user()->id)->exists();
            if(isset($buy_exist)){
                Buy::where('user_id',Auth::guard('customer')->user()->id)->delete();
                BuyDetail::where('user_id',Auth::guard('customer')->user()->id)->delete();
            }


            // Fetch flash sale
            $setting = GeneralSetting::first();

            // Fetch product
            $product = Product::find($validated['product_id']);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid product'
                ], 404);
            }

            // product image
            $productImage = Productimage::where('product_id', $product->id)->value('image');


            $buy = Buy::firstOrCreate(
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

            foreach ($validated['buy_details'] as $detail) {

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
                $buyDetailQuery = BuyDetail::where('user_id', $customer->id)
                    ->where('buy_id', $buy->id)
                    ->where('size', $detail['size'])
                    ->where('color_id', $productColor->color_id);

                if ($buyDetailQuery->exists()) {
                    $buyDetail = $buyDetailQuery->first();
                    $buyDetail->quantity = $detail['quantity'];
                    $buyDetail->save();
                } else {
                    $buyDetail = new BuyDetail();
                    $buyDetail->buy_id = $buy->id;
                    $buyDetail->user_id = $customer->id;
                    $buyDetail->product_id = $product->id;
                    $buyDetail->size = $detail['size'];
                    $buyDetail->color_id = $productColor->color_id;
                    $buyDetail->color = $productColor->color;
                    $buyDetail->color_image = $productColor->Image;
                    $buyDetail->quantity = $detail['quantity'];

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

                        $buyDetail->price = round($price);
                        $buyDetail->regular_price = $bulkqty ? $bulkqty->price : $sizeData->SalePrice;

                    } else {

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

                            $buyDetail->price = round($finalPrice);
                            $buyDetail->regular_price = $sizeData->RegularPrice;

                        } else {
                            $buyDetail->price = $sizeData->SalePrice;
                            $buyDetail->regular_price = $sizeData->RegularPrice;
                        }

                    }

                    $buyDetail->save();
                }

                $savedDetails[] = $buyDetail;



            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Added to buy successfully',
                'buy' => $buy,
                'buy_details' => $savedDetails
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


    public function buyProducts()
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

            $buyItems = Buy::where('user_id', $user->id)
                ->with('buydetails')
                ->get();

            $discount = [];
            $discount['product_price'] = 0;
            $discount['final_price'] = 0;
            // calculate prices from cart items
            foreach ($buyItems as $buy) {
                foreach ($buy->buydetails as $detail) {
                    $discount['product_price'] += $detail->quantity * $detail->regular_price;
                    $discount['final_price'] += $detail->quantity * $detail->price;

                    $sizeData = Productsize::where('product_id', $detail->product_id)
                        ->where('size', $detail->size)
                        ->first();

                    $detail->stock = $sizeData ? $sizeData->stock : 0;
                }
            }

           $discount['discount'] = $discount['product_price'] - $discount['final_price'];


            if ($buyItems->isEmpty()) {
                return response()->json([
                    'status'  => 'empty',
                    'message' => 'Buy is empty',
                    'data'    => [],
                ], 200);
            }

            return response()->json([
                'status'    => 'success',
                'message'   => 'Buy Products',
                'data'      => $buyItems,
                'priceSummary' => $discount ?? [],
                'order_condition' => $setting->order_condition
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    // public function buyProducts()
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

    //         $buyItems = Buy::where('user_id', $user->id)
    //             ->with('buydetails')
    //             ->get();

    //         $discount = [];
    //         $discount['product_price'] = 0;
    //         $discount['final_price'] = 0;
    //         // calculate prices from cart items
    //         foreach ($buyItems as $buy) {
    //             foreach ($buy->buydetails as $detail) {
    //                 $discount['product_price'] += $detail->quantity * $detail->regular_price;
    //                 $discount['final_price'] += $detail->quantity * $detail->price;
    //             }
    //         }

    //        $discount['discount'] = $discount['product_price'] - $discount['final_price'];


    //         if ($buyItems->isEmpty()) {
    //             return response()->json([
    //                 'status'  => 'empty',
    //                 'message' => 'Buy is empty',
    //                 'data'    => [],
    //             ], 200);
    //         }

    //         return response()->json([
    //             'status'    => 'success',
    //             'message'   => 'Buy Products',
    //             'data'      => $buyItems,
    //             'priceSummary' => $discount ?? [],
    //             'order_condition' => $setting->order_condition
    //         ], 200);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Something went wrong',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }



}
