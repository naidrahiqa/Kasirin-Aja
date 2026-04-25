<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of all non-deleted products with search & filter.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category')->withSum('transactionDetails as total_sold', 'quantity');

        // Search by product name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'available':
                    $query->where('stock', '>', 10);
                    break;
                case 'low':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'empty':
                    $query->where('stock', '<=', 0);
                    break;
            }
        }

        $products = $query->latest()->paginate(15)->appends($request->query());
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'cost_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'cost_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Soft delete the specified product.
     * The product won't appear in listings but historical
     * transaction data referencing it remains intact.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // This performs a soft delete thanks to the SoftDeletes trait

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
