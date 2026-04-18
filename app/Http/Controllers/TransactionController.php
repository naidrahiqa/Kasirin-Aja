<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Process the checkout from the POS cashier.
     *
     * Expects a JSON payload:
     * {
     *   "items": [
     *     { "product_id": 1, "quantity": 2 },
     *     { "product_id": 5, "quantity": 1 }
     *   ],
     *   "payment_method": "cash"
     * }
     *
     * SECURITY: We fetch real prices from the database.
     * Never trust prices sent from the frontend.
     */
    public function checkout(Request $request): JsonResponse
    {
        // Validate the incoming request
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|in:cash,debit,qris',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated) {
                // 1. Generate a unique invoice number: INV-YYYYMMDD-XXX
                $today = Carbon::now()->format('Ymd');
                $lastTransaction = Transaction::where('invoice_number', 'like', "INV-{$today}-%")
                    ->orderBy('invoice_number', 'desc')
                    ->first();

                if ($lastTransaction) {
                    $lastNumber = (int) substr($lastTransaction->invoice_number, -3);
                    $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '001';
                }

                $invoiceNumber = "INV-{$today}-{$newNumber}";

                // 2. Fetch all required products at once (efficient single query)
                $productIds = collect($validated['items'])->pluck('product_id');
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                // 3. Calculate totals and prepare detail records
                $totalAmount = 0;
                $detailsData = [];

                foreach ($validated['items'] as $item) {
                    $product = $products->get($item['product_id']);

                    if (! $product) {
                        throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                    }

                    // Check stock availability
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi. Tersedia: {$product->stock}");
                    }

                    $unitPrice = $product->price; // Real price from DB
                    $subtotal = $unitPrice * $item['quantity'];
                    $totalAmount += $subtotal;

                    $detailsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price_at_time_of_sale' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];

                    // 4. Decrease stock
                    $product->decrement('stock', $item['quantity']);
                }

                // 5. Create the main transaction record
                $transaction = Transaction::create([
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'user_id' => Auth::id(),
                ]);

                // 6. Create all transaction detail records
                foreach ($detailsData as $detail) {
                    $transaction->details()->create($detail);
                }

                return $transaction;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'redirect_url' => route('transactions.receipt', $transaction->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Export the filtered transactions to a CSV file.
     */
    public function exportCsv(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('method') && $request->input('method') !== 'all') {
            $query->where('payment_method', $request->input('method'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->get();

        $filename = 'laporan-transaksi-'.date('Ymd-His').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Tanggal', 'No. Invoice', 'Kasir', 'Metode Pembayaran', 'Total'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->created_at->format('d/m/Y H:i'),
                    $trx->invoice_number,
                    $trx->user->name ?? '-',
                    strtoupper($trx->payment_method),
                    $trx->total_amount,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the receipt for a completed transaction.
     */
    public function receipt(Transaction $transaction): View
    {
        // Eager load relationships for the receipt
        $transaction->load(['details.product', 'user']);

        return view('transactions.receipt', compact('transaction'));
    }

    /**
     * Display a listing of all transactions with search & filter.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with('user');

        // Search by invoice number
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }

        // Filter by payment method
        if ($request->filled('method') && $request->input('method') !== 'all') {
            $query->where('payment_method', $request->input('method'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = (clone $query)->latest()->paginate(20)->appends($request->query());

        // Summary stats for the filtered results (Optimization: reuse the query)
        $summaryQuery = clone $query;

        $totalFiltered = $summaryQuery->sum('total_amount');
        $countFiltered = $summaryQuery->count();

        return view('transactions.index', compact('transactions', 'totalFiltered', 'countFiltered'));
    }
}
