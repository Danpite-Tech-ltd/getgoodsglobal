<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomeDistrict;
use App\Models\CustomeThana;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;
use Toastr;

class ShippingChargeController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:shipping-list|shipping-create|shipping-edit|shipping-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:shipping-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:shipping-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:shipping-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $show_data = ShippingCharge::orderBy('id', 'ASC')->get();
        return view('backEnd.shippingcharge.index', compact('show_data'));
    }
    public function create()
    {
        return view('backEnd.shippingcharge.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['status'] = $request->status ? 1 : 0;
        // dd($input);
        ShippingCharge::create($input);
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('shippingcharges.index');
    }

    public function edit($id)
    {
        $edit_data = ShippingCharge::find($id);
        return view('backEnd.shippingcharge.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        $update_data = ShippingCharge::find($request->id);

        $input = $request->all();
        $input['status'] = $request->status ? 1 : 0;
        $update_data->update($input);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('shippingcharges.index');
    }

    public function inactive(Request $request)
    {
        $inactive = ShippingCharge::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }

    public function active(Request $request)
    {
        $active = ShippingCharge::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $delete_data = ShippingCharge::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }

    public function district()
    {
        $districts = CustomeDistrict::get();
        return view('backEnd.district-thana.district', compact('districts'));
    }
    public function district_create()
    {
        return view('backEnd.district-thana.district_create');
    }

    public function district_store(Request $request)
    {
        $request->validate([
            'districtName' => 'required'
        ]);

        $district = new CustomeDistrict();
        $district->districtName = $request->districtName;
        $district->save();
        Toastr::success('Success', 'Data create successfully');
        return redirect()->route('district.index');
    }
    public function district_edit($id)
    {
        $district = CustomeDistrict::find($id);
        return view('backEnd.district-thana.district_edit', compact('district'));
    }

    public function district_update(Request $request, $id)
    {
        $request->validate([
            'districtName' => 'required'
        ]);

        $district = CustomeDistrict::find($id);
        $district->districtName = $request->districtName;
        $district->save();
        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('district.index');
    }

    public function district_destroy(Request $request, $id)
    {
        $district = CustomeDistrict::find($id);
        $district->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->route('district.index');
    }
    public function thana()
    {
        $thanas = CustomeThana::get();
        return view('backEnd.district-thana.thana', compact('thanas'));
    }
    public function thana_create()
    {
        $districts = CustomeDistrict::get();
        return view('backEnd.district-thana.thana_create',compact('districts'));
    }

    public function thana_store(Request $request)
    {
        $request->validate([
            'thanaName' => 'required'
        ]);

        $thana = new CustomeThana();
        $thana->district_id = $request->district_id;
        $thana->thanaName = $request->thanaName;
        $thana->save();
        Toastr::success('Success', 'Data create successfully');
        return redirect()->route('thana.index');
    }
    public function thana_edit($id)
    {
        $thana = CustomeThana::find($id);
        $districts = CustomeDistrict::get();
        return view('backEnd.district-thana.thana_edit', compact('thana','districts'));
    }

    public function thana_update(Request $request, $id)
    {
        $request->validate([
            'thanaName' => 'required'
        ]);

        $thana = CustomeThana::find($id);
        $thana->district_id = $request->district_id;
        $thana->thanaName = $request->thanaName;
        $thana->save();
        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('thana.index');
    }

    public function thana_destroy(Request $request, $id)
    {
        $thana = CustomeThana::find($id);
        $thana->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->route('thana.index');
    }
}
