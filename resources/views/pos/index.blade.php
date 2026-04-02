@extends('layouts.app')
@section('content')

{{-- ─── POS Cashier Interface ──────────────────────────────────── --}}
<div x-data="posApp()" x-init="init()" class="flex flex-col lg:flex-row gap-6 -mt-2">

    {{-- ═══ LEFT COLUMN: Product Grid ═══ --}}
    <div class="flex-1 animate-fade-in-up">
        <div class="mb-4">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Kasir</h1>
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Cari produk..."
                           class="w-64 rounded-xl border border-gray-200 pl-10 pr-4 py-2.5 text-sm
                                  focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200">
                    <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </div>

            {{-- Category Filter Tabs --}}
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button x-on:click="activeCategory = 0" 
                        :class="activeCategory === 0 ? 'bg-gray-900 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg> Semua
                </button>
                @foreach($categories as $category)
                    <button x-on:click="activeCategory = {{ $category->id }}"
                            :class="activeCategory === {{ $category->id }} ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
            <template x-for="product in products" :key="product.id">
                <button
                    x-on:click="addToCart({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        stock: product.stock
                    })"
                    class="group relative overflow-hidden rounded-2xl bg-white border border-gray-100 p-4 text-left shadow-sm
                           hover:border-indigo-300 hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300 active:scale-[0.95]"
                    :class="getCartQty(product.id) > 0 ? 'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50/30' : ''"
                >
                    {{-- Product Emoji / Icon --}}
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 text-xl mb-3">
                        <svg class="w-5 h-5 inline-block mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    {{-- Product Info --}}
                    <h3 class="text-sm font-semibold text-gray-900 truncate" x-text="product.name"></h3>
                    <p class="mt-1 text-sm font-bold text-indigo-600">Rp <span x-text="formatNumber(product.price)"></span></p>
                    <p class="mt-0.5 text-xs text-gray-400">Stok: <span x-text="product.stock"></span></p>

                    {{-- Cart Quantity Badge --}}
                    <div x-show="getCartQty(product.id) > 0"
                         x-transition
                         class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white shadow-sm">
                        <span x-text="getCartQty(product.id)"></span>
                    </div>
                </button>
            </template>
        </div>
        
        {{-- Load More / Loading State --}}
        <div class="mt-8 text-center pb-8">
            <template x-if="isFetchingProducts">
                <div class="inline-flex items-center text-sm text-gray-400">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memuat produk...
                </div>
            </template>
            <template x-if="!isFetchingProducts && hasMoreProducts">
                <button x-on:click="fetchProducts(false)" class="rounded-full bg-white border border-gray-200 px-6 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 shadow-sm transition">
                    Tampilkan Lebih Banyak
                </button>
            </template>
            <template x-if="!isFetchingProducts && products.length === 0">
                <div class="rounded-2xl bg-white border border-gray-100 p-12 text-center mt-4">
                    <p class="text-gray-400 text-sm">Produk tidak ditemukan.</p>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══ RIGHT COLUMN: Cart Summary ═══ --}}
    <div class="w-full lg:w-96 lg:flex-shrink-0 animate-fade-in-up delay-200">
        <div class="sticky top-4 rounded-3xl bg-white border border-gray-100 shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            {{-- Cart Header --}}
            <div class="bg-slate-900 px-6 py-5 border-b border-slate-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <span><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span> Keranjang
                    </h2>
                    <span class="rounded-full bg-white/20 px-3 py-0.5 text-xs font-medium text-white"
                          x-text="cart.length + ' item'"></span>
                </div>
            </div>

            {{-- Cart Items --}}
            <div class="max-h-[400px] overflow-y-auto divide-y divide-gray-50">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-500">
                                Rp <span x-text="formatNumber(item.price)"></span> × <span x-text="item.qty"></span>
                            </p>
                        </div>

                        {{-- Quantity Controls --}}
                        <div class="flex items-center gap-1.5">
                            <button x-on:click="decreaseQty(index)"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-600 transition-colors text-sm font-bold">
                                −
                            </button>
                            <span class="w-8 text-center text-sm font-semibold text-gray-900" x-text="item.qty"></span>
                            <button x-on:click="increaseQty(index)"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-600 transition-colors text-sm font-bold">
                                +
                            </button>
                        </div>

                        {{-- Subtotal --}}
                        <div class="text-right w-24 flex-shrink-0">
                            <p class="text-sm font-bold text-gray-900">Rp <span x-text="formatNumber(item.price * item.qty)"></span></p>
                        </div>

                        {{-- Remove --}}
                        <button x-on:click="removeFromCart(index)"
                                class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-300 hover:bg-red-50 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                {{-- Empty Cart State --}}
                <div x-show="cart.length === 0" class="px-6 py-12 text-center">
                    <p class="text-gray-400 text-sm">Keranjang kosong</p>
                    <p class="text-gray-300 text-xs mt-1">Klik produk untuk menambahkan</p>
                </div>
            </div>

            {{-- Cart Footer: Total & Checkout --}}
            <div class="border-t border-gray-100 bg-gray-50/50 p-5" x-show="cart.length > 0">
                {{-- Payment Method --}}
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Metode Pembayaran</label>
                    <select x-model="paymentMethod"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                        <option value="cash"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Cash</option>
                        <option value="debit"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Debit</option>
                        <option value="qris"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> QRIS</option>
                    </select>
                </div>

                {{-- Total --}}
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-600">Total</span>
                    <span class="text-2xl font-bold text-gray-900">
                        Rp <span x-text="formatNumber(grandTotal)"></span>
                    </span>
                </div>

                {{-- Checkout Button triggers Modal --}}
                <button x-on:click="openPaymentModal()"
                        :disabled="isLoading"
                        class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 py-4 text-base font-bold text-white shadow-lg
                               hover:from-emerald-400 hover:to-teal-500 hover:scale-[1.02] hover:-translate-y-0.5 transition-all duration-300 hover:shadow-emerald-500/40
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isLoading"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bayar Sekarang</span>
                    <span x-show="isLoading"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Memproses...</span>
                </button>

                {{-- Clear Cart --}}
                <button x-on:click="clearCart()"
                        class="w-full mt-2 rounded-xl bg-white border border-gray-200 py-2.5 text-xs font-medium text-gray-500
                               hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ PAYMENT MODAL (AI Special Feature) ═══ --}}
    <div x-show="isModalOpen" 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
         
        {{-- Background overlay --}}
        <div x-show="isModalOpen"
             x-transition.opacity
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100 p-8">
                 
                <div class="mb-5 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 mb-4 shadow-inner">
                        <span class="text-3xl"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Pembayaran</h3>
                    <p class="text-sm text-gray-500 mt-1">Selesaikan transaksi ini</p>
                </div>

                {{-- Total to pay --}}
                <div class="rounded-2xl bg-gray-50 p-4 border border-gray-100 mb-5 text-center">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Tagihan</p>
                    <p class="text-3xl font-extrabold text-gray-900">Rp <span x-text="formatNumber(grandTotal)"></span></p>
                </div>

                <template x-if="paymentMethod === 'cash'">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Uang Diterima (Rp)</label>
                        <input type="number" 
                               x-model.number="cashGiven" 
                               class="w-full text-center text-xl font-bold rounded-2xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-4 shadow-sm"
                               placeholder="Masukkan nominal...">
                        
                        {{-- Suggestion Buttons --}}
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            <button x-on:click="cashGiven = grandTotal" class="py-2 rounded-xl bg-gray-100 text-sm font-medium hover:bg-gray-200 transition">Uang Pas</button>
                            <button x-on:click="cashGiven = Math.ceil(grandTotal/50000)*50000" class="py-2 rounded-xl bg-gray-100 text-sm font-medium hover:bg-gray-200 transition">Ke 50 Ribu</button>
                            <button x-on:click="cashGiven = Math.ceil(grandTotal/100000)*100000" class="py-2 rounded-xl bg-gray-100 text-sm font-medium hover:bg-gray-200 transition">Ke 100 Ribu</button>
                        </div>

                        {{-- Change details --}}
                        <div class="mt-5 text-center" x-show="cashGiven > 0">
                            <p class="text-sm font-medium text-gray-500 mb-1">Kembalian:</p>
                            <p class="text-2xl font-bold" 
                               :class="cashGiven >= grandTotal ? 'text-emerald-600' : 'text-red-500'">
                                Rp <span x-text="cashGiven >= grandTotal ? formatNumber(cashGiven - grandTotal) : 'Uang Kurang!'"></span>
                            </p>
                        </div>
                    </div>
                </template>

                <div class="mt-8 flex gap-3">
                    <button x-on:click="isModalOpen = false" 
                            class="w-full justify-center rounded-2xl bg-white px-4 py-3.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button x-on:click="checkout()" 
                            :disabled="isLoading || (paymentMethod === 'cash' && cashGiven < grandTotal)"
                            class="w-full justify-center rounded-2xl bg-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isLoading">Proses 🚀</span>
                        <span x-show="isLoading"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function posApp() {
    return {
        cart: [],
        products: [],
        searchQuery: '',
        activeCategory: 0,
        productsPage: 1,
        hasMoreProducts: true,
        isFetchingProducts: false,
        paymentMethod: 'cash',
        isLoading: false,
        isModalOpen: false,
        cashGiven: 0,
        searchDebounce: null,

        init() {
            // Restore cart from sessionStorage (persists across page navigations)
            const saved = sessionStorage.getItem('pos_cart');
            if (saved) {
                try { this.cart = JSON.parse(saved); } catch (e) { this.cart = []; }
            }

            // Auto-save cart on changes
            this.$watch('cart', (val) => {
                sessionStorage.setItem('pos_cart', JSON.stringify(val));
            }, { deep: true });

            this.$watch('searchQuery', () => {
                clearTimeout(this.searchDebounce);
                this.searchDebounce = setTimeout(() => {
                    this.fetchProducts(true);
                }, 300);
            });

            this.$watch('activeCategory', () => {
                this.fetchProducts(true);
            });

            // Initial Load
            this.fetchProducts(true);

            // Global Barcode Scanner Listener
            let barcodeString = '';
            let barcodeTimeout;
            
            document.addEventListener('keydown', (e) => {
                // Ignore if modal is open or typing in a text input manually
                if (this.isModalOpen || e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    if (e.target.id !== 'barcode_hidden_input') return;
                }

                if (e.key === 'Enter' && barcodeString.length > 0) {
                    e.preventDefault();
                    this.scanBarcode(barcodeString);
                    barcodeString = '';
                } else if (e.key.length === 1) { // Normal characters
                    barcodeString += e.key;
                    
                    // Clear buffer if there's a long delay (human typing vs scanner)
                    clearTimeout(barcodeTimeout);
                    barcodeTimeout = setTimeout(() => {
                        barcodeString = '';
                    }, 50); // 50ms timeout (scanners are usually 10-20ms per char)
                }
            });
        },

        async scanBarcode(barcode) {
            try {
                const response = await fetch(`/products/barcode/${barcode}`);
                const data = await response.json();
                
                if (data.success) {
                    this.addToCart(data.data);
                    // Optional visual feedback
                    const searchInput = document.querySelector('input[placeholder="Cari produk..."]');
                    if (searchInput) {
                        searchInput.classList.add('bg-emerald-50');
                        setTimeout(() => searchInput.classList.remove('bg-emerald-50'), 300);
                    }
                } else {
                    alert('Produk tidak ditemukan: ' + barcode);
                }
            } catch (err) {
                console.error('Scan error:', err);
            }
        },

        async fetchProducts(reset = false) {
            if (reset) {
                this.productsPage = 1;
                this.products = [];
                this.hasMoreProducts = true;
            }

            if (!this.hasMoreProducts || this.isFetchingProducts && !reset) return;
            
            this.isFetchingProducts = true;
            
            try {
                const response = await fetch(`/api/pos/products?page=${this.productsPage}&search=${encodeURIComponent(this.searchQuery)}&category_id=${this.activeCategory}`);
                const data = await response.json();
                
                if (reset) {
                    this.products = data.data;
                } else {
                    this.products = [...this.products, ...data.data];
                }
                
                this.hasMoreProducts = data.current_page < data.last_page;
                this.productsPage++;
            } catch (err) {
                console.error('Failed to fetch products:', err);
            } finally {
                this.isFetchingProducts = false;
            }
        },

        /**
         * Add a product to the cart. If already in cart, increase quantity.
         */
        addToCart(product) {
            const existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.qty >= product.stock) {
                    alert(`Stok ${product.name} tidak mencukupi! Tersedia: ${product.stock}`);
                    return;
                }
                existing.qty++;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    stock: product.stock,
                    qty: 1,
                });
            }
        },

        /**
         * Get quantity of a product in cart (for badge display).
         */
        getCartQty(productId) {
            const item = this.cart.find(i => i.id === productId);
            return item ? item.qty : 0;
        },

        increaseQty(index) {
            const item = this.cart[index];
            if (item.qty >= item.stock) {
                alert(`Stok ${item.name} tidak mencukupi! Tersedia: ${item.stock}`);
                return;
            }
            item.qty++;
        },

        decreaseQty(index) {
            if (this.cart[index].qty > 1) {
                this.cart[index].qty--;
            } else {
                this.removeFromCart(index);
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        clearCart() {
            if (confirm('Kosongkan semua item di keranjang?')) {
                this.cart = [];
            }
        },

        /**
         * Computed property: grand total of all items.
         */
        get grandTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        /**
         * Open the Payment Modal if cart is valid.
         */
        openPaymentModal() {
            if (this.cart.length === 0) {
                alert('Keranjang kosong!');
                return;
            }
            this.cashGiven = 0; // reset cash
            this.isModalOpen = true; // show modal
        },

        /**
         * Send checkout request to the backend via AJAX.
         */
        async checkout() {
            if (this.paymentMethod === 'cash' && this.cashGiven < this.grandTotal) {
                alert('Uang pelanggan kurang!');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('{{ route("transactions.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        items: this.cart.map(item => ({
                            product_id: item.id,
                            quantity: item.qty,
                        })),
                        payment_method: this.paymentMethod,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Clear cart and redirect to receipt
                    this.cart = [];
                    sessionStorage.removeItem('pos_cart');
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                console.error('Checkout error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Format a number with thousand separators (Indonesian style).
         */
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
    };
}
</script>
@endpush
