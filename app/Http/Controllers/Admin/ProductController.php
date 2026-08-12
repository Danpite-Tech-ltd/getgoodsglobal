<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Productprice;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Toastr;
use File;
use Str;
use Image;
// use DB;

class ProductController extends Controller
{
    public function getSubcategory(Request $request)
    {
        $subcategory = DB::table("subcategories")
            ->where("category_id", $request->category_id)
            ->pluck('subcategoryName', 'id');
        return response()->json($subcategory);
    }
    public function getChildcategory(Request $request)
    {
        $childcategory = DB::table("childcategories")
            ->where("subcategory_id", $request->subcategory_id)
            ->pluck('childcategoryName', 'id');
        return response()->json($childcategory);
    }


    // function __construct()
    // {
    //     $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    // }


    public function index(Request $request)
    {
        if ($request->keyword) {
            $data = Product::orderBy('id', 'DESC')->where('name', 'LIKE', '%' . $request->keyword . "%")->with('image', 'category')->paginate(50);
        } else {
            $data = Product::orderBy('id', 'DESC')->with('image', 'category')->paginate(50);
        }
        return view('backEnd.product.index', compact('data'));
    }
    public function create()
    {
        $categories = Category::where('parent_id', '=', '0')->where('status', 1)->select('id', 'name', 'status')->with('childrenCategories')->get();
        $brands = Brand::where('status', '1')->select('id', 'name', 'status')->get();
        $colors = Color::where('status', '1')->get();
        $sizes = Size::where('status', '1')->get();
        return view('backEnd.product.create', compact('categories', 'brands', 'colors', 'sizes'));
    }
  public function store(Request $request)
{
    DB::beginTransaction();
    // dd($request->all());

    try {
        // =============================================
        // Product slug & code
        // =============================================
        $last_id = Product::orderBy('id', 'desc')->value('id');
        $last_id = $last_id ? $last_id + 1 : 1;
    
        $product                  = new Product();
        $product->name            = $request->name;
        $product->slug            = strtolower(preg_replace('/[\/\s]+/', '-', $request->name . '-' . $last_id));
        $product->product_code    = 'P' . str_pad($last_id, 4, '0', STR_PAD_LEFT);
        $product->brand_id        = $request->brand_id;
        $product->order_by        = $request->order_by;
        $product->category_id     = $request->category_id;
        $product->subcategory_id  = $request->subcategory_id;
        $product->childcategory_id= $request->childcategory_id;
        $product->new_price       = $request->new_price;
        $product->old_price       = $request->old_price;
        $product->stock           = $request->stock;
        $product->pro_unit        = $request->pro_unit;
        $product->pro_video       = $request->pro_video;
        $product->description     = $request->description;
        $product->short_des       = $request->short_des;
        $product->product_weight  = $request->product_weight;
        $product->prebooking      = $request->prebooking;
        $product->status          = $request->status;
        $product->topsale         = $request->topsale;
        $product->feature_product = $request->feature_product;
        $product->deal_of_theday  = $request->deal_of_theday;
        $product->type            = $request->type;
    
    
        // =============================================
        // Gallery / slider images
        // =============================================
        if ($request->hasFile('PostImage')) {
            $imageData = [];
            foreach ($request->file('PostImage') as $imgFile) {
                $name = time() . '_' . strtolower(preg_replace('/\s+/', '-', $imgFile->getClientOriginalName()));
                $imgFile->move(public_path('images/product/slider/'), $name);
                $imageData[] = $name;
            }
            $product->PostImage = json_encode($imageData);
        }
    
        $product->save();
    
    
        // =============================================
        // Main product thumbnail image
        // =============================================
        if ($request->hasFile('image')) {
            $image    = $request->file('image');
            $name     = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image->getClientOriginalName()));
            $path     = 'public/uploads/product/';
            $image->move($path, $name);
    
            $pimage             = new Productimage();
            $pimage->product_id = $product->id;
            $pimage->image      = $path . $name;
            $pimage->save();
        }
    
    
        // =============================================
        // Variant product — colors & sizes
        // =============================================
        if ($request->type != 0) {
    
            $variants = $request->input('variant', []);  // variant[colorID][colorID|color]
            $sizes    = $request->input('size', []);      // size[colorID][sizeID][field]
    
            // -- Save each color
            foreach ($variants as $colorID => $colorData) {
    
                $productColor             = new Productcolor();
                $productColor->product_id = $product->id;
                $productColor->color_id   = $colorData['colorID'];
                $productColor->color      = $colorData['color'];
                $productColor->specification      = $colorData['specification'];
    
                // Color image
                if ($request->hasFile('variant.' . $colorID . '.image')) {
                    $variantImg  = $request->file('variant.' . $colorID . '.image');
                    $imgName     = time() . '_' . strtolower(preg_replace('/\s+/', '-', $variantImg->getClientOriginalName()));
                    $imgPath     = 'public/images/variant/';
                    $variantImg->move($imgPath, $imgName);
                    $productColor->Image = $imgPath . $imgName;
                }
    
                $productColor->save();
    
    
                // -- Save sizes for this color
                if (!empty($sizes[$colorID])) {
                    foreach ($sizes[$colorID] as $sizeID => $sizeData) {
    
                        $productSize                = new Productsize();
                        
                        $last_size_id = Productsize::orderBy('id', 'desc')->value('id');
                        $last_size_id = $last_size_id ? $last_size_id + 1 : Uniqueid();
                        
                        $productSize->sku           = 'SKU-00' . $last_size_id;
                        $productSize->product_id    = $product->id;
                        $productSize->color_id      = $colorID;   // link size to color
                        $productSize->size_id       = $sizeData['sizeID'];
                        $productSize->size          = $sizeData['size'];
                        $productSize->stock         = $sizeData['stock'];
                        $productSize->total_stock         = $sizeData['stock'];
                        // $productSize->PurchasePrice = $sizeData['purchase_price'];
                        $productSize->SalePrice     = $sizeData['sale_price'];
                        $productSize->RegularPrice     = $sizeData['RegularPrice'];
                        $productSize->save();
                    }
                }
            }
    
        }
    
