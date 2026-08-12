<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreatePage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function pages()
    {
        $pages = CreatePage::where('status', 1)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Pages',
            'data' => $pages,
        ], 200);
    }
}
