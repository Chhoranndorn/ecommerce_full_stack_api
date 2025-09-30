<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Special;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        $specials = Special::all()->map(function ($item){
            $item->image = asset($item->image);
            return $item;
        });
        $categories = Category::all()->map(function ($item) {
            // $item->icon = URL::to($item->icon);
            $item->icon = asset($item->icon);
            return $item;
        });

        $products = Product::all()->map(function ($item) {
            // $item->image = URL::to($item->image);
            $item->image = asset($item->image);
            return $item;
        });
        return response()->json([
            'specials' => $specials,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
