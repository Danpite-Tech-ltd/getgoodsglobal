<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Expense::latest()->get();
        return view('backEnd.expense.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.expense.create');
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

        $expense = new Expense();
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->expense_type = $request->expense_type;
        $expense->payment_type = $request->payment_type;
        $expense->date = Carbon::now();
        $expense->status = $request->status ? 1 : 0;

        if ($request->file('file')) {
            $image = $request->file('file');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/images/expense/';
            $image->move($imagePath, $imageName);

            $expense->file   = $imagePath . $imageName;
        }
        $expense->save();
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('expense.index');
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
        $expense = Expense::find($id);
        return view('backEnd.expense.edit',compact('expense'));
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
        $expense = Expense::find($id);
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->expense_type = $request->expense_type;
        $expense->payment_type = $request->payment_type;
        $expense->date = Carbon::now();
        $expense->status = $request->status ? 1 : 0;

        if ($request->file('file')) {
            $image = $request->file('file');

            if (!is_null($expense->file) && file_exists($expense->file)) {
                unlink($expense->file);
            }

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/images/expense/';
            $image->move($imagePath, $imageName);

            $expense->file   = $imagePath . $imageName;
        }
        $expense->save();
        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('expense.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!is_null($expense->file) && file_exists($expense->file)) {
            unlink($expense->file);
        }

        $expense->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->route('expense.index');
    }
}
