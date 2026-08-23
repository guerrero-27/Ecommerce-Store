<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', ['categories' => $categories, 'product' => new Product()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('images')) {
            $validated['images'] = $this->storeImages($request->file('images'));
        }

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', "\"{$product->name}\" created.");
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        if ($request->name !== $product->name) {
            $validated['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('images')) {
            foreach ($product->images ?? [] as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $validated['images'] = $this->storeImages($request->file('images'));
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "\"{$product->name}\" updated.");
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "\"{$product->name}\" deleted.");
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gt:price',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
            'images.*' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);
    }

    private function storeImages(array $files): array
    {
        return collect($files)->map(fn ($file) => $file->store('products', 'public'))->toArray();
    }
}
