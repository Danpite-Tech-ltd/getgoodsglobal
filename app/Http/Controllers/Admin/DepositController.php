<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Deposit::latest()->get();
        return view('backEnd.deposit.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.deposit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            '*' => 'required',
        ]);

        $deposit = new Deposit();
        $deposit->title = $request->title;
        $deposit->amount = $request->amount;
        $deposit->deposit_type = $request->deposit_type;
        $deposit->payment_type = $request->payment_type;
        $deposit->date = Carbon::now();
        $deposit->status = $request->status ? 1 : 0;

        if ($request->file('file')) {
            $image = $request->file('file');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/images/deposit/';
            $image->move($imagePath, $imageName);

            $deposit->file   = $imagePath . $imageName;
        }
        $deposit->save();
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('deposit.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deposit = Deposit::find($id);
        return view('backEnd.deposit.edit', compact('deposit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deposit = Deposit::find($id);
        $deposit->title = $request->title;
        $deposit->amount = $request->amount;
        $deposit->deposit_type = $request->deposit_type;
        $deposit->payment_type = $request->payment_type;
        $deposit->date = Carbon::now();
        $deposit->status = $request->status ? 1 : 0;

        if ($request->file('file')) {
            $image = $request->file('file');

            if (!is_null($deposit->file) && file_exists($deposit->file)) {
                unlink($deposit->file);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/images/deposit/';
            $image->move($imagePath, $imageName);

            $deposit->file   = $imagePath . $imageName;
        }
        $deposit->save();
        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('deposit.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deposit = Deposit::find($id);

        if (!is_null($deposit->file) && file_exists($deposit->file)) {
            unlink($deposit->file);
        }

        $deposit->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->route('depo$deposit.index');
    }
}
