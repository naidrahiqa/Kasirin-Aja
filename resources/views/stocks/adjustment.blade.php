@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="mb-8 animate-fade-in-up">
    <h1 class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight">Quick Stock Adjustment</h1>
    <p class="mt-1 text-sm text-gray-500">Scan barcode barang untuk memproses stok masuk, keluar, atau penyesuaian.</p>
</div>

{{-- ─── Alert Messages ─────────────────────────────────────────── --}}
@if(session('success'))
<div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 text-green-700 animate-fade-in-up flex items-center gap-3">
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200 text-red-700 animate-fade-in-up flex items-center gap-3">
    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
    {{-- ─── Scan Section ───────────────────────────────────────────── --}}
    <div class="animate-fade-in-up delay-100">
        <div class="rounded-3xl bg-white p-8 shadow-xl border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">1. Scan Barcode</h2>
            <p class="text-sm text-gray-500 mb-6">Pastikan kursor ada di kotak ini, lalu tembak barcode menggunakan scanner.</p>
            
            <div class="relative">
                <input type="text"
                       id="scanner_input"
                       autofocus
                       placeholder="Scan di sini..."
                       class="w-full rounded-2xl border-2 border-indigo-200 bg-indigo-50/30 px-6 py-5 text-xl font-mono text-center text-gray-900 placeholder-indigo-300
                              focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/20 transition-all duration-300 shadow-inner">
                <div id="scan_loading" class="hidden absolute right-6 top-6">
                    <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <div id="scan_error" class="hidden mt-4 rounded-xl bg-red-50 p-4 border border-red-200 text-sm text-red-700"></div>
        </div>
    </div>

    {{-- ─── Processing Form ────────────────────────────────────────── --}}
    <div class="animate-fade-in-up delay-200">
        <div id="adjustment_panel" class="opacity-50 pointer-events-none transition-opacity duration-300 rounded-3xl bg-white p-8 shadow-xl border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-6">2. Proses Stok</h2>
            
            <div class="mb-6 rounded-2xl bg-gray-50 p-5 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Produk</p>
                    <p id="display_name" class="text-lg font-bold text-gray-900">-</p>
                    <p id="display_barcode" class="text-sm font-mono text-gray-500">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sisa Stok</p>
                    <p id="display_stock" class="text-3xl font-extrabold text-indigo-600">-</p>
                </div>
            </div>

            <form action="{{ route('stocks.storeAdjustment') }}" method="POST" id="adjustment_form">
                @csrf
                <input type="hidden" name="product_id" id="product_id">
                
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Mutasi</label>
                        <select name="type" id="mutation_type" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                            <option value="in">Masuk (In)</option>
                            <option value="out">Keluar (Out)</option>
                            <option value="adjustment">Penyesuaian Fisik Opname</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah / Qty</label>
                        <input type="number" name="quantity" id="quantity_input" min="1" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-lg font-bold text-center focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Referensi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="reference" placeholder="Misal: Retur supplier, barang rusak" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                </div>

                <button type="submit" class="w-full rounded-xl bg-indigo-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-300">
                    Selesai & Simpan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scannerInput = document.getElementById('scanner_input');
        const scanLoading = document.getElementById('scan_loading');
        const scanError = document.getElementById('scan_error');
        
        const adjustmentPanel = document.getElementById('adjustment_panel');
        const displayProductName = document.getElementById('display_name');
        const displayProductBarcode = document.getElementById('display_barcode');
        const displayProductStock = document.getElementById('display_stock');
        
        const productIdInput = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity_input');
        const mutationType = document.getElementById('mutation_type');
        const adjustmentForm = document.getElementById('adjustment_form');

        let typingTimer;
        
        // Listen for scanner input (Scanner typically fires Enter)
        scannerInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                processBarcode(this.value.trim());
            }
        });

        function processBarcode(barcode) {
            if (!barcode) return;
            
            scanLoading.classList.remove('hidden');
            scanError.classList.add('hidden');
            
            fetch(`/products/barcode/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    scanLoading.classList.add('hidden');
                    if (data.success) {
                        // Product found
                        const product = data.data;
                        
                        // Update UI
                        displayProductName.textContent = product.name;
                        displayProductBarcode.textContent = product.barcode || '-';
                        displayProductStock.textContent = product.stock;
                        
                        // Update Form
                        productIdInput.value = product.id;
                        quantityInput.value = 1; // Default to 1
                        
                        // Enable Panel
                        adjustmentPanel.classList.remove('opacity-50', 'pointer-events-none');
                        
                        // Clear scanner & redirect focus to quantity
                        scannerInput.value = '';
                        quantityInput.focus();
                        quantityInput.select();
                    } else {
                        // Not found
                        showError(data.message || 'Produk tidak ditemukan');
                    }
                })
                .catch(err => {
                    scanLoading.classList.add('hidden');
                    showError('Terjadi kesalahan sistem.');
                    console.error(err);
                });
        }

        function showError(message) {
            scanError.textContent = message;
            scanError.classList.remove('hidden');
            adjustmentPanel.classList.add('opacity-50', 'pointer-events-none');
            scannerInput.select();
        }
        
        // Helper to refocus scanner if clicking outside
        // Uncomment if you want aggressive refocus
        /*
        document.addEventListener('click', function(e) {
            if (!adjustmentPanel.contains(e.target) && e.target !== scannerInput) {
                scannerInput.focus();
            }
        });
        */
    });
</script>

@endsection
