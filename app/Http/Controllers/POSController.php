<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class POSController extends Controller
{
    /**
     * Display the cashier POS interface.
     * Passes all available (non-deleted, in-stock) products to the view.
     */
    public function index(): View
    {
        $categories = \App\Models\Category::all();
        $products = Product::where('stock', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products', 'categories'));
    }
}
