<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Bank;
use Toastr;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $data = Bank::orderBy('id','DESC')->get();
        return view('backEnd.bank.index',compact('data'));
    }
    public function create()
    {
        return view('backEnd.bank.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required',
            'account_number' => 'required',
            'image' => 'required', 'image',
            'status' => 'required',
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $uploadPath = 'public/uploads/bank/';
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
        }

        $input = $request->all();
        $input['image'] = $fileUrl;
        Bank::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('bank.index');
    }

    public function edit($id)
    {
        $edit_data = Bank::find($id);
        return view('backEnd.bank.edit',compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required',
            'account_number' => 'required',
            'status' => 'required',
        ]);
        $update_data = Bank::find($request->id);
        $input = $request->all();

        $image = $request->file('image');
        if($image){
           // image with intervention
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $uploadPath = 'public/uploads/bank/';
            $file->move($uploadPath,$name);
            $fileUrl =$uploadPath.$name;
            $input['image'] = $fileUrl;
            if($update_data->image){
                File::delete($update_data->image);
            }
        }else{
            $input['image'] = $update_data->image;
        }

        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('bank.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Bank::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Bank::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Bank::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
}
