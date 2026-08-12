<?php

namespace App\Http\Controllers;

use App\Models\BulkQuantity;
use App\Models\Product;
use Illuminate\Http\Request;

class BulkqtyController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        $bulk_qty = BulkQuantity::where('product_id', $product->id)->where('status', 1)->get();
        return view('backEnd.bulkqty.index', compact('bulk_qty', 'product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'min_qty' => 'required',
            'max_qty' => 'required',
            'price' => 'required',
        ]);

        $bulkquantity_count = BulkQuantity::where('product_id', $request->product_id)->count();

        if ($bulkquantity_count >= 3) {
            return redirect()->back()->with('error', 'Bulk Quantity Maximum 3 Times!');
        }

        $bulkquantity = new BulkQuantity();
        $bulkquantity->product_id = $request->product_id;
        $bulkquantity->title = $request->title;
        $bulkquantity->min_qty = $request->min_qty;
        $bulkquantity->max_qty = $request->max_qty;
        $bulkquantity->price = $request->price;
        $bulkquantity->save();

        return redirect()->back()->with('success', 'Bulk Quantity Created!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'min_qty' => 'required',
            'max_qty' => 'required',
            'price' => 'required',
        ]);
    
        $bulkquantity = BulkQuantity::findOrFail($id);
    
        $bulkquantity->title = $request->title;
        $bulkquantity->min_qty = $request->min_qty;
        $bulkquantity->max_qty = $request->max_qty;
        $bulkquantity->price = $request->price;
    
        $bulkquantity->save();
    
        return redirect()->back()->with('success', 'Bulk Quantity Updated!');
    }
    
    


    public function destroy($id)
    {
        $bulkquantity = BulkQuantity::find($id);
        $bulkquantity->delete();
        return redirect()->back()->with('delete', 'Deleted!');
    }
}
