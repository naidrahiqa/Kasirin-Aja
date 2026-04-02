<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class POSController extends Controller
{
    /**
     * Display the cashier POS interface.
     */
    public function index(): View
    {
        $categories = \App\Models\Category::all();

        return view('pos.index', compact('categories'));
    }

    /**
     * API to get products for the POS grid incrementally.
     */
    public function getProducts(\Illuminate\Http\Request $request)
    {
        $query = Product::where('stock', '>', 0);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', $request->search);
            });
        }

        if ($request->filled('category_id') && $request->category_id > 0) {
            $query->where('category_id', $request->category_id);
        }

        // Return up to 30 products per page
        $products = $query->orderBy('name')->paginate(30);

        return response()->json($products);
    }
}
