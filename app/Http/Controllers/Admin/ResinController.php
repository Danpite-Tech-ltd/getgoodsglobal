<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResinRatio;
use Illuminate\Http\Request;
use Toastr;

class ResinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ResinRatio::latest()->get();
        return view('backEnd.resin.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'resin'            => 'required|string|max:255',
            'hardener'         => 'required|string|max:255',
            'resin_density'    => 'required|string|max:255',
            'hardener_density' => 'required|string|max:255',
            'wastage'          => 'required|string|max:255',
            'status'           => 'required|in:0,1',
        ]);

        $resin = ResinRatio::create($request->only([
            'title', 'resin', 'hardener', 'resin_density', 'hardener_density', 'wastage', 'status'
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Resin Ratio created successfully!',
                'data'    => $resin,
            ]);
        }

        Toastr::success('Success', 'Resin Ratio created successfully!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource (returns JSON for AJAX modal).
     */
    public function edit(Request $request, $id)
    {
        $resin = ResinRatio::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $resin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'nullable|string|max:255',
            'resin'            => 'required|string|max:255',
            'hardener'         => 'required|string|max:255',
            'resin_density'    => 'required|string|max:255',
            'hardener_density' => 'required|string|max:255',
            'wastage'          => 'required|string|max:255',
            'status'           => 'required|in:0,1',
        ]);

        $resin = ResinRatio::findOrFail($id);
        $resin->update($request->only([
            'title', 'resin', 'hardener', 'resin_density', 'hardener_density', 'wastage', 'status'
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Resin Ratio updated successfully!',
                'data'    => $resin,
            ]);
        }

        Toastr::success('Success', 'Resin Ratio updated successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $resin = ResinRatio::findOrFail($id);
        $resin->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Resin Ratio deleted successfully!',
            ]);
        }

        Toastr::success('Success', 'Resin Ratio deleted successfully!');
        return redirect()->back();
    }
}
