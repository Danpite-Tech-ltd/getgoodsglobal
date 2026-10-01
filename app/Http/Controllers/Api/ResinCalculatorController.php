<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use App\Models\ResinRatio;
use App\Models\Shape;
use Illuminate\Http\Request;

class ResinCalculatorController extends Controller
{
    public function shape()
    {
        $data = Shape::where('status', 1)->get();
        return response()->json([
            'status' => true, 
            'message' => 'Shape List',
            'data' => $data
        ]);
    }
    public function resin()
    {
        $data = ResinRatio::where('status', 1)->get();
        return response()->json([
            'status' => true, 
            'message' => 'Resin Ratio List',
            'data' => $data
        ]);
    }
    public function mesurement()
    {
        $data = MeasurementUnit::where('status', 1)->get();
        return response()->json([
            'status' => true, 
            'message' => 'Measurement Unit List',
            'data' => $data
        ]);
    }
}
