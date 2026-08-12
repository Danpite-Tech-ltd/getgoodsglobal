<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerCategory;
use App\Models\Banner;
use App\Models\FeaturedBanner;
use Toastr;
use Image;
use File;
class BannerController extends Controller
{
    // function __construct()
    // {
    //      $this->middleware('permission:banner-list|banner-create|banner-edit|banner-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:banner-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:banner-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:banner-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $data = Banner::orderBy('id','DESC')->with('category')->get();
        return view('backEnd.banner.index',compact('data'));
    }
    public function create()
    {
        $categories = BannerCategory::orderBy('id','DESC')->select('id','name')->get();
        return view('backEnd.banner.create',compact('categories'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        // image with intervention 
        $file = $request->file('image');
        $name = time().$file->getClientOriginalName();
        $uploadPath = 'public/uploads/banner/';
        $file->move($uploadPath,$name);
        $fileUrl =$uploadPath.$name;

        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $input['image'] = $fileUrl;
        Banner::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('banners.index');
    }
    
    public function edit($id)
    {
        $edit_data = Banner::find($id);
        $categories = BannerCategory::select('id','name')->get();
        return view('backEnd.banner.edit',compact('edit_data','categories'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $update_data = Banner::find($request->id);
        $input = $request->all();
        $image = $request->file('image');
        if($image){
           // image with intervention 
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $uploadPath = 'public/uploads/banner/';
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
            $input['image'] = $fileUrl;
            File::delete($update_data->image);
        }else{
            $input['image'] = $update_data->image;
        }

        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('banners.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = Banner::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Banner::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Banner::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function edit_feature($id)
    {
        $edit_data = FeaturedBanner::find($id);
        return view('backEnd.banner.featured',compact('edit_data'));
    }
    public function update_feature(Request $request ,$id)
    {
        $featuredBanner = FeaturedBanner::find($id);

        $featuredBanner->title_one = $request->title_one;
        $featuredBanner->title_two = $request->title_two;
        $featuredBanner->title_three = $request->title_three;
        $featuredBanner->title_four = $request->title_four;

        if ($request->file('image_one')) {
            $image = $request->file('image_one');

            if (!is_null($featuredBanner->image_one) && file_exists($featuredBanner->image_one)) {
                unlink($featuredBanner->image_one);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/featured_banner/';
            $image->move($imagePath, $imageName);

            $featuredBanner->image_one   = $imagePath . $imageName;
        }

        if ($request->file('image_two')) {
            $image = $request->file('image_two');

            if (!is_null($featuredBanner->image_two) && file_exists($featuredBanner->image_two)) {
                unlink($featuredBanner->image_two);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/featured_banner/';
            $image->move($imagePath, $imageName);

            $featuredBanner->image_two   = $imagePath . $imageName;
        }

        if ($request->file('image_three')) {
            $image = $request->file('image_three');

            if (!is_null($featuredBanner->image_three) && file_exists($featuredBanner->image_three)) {
                unlink($featuredBanner->image_three);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/featured_banner/';
            $image->move($imagePath, $imageName);

            $featuredBanner->image_three   = $imagePath . $imageName;
        }

        if ($request->file('image_four')) {
            $image = $request->file('image_four');

            if (!is_null($featuredBanner->image_four) && file_exists($featuredBanner->image_four)) {
                unlink($featuredBanner->image_four);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/uploads/featured_banner/';
            $image->move($imagePath, $imageName);

            $featuredBanner->image_four   = $imagePath . $imageName;
        }

        $featuredBanner->update();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();

    }
}
