<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\FeaturedBanner;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function main_sliders()
    {
        $mainslider = Banner::where('category_id', 1)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Sliders',
            'data' => $mainslider,
        ], 200);
    }

    public function gallery_slider()
    {
        $galleryslider = BannerCategory::where('id', '!=', 1)->with('banners')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery Sliders',
            'data' => $galleryslider,
        ], 200);
    }
    
    public function featured_banner()
    {
        $featured_banner = FeaturedBanner::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Featured',
            'data' => $featured_banner,
        ], 200);
    }
}
