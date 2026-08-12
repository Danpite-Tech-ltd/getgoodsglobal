<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\District;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\ShippingCharge;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Color;
use App\Models\Review;
use App\Models\Blog;
use App\Exports\Dataexport;
use Session;
use Cart;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{




    public function datafeed()
    {
        $colours = Productcolor::all();

        $xml = new \SimpleXMLElement('<rss/>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'LOREELBD.COM');
        $channel->addChild('link', url('https://loreenbd.com'));
        $channel->addChild('description', 'Loreen BD is an online luxury store offering a wide range of premium bags and accessories, including leather handbags, backpacks, and clutches. The site features elegant designs for both men and women and regularly provides discounts on select items.');

        foreach ($colours as $index => $colour) {
            $product = Product::where('id', $colour->product_id)->first();
            if (isset($product)) {
                $color = Color::where('id', $colour->color_id)->first();
                $sizes = Productsize::where('product_id', $colour->product_id)->get()->pluck('size');
                $item = $channel->addChild('item');
                $item->addChild('g:id', $index + 1);
                $item->addChild('g:item_group_id', $colour->product_id);
                $item->addChild('g:title', $product->name);
                $description = str_replace('&nbsp;', ' ', $product->description);
                $item->addChild('g:description', strip_tags($description));
                $item->addChild('g:link', 'https://loreenbd.com/product/' . $product->slug);
                $item->addChild('g:image_link', 'https://loreenbd.com/' . $product->image['image']);
                $item->addChild('g:brand', 'Loreenbd');
                if (isset($color)) {
                    $item->addChild('g:color', $color->colorName);
                } else {
                    $item->addChild('g:color', '');
                }
                $item->addChild('g:size', $sizes);
                $item->addChild('g:condition', 'new');
                $item->addChild('g:availability', 'available for order');
                $item->addChild('g:price', number_format($product->old_price) . ' BDT');
                $item->addChild('g:sale_price', number_format($product->new_price) . ' BDT');
            }
        }

        $exeid = Productcolor::get()->pluck('product_id');
        $products = Product::whereNotIn('id', $exeid)->get();

        foreach ($products as $ind => $product) {
            $id = $ind + 1 + count($colours);
            $product = Product::where('id', $product->id)->first();
            if (isset($product)) {
                $item = $channel->addChild('item');
                $item->addChild('g:id', $id);
                $item->addChild('g:item_group_id', $product->id);
                $item->addChild('g:title', $product->name);
                $description = str_replace('&nbsp;', ' ', $product->description);
                $item->addChild('g:description', strip_tags($description));
                $item->addChild('g:link', 'https://loreenbd.com/product/' . $product->slug);
                $item->addChild('g:image_link', 'https://loreenbd.com/' . $product->image['image']);
                $item->addChild('g:brand', 'Loreenbd');
                $item->addChild('g:color', '');
                $item->addChild('g:size', '');
                $item->addChild('g:condition', 'new');
                $item->addChild('g:availability', 'available for order');
                $item->addChild('g:price', number_format($product->old_price) . ' BDT');
                $item->addChild('g:sale_price', number_format($product->new_price) . ' BDT');
            }
        }

        return Response::make($xml->asXML(), 200, ['Content-Type' => 'application/xml']);
    }
    public function index()
    {
        return redirect()->to('/login');

        // $blog = Blog::all();
        $blog = Blog::orderBy('created_at', 'desc')->paginate(4);
        // return "Welcome to Kenakatar.com";
        $frontcategory = Category::where(['status' => 1, 'front_view' => 1])
            ->select('id', 'name', 'image', 'slug', 'status')->limit(6)
            ->get();

        $sliders = Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();

        $sliderbottomads = Banner::where(['status' => 1, 'category_id' => 5])
            ->select('id', 'image', 'link')
            ->limit(3)
            ->get();

        $footertopads = Banner::where(['status' => 1, 'category_id' => 6])
            ->select('id', 'image', 'link')
            ->limit(2)
            ->get();

        $hotdeal_top = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->with('prosizes', 'procolors')
            ->limit(12)
            ->get();
        // return $hotdeal_top;

        $feature_products = Product::where(['status' => 1, 'feature_product' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->with('prosizes', 'procolors')
            ->limit(12)
            ->get();

        $dealofthe_products = Product::where(['status' => 1, 'deal_of_theday' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->with('prosizes', 'procolors')
            ->limit(12)
            ->get();

        $new_products = Product::where(['status' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->with('prosizes', 'procolors')
            ->limit(12)
            ->latest()
            ->get();

        $hotdeal_bottom = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->skip(12)
            ->limit(12)
            ->get();

        $homeproducts = Category::where(['front_view' => 1, 'status' => 1])
            ->orderBy('id', 'ASC')
            ->with(['products', 'products.image', 'products.prosize', 'products.procolor'])
            ->get()
            ->map(function ($query) {
                $query->setRelation('products', $query->products->take(12));
                return $query;
            });
        //  return $frontcategory;
        return view('frontEnd.layouts.pages.index', compact('sliders', 'frontcategory', 'hotdeal_top', 'feature_products', 'dealofthe_products', 'new_products', 'hotdeal_bottom', 'homeproducts', 'sliderbottomads', 'footertopads', 'blog'));
    }

    public function shop()
    {

        $homeproducts = Category::where(['front_view' => 1, 'status' => 1])
            ->orderBy('id', 'ASC')
            ->with(['products', 'products.image', 'products.prosize', 'products.procolor'])
            ->get()
            ->map(function ($query) {
                $query->setRelation('products', $query->products->take(12));
                return $query;
            });
        return view('frontEnd.layouts.pages.shop', compact('homeproducts'));
    }

    public function hotdeals()
    {

        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->paginate(36);
        return view('frontEnd.layouts.pages.hotdeals', compact('products'));
    }

    public function category($slug, Request $request)
    {
        $category = Category::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'category_id' => $category->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id');
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedSubcategories = $request->input('subcategory', []);
        $products = $products->when($selectedSubcategories, function ($query) use ($selectedSubcategories) {
            return $query->whereHas('subcategory', function ($subQuery) use ($selectedSubcategories) {
                $subQuery->whereIn('id', $selectedSubcategories);
            });
        });

        $products = $products->paginate(100000000);
        return view('frontEnd.layouts.pages.category', compact('category', 'products', 'subcategories', 'min_price', 'max_price'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id');
        $childcategories = Childcategory::where('subcategory_id', $subcategory->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $selectedChildcategories = $request->input('childcategory', []);
        $products = $products->when($selectedChildcategories, function ($query) use ($selectedChildcategories) {
            return $query->whereHas('childcategory', function ($subQuery) use ($selectedChildcategories) {
                $subQuery->whereIn('id', $selectedChildcategories);
            });
        });

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image')
            ->limit(6)
            ->select('id', 'name', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products', 'impproducts', 'childcategories', 'max_price', 'min_price'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $childcategories = Childcategory::where('subcategory_id', $childcategory->subcategory_id)->get();
        $products = Product::where(['status' => 1, 'childcategory_id' => $childcategory->id])->with('category')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id', 'subcategory_id', 'childcategory_id');


        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);
        // return $products;
        $impproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->with('image')
            ->limit(6)
            ->select('id', 'name', 'slug')
            ->get();

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products', 'impproducts', 'min_price', 'max_price', 'childcategories'));
    }


    public function details($slug)
    {
        $details = Product::where(['slug' => $slug, 'status' => 1])
            ->with('image', 'images', 'category', 'subcategory', 'childcategory')
            ->firstOrFail();
        $products = Product::where(['category_id' => $details->category_id, 'status' => 1])
            ->with('image')
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->get();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $reviews = Review::where(['product_id' => $details->id, 'status' => 'active'])->get();
        $productcolors = Productcolor::where('product_id', $details->id)->get();
        // return $productcolors;
        $productsizes = Productsize::where('product_id', $details->id)
            ->get();

        return view('frontEnd.layouts.pages.details', compact('details', 'products', 'shippingcharge', 'productcolors', 'productsizes', 'reviews'));
    }

    public function quantity_cart(Request $request)
    {
        $product_id = $request->product_id;
        $size = $request->size;

        $totalQuantity = Productsize::where('product_id', $product_id)
            ->where('size', $size)
            ->sum('quantity');

        return response()->json($totalQuantity);
    }

    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images')->withCount('reviews')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price',)
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'product_code')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%")->orWhere('product_code', 'LIKE', '%' . $request->keyword . '%');
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->paginate(36);
        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.search', compact('products', 'keyword'));
    }

    public function shipping_charge(Request $request)
    {
        $shipping = ShippingCharge::where(['id' => $request->id])->first();
        Session::put('shipping', $shipping->amount);
        Session::put('discount', $request->discount);
        return view('frontEnd.layouts.ajax.cart');
    }


    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = District::where(['district' => $request->id])->pluck('area_name', 'id');
        return response()->json($areas);
    }
    public function campaign($slug)
    {
        $campaign_data = Campaign::where('slug', $slug)->with('images')->first();
        $product = Product::where('id', $campaign_data->product_id)
            ->where('status', 1)
            ->with('image')
            ->first();
        Cart::instance('shopping')->destroy();
        $cart_count = Cart::instance('shopping')->count();
        if ($cart_count == 0) {
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image,
                    'old_price' => $product->old_price,
                    'purchase_price' => $product->purchase_price,
                ],
            ]);
        }
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $select_charge = ShippingCharge::where('status', 1)->first();
        Session::put('shipping', $select_charge->amount);

         $productcolors = Productcolor::where('product_id', $campaign_data->product_id)
            ->with('color')
            ->get();
        $productsizes = Productsize::where('product_id', $campaign_data->product_id)
            ->with('size')
            ->get();
        return view('frontEnd.layouts.pages.campaign.campaign', compact('campaign_data', 'product', 'shippingcharge', 'productcolors', 'productsizes'));
    }

    public function campaign_order(Request $request)
    {
        //
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function offers()
    {
        return view('frontEnd.layouts.pages.offers');
    }
}
