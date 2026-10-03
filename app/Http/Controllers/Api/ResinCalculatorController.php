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

    public function calculation(Request $request)
    {
        // validation

        $data = $request->validate([
            'shape_id'            => 'required|exists:shapes,id',
            'resin_ratio_id'      => 'required|exists:resin_ratios,id',
            'measurement_unit_id' => 'required|exists:measurement_units,id',
            'price'               => 'required|numeric|min:0',
            'inputs'              => 'required|array',
        ]);

        $shape = Shape::findOrFail($data['shape_id']);
        $ratio = ResinRatio::findOrFail($data['resin_ratio_id']);
        $unit  = MeasurementUnit::findOrFail($data['measurement_unit_id']);

        $rules = [];
        foreach ($shape->input_list as $field) {
            $rules['inputs.' . $field['parameter']] = 'required|numeric|gt:0';
        }
        $request->validate($rules);

        $total_volume = 0;
        if ($shape->type == 'rectangle') {

            $total_volume = 1;

            foreach ($shape->input_list as $field) {
                $value = $request->input('inputs.' . $field['parameter']);

                if (empty($field['mm'])) {
                    $value *= $unit->value;
                } else {
                    $value *= 0.1;
                }

                $total_volume *= $value;
            }
        } elseif ($shape->type == 'circle') {
            $radius = 0;
            $other_multiplier = 1;

            foreach ($shape->input_list as $field) {
                $value = $request->input('inputs.' . $field['parameter']);

                if (empty($field['mm'])) {
                    $value *= $unit->value;
                } else {
                    $value *= 0.1;
                }

                if (!empty($field['diameter'])) {
                    $radius = $value / 2;
                } else {
                    $other_multiplier *= $value;
                }
            }

            $total_volume = pi() * $radius * $radius * $other_multiplier;
        } elseif($shape->type == 'custom'){
            $total_volume = 1;

            foreach ($shape->input_list as $field) {
                $value = $request->input('inputs.' . $field['parameter']);

                $total_volume *= $value;
            }
        }

        $resin    = $total_volume * ($ratio->resin    / ($ratio->resin + $ratio->hardener)) * (1 + $ratio->wastage / 100);
        $hardener = $total_volume * ($ratio->hardener / ($ratio->resin + $ratio->hardener)) * (1 + $ratio->wastage / 100);

        
        $resin = round($resin, 3);
        $hardener = round($hardener, 3);

        $cost = $data['price'] / 1000 * ($resin + $hardener);
        $cost = round($cost, 2);

        return response()->json([
            'status' => true,
            'message' => 'Calculation Result',
            'data' => [
                'resin' => $resin,
                'hardener' => $hardener,
                'cost' => $cost,
                'total_volume' => $total_volume,
                'shape' => $shape,
                'resin_ratio' => $ratio,
                'measurement_unit' => $unit,
                'inputs' => $data['inputs'],
                'price' => $data['price'],
            ]
        ]);
    }
}
