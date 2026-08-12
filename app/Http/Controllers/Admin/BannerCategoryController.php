<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerCategory;
use Toastr;
class BannerCategoryController extends Controller
{
    // function __construct()
    // {
    //      $this->middleware('permission:banner-category-list|banner-category-create|banner-category-edit|banner-category-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:banner-category-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:banner-category-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:banner-category-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $data = BannerCategory::orderBy('id','DESC')->get();
        return view('backEnd.banner.category.index',compact('data'));
    }
    public function create()
    {
        $categories = BannerCategory::orderBy('id','DESC')->select('id','name')->get();
        return view('backEnd.banner.category.create',compact('categories'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'icon' => 'nullable', 'image',
            'status' => 'required',
        ]);
        
        if($request->file('icon')){
            $file = $request->file('icon');
            $name = time().$file->getClientOriginalName();
            $uploadPath = 'public/uploads/banner/';
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
        }
        
        $input = $request->all();
        $input['icon'] = $fileUrl;
        BannerCategory::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('banner_category.index');
    }
    
    public function edit($id)
    {
        $edit_data = BannerCategory::find($id);
        return view('backEnd.banner.category.edit',compact('edit_data'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $update_data = BannerCategory::find($request->id);
        $input = $request->all();
        
        $image = $request->file('icon');
        if($image){
           // image with intervention 
            $file = $request->file('icon');
            $name = time().$file->getClientOriginalName();
            $uploadPath = 'public/uploads/banner/';
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
            $input['icon'] = $fileUrl;
            if($update_data->icon){
                File::delete($update_data->icon);
            }
        }else{
            $input['icon'] = $update_data->icon;
        }
        
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('banner_category.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = BannerCategory::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = BannerCategory::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = BannerCategory::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
}
