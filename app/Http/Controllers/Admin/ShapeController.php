<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shape;
use Illuminate\Http\Request;
use Toastr;

class ShapeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Shape::latest()->get();
        return view('backEnd.shape.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:rectangle,circle,custom',
            'status' => 'required|in:0,1',
            'input_list' => 'required|array|min:1',
            'input_list.*.title' => 'required|string|max:255',
            'input_list.*.parameter' => 'required|string|max:255',
            'input_list.*.mm' => 'nullable|in:0,1',
        ], [
            'input_list.required' => 'At least one input item is required.',
            'input_list.min' => 'At least one input item is required.',
            'input_list.*.title.required' => 'Each input item must have a title.',
            'input_list.*.parameter.required' => 'Each input item must have a parameter.',
        ]);

        $inputList = [];
        if (is_array($request->input_list)) {
            foreach ($request->input_list as $item) {
                if (isset($item['title']) && isset($item['parameter'])) {
                    $inputList[] = [
                        'title' => trim($item['title']),
                        'parameter' => trim($item['parameter']),
                        'mm' => isset($item['mm']) && (int)$item['mm'] === 1 ? 1 : 0,
                    ];
                }
            }
        }

        $shape = Shape::create([
            'title' => $request->title,
            'type' => $request->type,
            'input_list' => $inputList,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Shape created successfully!',
                'data' => $shape,
            ]);
        }

        Toastr::success('Success', 'Shape created successfully!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource (returns JSON for AJAX modal).
     */
    public function edit(Request $request, $id)
    {
        $shape = Shape::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $shape,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:rectangle,circle,custom',
            'status' => 'required|in:0,1',
            'input_list' => 'required|array|min:1',
            'input_list.*.title' => 'required|string|max:255',
            'input_list.*.parameter' => 'required|string|max:255',
            'input_list.*.mm' => 'nullable|in:0,1',
        ], [
            'input_list.required' => 'At least one input item is required.',
            'input_list.min' => 'At least one input item is required.',
            'input_list.*.title.required' => 'Each input item must have a title.',
            'input_list.*.parameter.required' => 'Each input item must have a parameter.',
        ]);

        $inputList = [];
        if (is_array($request->input_list)) {
            foreach ($request->input_list as $item) {
                if (isset($item['title']) && isset($item['parameter'])) {
                    $inputList[] = [
                        'title' => trim($item['title']),
                        'parameter' => trim($item['parameter']),
                        'mm' => isset($item['mm']) && (int)$item['mm'] === 1 ? 1 : 0,
                    ];
                }
            }
        }

        $shape = Shape::findOrFail($id);
        $shape->update([
            'title' => $request->title,
            'type' => $request->type,
            'input_list' => $inputList,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Shape updated successfully!',
                'data' => $shape,
            ]);
        }

        Toastr::success('Success', 'Shape updated successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $shape = Shape::findOrFail($id);
        $shape->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Shape deleted successfully!',
            ]);
        }

        Toastr::success('Success', 'Shape deleted successfully!');
        return redirect()->back();
    }
}
