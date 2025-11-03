<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Special;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $categories = Category::all();
        $featuredProducts = Product::where('is_featured', true)->take(8)->get();
        $specials = Special::take(6)->get();

        return view('web.index', compact('banners', 'categories', 'featuredProducts', 'specials'));
    }

    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('web.products', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('web.product-detail', compact('product', 'relatedProducts'));
    }

    public function about()
    {
        return view('web.about');
    }

    public function contact()
    {
        return view('web.contact');
    }
}
