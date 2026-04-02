<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function adjustment()
    {
        return view('stocks.adjustment');
    }

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|not_in:0',
            'reference' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'out' && $product->stock < abs($validated['quantity'])) {
            return back()->with('error', 'Stok tidak cukup untuk dikeluarkan!');
        }

        $qty = abs($validated['quantity']);
        if ($validated['type'] === 'out' || ($validated['type'] === 'adjustment' && $validated['quantity'] < 0)) {
             $product->decrement('stock', $qty);
        } else {
             $product->increment('stock', $qty);
        }

        StockMutation::create([
            'product_id' => $product->id,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'reference' => $validated['reference'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', "Stok {$product->name} berhasil diperbarui (Sekarang: {$product->fresh()->stock}).");
    }
}
