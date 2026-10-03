<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use Toastr;

class MesurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MeasurementUnit::latest()->get();
        return view('backEnd.measurement.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'value'  => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'custom' => 'nullable|in:0,1',
            'is_ml'  => 'nullable|in:0,1',
        ]);

        $custom = $request->input('custom', 0);
        $is_ml = ($custom == 1) ? $request->input('is_ml', 0) : null;

        $unit = MeasurementUnit::create([
            'title'  => $request->title,
            'value'  => $request->value,
            'status' => $request->status,
            'custom' => $custom,
            'is_ml'  => $is_ml,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement Unit created successfully!',
                'data'    => $unit,
            ]);
        }

        Toastr::success('Success', 'Measurement Unit created successfully!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource (returns JSON for AJAX modal).
     */
    public function edit(Request $request, $id)
    {
        $unit = MeasurementUnit::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $unit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'value'  => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'custom' => 'nullable|in:0,1',
            'is_ml'  => 'nullable|in:0,1',
        ]);

        $custom = $request->input('custom', 0);
        $is_ml = ($custom == 1) ? $request->input('is_ml', 0) : null;

        $unit = MeasurementUnit::findOrFail($id);
        $unit->update([
            'title'  => $request->title,
            'value'  => $request->value,
            'status' => $request->status,
            'custom' => $custom,
            'is_ml'  => $is_ml,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement Unit updated successfully!',
                'data'    => $unit,
            ]);
        }

        Toastr::success('Success', 'Measurement Unit updated successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $unit = MeasurementUnit::findOrFail($id);
        $unit->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement Unit deleted successfully!',
            ]);
        }

        Toastr::success('Success', 'Measurement Unit deleted successfully!');
        return redirect()->back();
    }
}
