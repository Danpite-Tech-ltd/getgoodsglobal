<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::where('status', 1)
            ->select('id', 'name', 'slug', 'image', 'status')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories',
            'data' => $categories,
        ], 200);
    }
    
    // public function categories()
    // {
    //     $categories = Cache::remember('categories', 3600, function () {
    //         return Category::where('status', 1)
    //             ->select('id','name','slug','image')
    //             ->get();
    //     });
    
    //     return response()->json([
    //         'data' => $categories
    //     ], 200);
    // }
    public function menuCategories()
    {
        $categories = Category::where('status', 1)
            ->select('id', 'name', 'slug', 'image', 'status')
            ->with(['subcategories' => function($q){
                $q->where('status', 1)->select('id', 'subcategoryName', 'slug', 'image', 'category_id', 'status');
            }])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories',
            'data' => $categories,
        ], 200);
    }

    public function subcategoriesByCategory($slug)
    {
        $category = Category::where('slug',$slug)->first();

        $subcategories = Subcategory::where('status',1)
                ->where('category_id',$category->id)
                ->get();

        return response()->json([
            'status' => true,
            'message' => 'Subcategories for '.$category->name .' ',
            'data' => $subcategories,
        ], 200);
    }

    public function childcategoriesBySubcategory(string $slug)
    {
        $subcategory = Subcategory::where('slug',$slug)->first();

        $childcategories = Childcategory::where('status',1)
            ->where('subcategory_id', $subcategory->id)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Childcategories for '.$subcategory->subcategoryName .' ',
            'data' => $childcategories,
        ], 200);
    }
}
