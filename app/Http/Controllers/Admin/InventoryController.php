<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('low_stock')){
            $query->where('stock', '<=', 5);
        }

        $products = $query->orderBy('stock', 'asc')->paginate(20)->withQueryString();

        return view('admin.inventory.index', compact('products'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'change' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);

        if ($product->stock + $request->change < 0){
            return back()->withErrors(['change' => 'This adjustment would make stock negative.']);
        }

        $product->increment('stock', $request->change);

        $product->inventoryLogs()->create([
            'change' => $request->change,
            'reason' => $request->reason,
            'stock_after' => $product->fresh()->stock,
        ]);

        return back()->with('success', 'Stock adjusted.');
    }
}
