<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BulkQuantity;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\ShippingCharge;
use App\Models\Subcategory;
use App\Models\Review;
use App\Models\CartDetails;
use App\Models\GeneralSetting;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function frontCategoryProducts(Request $request)
    {
        $this->trackVisitor($request);

        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        // $products = Category::where(['front_view' => 1, 'status' => 1])
        //     ->orderBy('id', 'ASC')
        //     ->with(['products', 'products.image', 'products.prosize', 'products.procolor'])
        //     ->get();
            
        $categories = Category::where(['front_view' => 1, 'status' => 1])
            ->orderBy('id', 'ASC')
            ->with(['products' => function($q){
                $q->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price');
            }, 'products.image'])
            ->select('id', 'name', 'slug', 'image')
            ->get();
        
        $setting = GeneralSetting::select(
            'id',
            'flash_sale_title',
            'flash_sale_percentage',
            'flash_sale_start_date',
            'flash_sale_end_date'
        )->first();
        
        foreach ($categories as $category) {
            foreach ($category->products as $product) {
        
                if (
                    $product->topsale == 1 &&
                    $today->between(
                        Carbon::parse($setting->flash_sale_start_date),
                        Carbon::parse($setting->flash_sale_end_date)
                    )
                ) {
        
                    // $product->flash_sale = [
                    //     'title' => $setting->flash_sale_title,
                    //     'percentage' => $setting->flash_sale_percentage,
                    //     'flash_sale_price' => $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100),
                    //     'start_date' => $setting->flash_sale_start_date,
                    //     'end_date' => $setting->flash_sale_end_date,
                    // ];
                    
                    $product->new_price = $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100);
        
                }
        
            }
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Products',
            'data' => $categories
        ], 200);
    }

    private function trackVisitor($request)
    {
        $ip = $request->ip();
        $date = Carbon::today()->toDateString();

        Visitor::firstOrCreate([
            'ip' => $ip,
            'visit_date' => $date,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        $keyword = $request->keyword;

        $products = Product::where('status', 1)
            ->where('name', 'LIKE', "%{$keyword}%")
            ->with('images')
            ->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price')
            ->get();
            // ->paginate(16);
            
        foreach($products as $product){
            if (
                    $product->topsale == 1 &&
                    $today->between(
                        Carbon::parse($setting->flash_sale_start_date),
                        Carbon::parse($setting->flash_sale_end_date)
                    )
                ) {
        
                    // $product->flash_sale = [
                    //     'title' => $setting->flash_sale_title,
                    //     'percentage' => $setting->flash_sale_percentage,
                    //     'flash_sale_price' => $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100),
                    //     'start_date' => $setting->flash_sale_start_date,
                    //     'end_date' => $setting->flash_sale_end_date,
                    // ];
                    $product->new_price = $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100);
        
                }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Search Result for "' . $keyword . '"',
            'data' => $products,
        ], 200);
    }

   public function productDetails($slug)
    {
        $today = Carbon::today();
        $setting = GeneralSetting::first();
            
        $details = Product::where(['slug' => $slug, 'status' => 1])
            ->with('image')
            ->firstOrFail();
    
        // $shippingCharge = ShippingCharge::where('status', 1)->get();
    
        $productColors = Productcolor::where('product_id', $details->id)
        ->with(['color'])
        ->get()
        ->map(function ($item) {
    
            // Relation এ product_id দেওয়া যায় না, তাই এখানে manually load
            $item->sizes = Productsize::where('product_id', $item->product_id)
                                ->where('color_id', $item->color_id)
                                ->with('size')
                                ->get();
            
    
            $item->color_qty = Auth::guard('customer')->check()
                ? (CartDetails::where('user_id', Auth::guard('customer')->user()->id)
                                ->where('product_id', $item->product_id)
                                ->where('color_id', $item->color_id)
                                ->value('quantity') ?? 0)
                : 0;
    
            return $item;
        });
        
        if($details->topsale == 1 && 
            $today->between(
                Carbon::parse($setting->flash_sale_start_date),
                Carbon::parse($setting->flash_sale_end_date)
            )){
                
            $setting = GeneralSetting::select('id', 'flash_sale_title', 'flash_sale_percentage', 'flash_sale_start_date', 'flash_sale_end_date')->first();
            foreach($productColors as $products){
                foreach($products->sizes as $value){
                    $value->SalePrice = $value->RegularPrice - round(($value->RegularPrice * $setting->flash_sale_percentage) / 100);
                }
            }
        }else{
            $setting = [];
        }
        
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Product Details',
            'data'    => [
                'product'        => $details,
                'productColors'  => $productColors,
                // productSizes আলাদা পাঠানোর দরকার নেই
                // প্রতিটি color এর ভেতরে sizes আছে
                'flashSale' => $setting
            ],
        ], 200);
    }

    public function relatedProducts($slug)
    {
        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        $product = Product::where('slug', $slug)->first();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('image')
            ->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price')
            ->limit(8)
            ->get();
        
        foreach($relatedProducts as $product){
            if (
                    $product->topsale == 1 &&
                    $today->between(
                        Carbon::parse($setting->flash_sale_start_date),
                        Carbon::parse($setting->flash_sale_end_date)
                    )
                ) {
        
                    // $product->flash_sale = [
                    //     'title' => $setting->flash_sale_title,
                    //     'percentage' => $setting->flash_sale_percentage,
                    //     'flash_sale_price' => $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100),
                    //     'start_date' => $setting->flash_sale_start_date,
                    //     'end_date' => $setting->flash_sale_end_date,
                    // ];
                    
                    $product->new_price = $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100);
        
                }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Related Products',
            'data' => $relatedProducts,
        ], 200);
    }

    public function categoryProducts($slug)
    {
        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        $category = Category::where('slug', $slug)->first();

        $products = Product::where('status', 1)
            ->where('category_id', $category->id)
            ->with('images')
            ->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price')
            ->get();
            
        foreach($products as $product){
            if (
                    $product->topsale == 1 &&
                    $today->between(
                        Carbon::parse($setting->flash_sale_start_date),
                        Carbon::parse($setting->flash_sale_end_date)
                    )
                ) {
        
                    // $product->flash_sale = [
                    //     'title' => $setting->flash_sale_title,
                    //     'percentage' => $setting->flash_sale_percentage,
                    //     'flash_sale_price' => $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100),
                    //     'start_date' => $setting->flash_sale_start_date,
                    //     'end_date' => $setting->flash_sale_end_date,
                    // ];
                    $product->new_price = $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100);
        
                }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Products for Category "' . $category->name . '"',
            'data' => $products,
        ], 200);
    }

    public function subcategoryProducts($slug)
    {
        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        $subcategory = Subcategory::where('slug', $slug)->first();

        $products = Product::where('status', 1)
            ->where('subcategory_id', $subcategory->id)
            ->with('images')
            ->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price')
            ->get();
            
        foreach($products as $product){
            if (
                    $product->topsale == 1 &&
                    $today->between(
                        Carbon::parse($setting->flash_sale_start_date),
                        Carbon::parse($setting->flash_sale_end_date)
                    )
                ) {
        
                    // $product->flash_sale = [
                    //     'title' => $setting->flash_sale_title,
                    //     'percentage' => $setting->flash_sale_percentage,
                    //     'flash_sale_price' => $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100),
                    //     'start_date' => $setting->flash_sale_start_date,
                    //     'end_date' => $setting->flash_sale_end_date,
                    // ];
                    $product->new_price = $product->old_price - round(($product->old_price * $setting->flash_sale_percentage) / 100);
        
                }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Products for Subcategory "' . $subcategory->subcategoryName . '"',
            'data' => $products,
        ], 200);
    }

    public function childcategoryProducts($slug)
    {
        $childcategory = Childcategory::where('slug', $slug)->first();

        $products = Product::where('status', 1)
            ->where('childcategory_id', $childcategory->id)
            ->with('images')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products for Childcategory "' . $childcategory->childcategoryName . '"',
            'data' => $products,
        ], 200);
    }

    public function addProductReview(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email',
                'ratting'    => 'required|numeric|min:1|max:5',
                'review'     => 'required|string|max:1000',
                'product_id' => 'required|integer|exists:products,id',
                'image'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $review = new Review();
            $review->name = $request->name;
            $review->email = $request->email;
            $review->ratting = $request->ratting;
            $review->review = $request->review;
            $review->product_id = $request->product_id;

            // image upload
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'public/uploads/review/';
                $image->move($imagePath, $imageName);

                $review->image = $imagePath . $imageName;
            }

            $review->status = 'pending';
            $review->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product Review added successfully',
                'data'    => $review,
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function productreviewList(Request $request)
    {
        try {

            $review = Review::query()->where('status', 'active');

            // filter by product id
            if ($request->has('product_id')) {
                $review->where('product_id', $request->product_id);
            }

            $reviews = $review->orderBy('id', 'DESC')->paginate(10);

            return response()->json([
                'status'  => 'success',
                'message' => 'Product reviews fetched successfully',
                'data'    => $reviews,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function bulkquantities($product_id)
    {
        $bulkquantities = BulkQuantity::where('product_id',$product_id)->get();
        $product = Product::where('id', $product_id)->first();
        $today = Carbon::today();
        $setting = GeneralSetting::first();
        
        if (
            $product->topsale == 1 &&
            $today->between(
                Carbon::parse($setting->flash_sale_start_date),
                Carbon::parse($setting->flash_sale_end_date)
            )
        ){
            foreach($bulkquantities as $bulk){
                $bulk['flash_sale_price'] = $bulk->price - round(($bulk->price * $setting->flash_sale_percentage) / 100);
            }
        }
        
        return response()->json([
            'status'  => 'success',
            'message' => 'Product Bulk Quantities fetched successfully',
            'data'    => $bulkquantities,
        ], 200);
    }
    
    public function flashSale()
    {
        $setting = GeneralSetting::first();
        $today = Carbon::today();
        if($today->between(
                Carbon::parse($setting->flash_sale_start_date),
                Carbon::parse($setting->flash_sale_end_date)
        )){
                                
            $product = Product::where('status',1)
                ->where('topsale', 1)
                ->with('image')
                ->select('id', 'name', 'slug', 'category_id', 'new_price', 'old_price')
                ->paginate(12);
            
            $product->getCollection()->transform(function ($item) use ($setting) {
                $item->flash_sale_percentage = $setting->flash_sale_percentage;
                $item->new_price = $item->old_price - round(($item->old_price * $setting->flash_sale_percentage) / 100) ;
                return $item;
            });

        }
        
        return response()->json([
            'status'  => 'success',
            'message' => 'Flash Sale Product',
            'data'    =>$product ?? []
        ], 200);
    }
    
    
}
