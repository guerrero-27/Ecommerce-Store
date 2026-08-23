<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['slug'] = Str::slug($request->name);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');

    }

    public function destroy(Category $category)
    {
        if($category->products()->exists()){
            return back()->withErrors(['category' => 'Cannot delete a category that still has products. Move or delete those products first.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted');
    }
}
