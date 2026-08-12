<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EcomPixel;
use App\Models\GoogleTagManager;
use Illuminate\Http\Request;

class PixelGtmController extends Controller
{
    public function pixel()
    {
        $pixel = EcomPixel::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Pixel Success',
            'data' => $pixel,
        ], 200);
    }
    public function gtm()
    {
        $gtm = GoogleTagManager::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Google Tag Manager Success',
            'data' => $gtm,
        ], 200);
    }
}