        DB::commit();
        // =============================================
        // Response
        // =============================================
        return response()->json([
            'status'  => 'success',
            'message' => 'Product Created Successfully',
        ]);
    
        } catch (\Exception $e) {
    
        DB::rollBack();
    
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}

    public function edit($id)
{
    $edit_data      = Product::with('images')->find($id);
    $categories     = Category::where('parent_id', '=', '0')->where('status', 1)->select('id', 'name', 'status')->get();
    $categoryId     = $edit_data->category_id;
    $subcategoryId  = $edit_data->subcategory_id;
    $subcategory    = Subcategory::where('category_id', '=', $categoryId)->select('id', 'subcategoryName', 'status')->get();
    $childcategory  = Childcategory::where('subcategory_id', '=', $subcategoryId)->select('id', 'childcategoryName', 'status')->get();
    $brands         = Brand::where('status', '1')->select('id', 'name', 'status')->get();
    $totalsizes     = Size::where('status', 1)->get();
    $totalcolors    = Color::where('status', 1)->get();

    // Color wise data — each color with its sizes grouped
    $varients = Productcolor::where('product_id', $id)->get();

    // sizes grouped by color_id so blade can render color-wise
    $sizes = Productsize::where('product_id', $id)
                ->get()
                ->groupBy('color_id');   // key: color_id => collection of sizes

    return view('backEnd.product.edit', compact(
        'edit_data', 'categories', 'subcategory', 'childcategory',
        'brands', 'varients', 'sizes', 'totalsizes', 'totalcolors'
    ));
}

    public function removevarient($id)
    {
        $variant = Productcolor::where('id', $id)->first();
        $variant->delete();
        $response['status'] = 'success';
        $response['message'] = 'Colour Varient Remove Sucessfully';
        return json_encode($response);
        die();
    }
    public function removesize($id)
    {
        $size = Productsize::where('id', $id)->first();
        $size->delete();
        $response['status'] = 'success';
        $response['message'] = 'Size /Weight Remove Sucessfully';
        return json_encode($response);
        die();
    }


    public function price_edit()
    {
        $products = DB::table('products')->select('id', 'name', 'status', 'price_two', 'price_two', 'stock')->where('status', 1)->get();;
        return view('backEnd.product.price_edit', compact('products'));
    }
    public function price_update(Request $request)
    {
        $ids = $request->ids;
        $oldPrices = $request->price_two;
        $newPrices = $request->price_two;
        $stocks = $request->stock;
        foreach ($ids as $key => $id) {
            $product = Product::select('id', 'name', 'status', 'price_two', 'price_two', 'stock')->find($id);

            if ($product) {
                $product->update([
                    'price_two' => $oldPrices[$key],
                    'price_two' => $newPrices[$key],
                    'stock' => $stocks[$key],
                ]);
            }
        }
        Toastr::success('Success', 'Price update successfully');
        return redirect()->back();
    }


    public function variant(Request $request)
    {
        if (isset($request['q'])) {
            $variants = Color::query()->where('colorName', 'like', '%' . $request['q'] . '%')->where('status', 1)->get();
        } else {
            $variants = Color::where('status', '1')->get();
        }
        $variant = array();
        foreach ($variants as $item) {
            $variant[] = array(
                "id" => $item['id'],
                "text" => $item['colorName']
            );
        }
        $data['data'] = $variant;
        return json_encode($data);
        die();
    }

    public function sizeweight(Request $request)
    {
        if (isset($request['q'])) {
            $variants = Size::query()->where('sizeName', 'like', '%' . $request['q'] . '%')->where('status', 1)->get();
        } else {
            $variants = Size::where('status', 1)->get();
        }
        $variant = array();
        foreach ($variants as $item) {
            $variant[] = array(
                "id" => $item['id'],
                "text" => $item['sizeName']
            );
        }
        $data['data'] = $variant;
        return json_encode($data);
        die();
    }


   public function update(Request $request)
{   
    // dd($request->all());
    $product = Product::find($request->product_id);

    $product->name            = $request->name;
    $product->product_code    = $request->product_code;
    $product->brand_id        = $request->brand_id;
    $product->order_by        = $request->order_by;
    $product->category_id     = $request->category_id;
    $product->new_price       = $request->new_price;
    $product->old_price       = $request->old_price;
    $product->stock           = $request->stock;
    $product->pro_unit        = $request->pro_unit;
    $product->pro_video       = $request->pro_video;
    $product->description     = $request->description;
    $product->short_des       = $request->short_des;
    $product->product_weight  = $request->product_weight;
    $product->prebooking      = $request->prebooking;
    $product->status          = $request->status;
    $product->topsale         = $request->topsale         ? 1 : 0;
    $product->feature_product = $request->feature_product ? 1 : 0;
    $product->deal_of_theday  = $request->deal_of_theday  ? 1 : 0;
    $product->type            = $request->type;
    $product->updated_at      = Carbon::now();

    if ($request->subcategory_id !== 'null') {
        $product->subcategory_id = $request->subcategory_id;
    }
    if ($request->childcategory_id !== 'null') {
        $product->childcategory_id = $request->childcategory_id;
    }


    // =============================================
    // Gallery / slider images
    // =============================================
    if ($request->hasFile('PostImage')) {
        $imageData = [];
        foreach ($request->file('PostImage') as $imgFile) {
            $name = time() . '_' . strtolower(preg_replace('/\s+/', '-', $imgFile->getClientOriginalName()));
            $imgFile->move(public_path('images/product/slider/'), $name);
            $imageData[] = $name;
        }
        $product->PostImage = json_encode($imageData);
    }

    $product->update();


    // =============================================
    // Main product thumbnail
    // =============================================
    if ($request->hasFile('image')) {
        $image    = $request->file('image');
        $name     = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image->getClientOriginalName()));
        $path     = 'public/uploads/product/';
        $image->move($path, $name);

        Productimage::where('product_id', $product->id)->delete();

        $pimage             = new Productimage();
        $pimage->product_id = $product->id;
        $pimage->image      = $path . $name;
        $pimage->save();
    }


    // =============================================
    // Variant product — colors & color-wise sizes
    // =============================================
    if ($request->type != 0) {

        $variants = $request->input('variant', []); // variant[colorID][colorID|color|colorDbID]
        $sizes    = $request->input('size', []);    // size[colorID][sizeID][field|sizeDbID]
        $time     = microtime('.') * 10000;

        foreach ($variants as $colorID => $colorData) {

            $colorDbID = $colorData['colorDbID'] ?? '';

            // ---- Update or create color ----
            if ($colorDbID) {
                $productColor = Productcolor::find($colorDbID);
            } else {
                $productColor = new Productcolor();
                $productColor->product_id = $product->id;
            }

            $productColor->color_id = $colorData['colorID'];
            $productColor->color    = $colorData['color'];
            $productColor->specification    = $colorData['specification'];

            // Color image
            if ($request->hasFile('variant.' . $colorID . '.image')) {
                $variantImg = $request->file('variant.' . $colorID . '.image');
                $imgName    = $time . strtolower(preg_replace('/\s+/', '-', $variantImg->getClientOriginalName()));
                $imgPath    = 'public/images/variant/';
                $variantImg->move($imgPath, $imgName);
                $productColor->Image = $imgPath . $imgName;
            }

            $colorDbID ? $productColor->update() : $productColor->save();


            // ---- Update or create sizes for this color ----
            if (!empty($sizes[$colorID])) {
                foreach ($sizes[$colorID] as $sizeID => $sizeData) {

                   $sizeDbID = $sizeData['sizeDbID'] ?? '';

                    if ($sizeDbID) {
                        $productSize = Productsize::find($sizeDbID);
                        // পুরনো stock বাদ দিয়ে নতুন stock যোগ
                        $total_stock = ($productSize->total_stock - $productSize->stock) + $sizeData['stock'];
                    } else {
                        $productSize = new Productsize();
                        $productSize->product_id = $product->id;
                        $productSize->color_id   = $colorID;
                        // নতুন entry তে শুধু নতুন stock
                        $total_stock = $sizeData['stock'];
                        
                        $last_size_id = Productsize::orderBy('id', 'desc')->value('id');
                        $last_size_id = $last_size_id ? $last_size_id + 1 : Uniqueid();
                        
                        $productSize->sku           = 'SKU-00' . $last_size_id;
                    }
                    $productSize->size_id       = $sizeData['sizeID'];
                    $productSize->size          = $sizeData['size'];
                    $productSize->stock         = $sizeData['stock'];
                    
                    $productSize->total_stock   = $total_stock;
                    $productSize->SalePrice     = $sizeData['sale_price'];
                    $productSize->RegularPrice  = $sizeData['RegularPrice'];

                    $sizeDbID ? $productSize->update() : $productSize->save();
                }
            }
        }
    }


    return response()->json([
        'status'  => 'success',
        'message' => 'Product Updated Successfully',
    ]);
}

    public function inactive(Request $request)
    {
        $inactive = Product::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Product::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Product::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    {
        $delete_data = Productimage::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function pricedestroy(Request $request)
    {
        $delete_data = Productprice::find($request->id);
        $delete_data->delete();
        Toastr::success('Success', 'Product price delete successfully');
        return redirect()->back();
    }
    public function update_deals(Request $request)
    {
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['topsale' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Hot deals product status change']);
    }
    // public function update_feature(Request $request)
    // {
    //     $products = Product::whereIn('id', $request->input('product_ids'))->update(['feature_product' => $request->status]);
    //     return response()->json(['status' => 'success', 'message' => 'Feature product status change']);
    // }
    public function update_status(Request $request)
    {
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Product status change successfully']);
    }
}
