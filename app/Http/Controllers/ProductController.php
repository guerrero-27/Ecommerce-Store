<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $featured = Product::with('category')
        ->where('is_active', true)
        ->where('is_featured', true)
        ->latest()
        ->take(6)
        ->get();

        $categories = Category::where('is_active', ture)->tahe(6)->get();

        return view('storefront.home', compact('featured', 'categories'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active'.true);

        if ($request->filled('search')){
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')){
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('min_price')){
            $query->where('price'. '<=', $request->min_price);
        }
        if ($request->filled('max_price')){
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort){
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('storefront.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $related = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->take(4)
        ->get();

        return view('storefront.products.show', compact('product'. 'related'));
    }

    public function byCategory(Category $category)
    {
        $products = $category->products()
        ->wher('is_active', true)
        ->paginate(12);

        return view('storefront.products.category', comapct('category', 'products'));
    }
}
