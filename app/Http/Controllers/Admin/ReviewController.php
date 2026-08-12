<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Product;
use App\Models\Review;
use App\Models\Customer;
class ReviewController extends Controller
{
    // function __construct()
    // {
    //      $this->middleware('permission:review-list|review-create|review-edit|review-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:review-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:review-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:review-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $show_data = Review::orderBy('id','DESC')->get();
        return view('backEnd.review.index',compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status'=>1])->select('id','name')->get();
        $customers = Customer::where('status', 'active')->get();
        return view('backEnd.review.create',compact('products', 'customers'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'product_id' => 'required',
            'status' => 'required',
        ]);
        // $customer = Customer::where('id', $request->customer_id)->first();
        // $input = $request->all();
        // $input['name'] = $customer->name ? $customer->name : 'N / A';
        // $input['email'] = $customer->email ? $customer->email : 'N / A';

        // if ($request->file('image')) {
        //     $image = $request->file('image');

        //     $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
        //     $imagePath          = 'public/uploads/review/';
        //     $image->move($imagePath, $imageName);

        //     $input['image']   = $imagePath . $imageName;
        // }

        // $input['status'] = $request->status==1?'active':'pending';
        // Review::create($input);



        $customer = Customer::where('id', $request->customer_id)->first();
        $review = new Review();
        $review->name = $request->customer_name;
        $review->email = $request->customer_email;

        $review->ratting = $request->ratting;
        $review->review = $request->review;
        $review->product_id = $request->product_id;

        if ($request->file('image')) {
            $image = $request->file('image');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/review/';
            $image->move($imagePath, $imageName);

            $review->image   = $imagePath . $imageName;
        }

        $review->status = $request->status==1?'active':'pending';
        $review->save();
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('reviews.index');
    }

    public function edit($id)
    {
        $edit_data = Review::find($id);
        $products = Product::where(['status'=>1])->select('id','name')->get();
        return view('backEnd.review.edit',compact('edit_data','products'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'ratting' => 'required',
            'product_id' => 'required',
        ]);


        // $input = $request->except('hidden_id');
        // $input['status'] = $request->status==1?'active':'pending';
        // if ($request->file('image')) {
        //     $image = $request->file('image');

        //     if (!is_null($input['image']) && file_exists($input['image'])) {
        //         unlink($input['image']);
        //     }

        //     $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
        //     $imagePath          = 'public/uploads/review/';
        //     $image->move($imagePath, $imageName);

        //     $input['image']   = $imagePath . $imageName;
        // }
        // $update_data = Review::find($request->hidden_id);
        // $update_data->update($input);

        $review = Review::find($id);
        $review->name = $request->name;
        $review->email = $request->email;

        $review->ratting = $request->ratting;
        $review->review = $request->review;
        $review->feedback = $request->feedback;
        $review->product_id = $request->product_id;

        if ($request->file('image')) {
            $image = $request->file('image');

            if (!is_null($review->image) && file_exists($review->image)) {
                unlink($review->image);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/review/';
            $image->move($imagePath, $imageName);

            $review->image   = $imagePath . $imageName;
        }

        $review->status = $request->status == 1 ? 'active' : 'pending';
        $review->update();

        Toastr::success('Success','Data update successfully');
        return redirect()->route('reviews.index');
    }

    public function pending(){
        $data = Review::where('status','pending')->get();
        return view('backEnd.review.pending',compact('data'));
    }
    public function inactive(Request $request){
        $inactive = Review::find($request->hidden_id);
        $inactive->status = 'pending';
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request){
        $active = Review::find($request->hidden_id);
        $active->status = 'active';
        $active->save();

        // $product = Product::select('id','ratting')->find($active->product_id);
        // $product->ratting += 1;
        // $product->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Review::find($request->hidden_id);
        if (!is_null($delete_data->image) && file_exists($delete_data->image)) {
            unlink($delete_data->image);
        }
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
}
