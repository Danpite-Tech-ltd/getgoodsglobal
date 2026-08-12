<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMedaiaController extends Controller
{
    public function socialMedia()
    {
        $social = SocialMedia::where('status',1)->select('title','icon','link','color','status')->get();

          return response()->json([
            'status' => 'success',
            'message' => 'Social',
            'data' => $social,
        ], 200);
    }
}
