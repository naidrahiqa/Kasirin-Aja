@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="mb-8 animate-fade-in-up">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan hari ini dan statistik terbaru.</p>
</div>

{{-- ─── Summary Cards ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    {{-- Card: Total Sales Today --}}
    <div class="animate-fade-in-up delay-100 relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white shadow-lg hover:shadow-indigo-500/30 hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-indigo-400/20 blur-xl"></div>
        <div class="relative">
            <p class="text-sm font-medium text-indigo-100"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Penjualan Hari Ini</p>
            <p class="mt-2 text-2xl font-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Card: Transaction Count Today --}}
    <div class="animate-fade-in-up delay-200 relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg hover:shadow-emerald-500/30 hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-emerald-400/20 blur-xl"></div>
        <div class="relative">
            <p class="text-sm font-medium text-emerald-100"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Transaksi Hari Ini</p>
            <p class="mt-2 text-2xl font-bold">{{ $todayTransactionCount }}</p>
        </div>
    </div>

    {{-- Card: Total Products --}}
    <div class="animate-fade-in-up delay-200 relative overflow-hidden rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 p-6 text-white shadow-lg hover:shadow-sky-500/30 hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-sky-400/20 blur-xl"></div>
        <div class="relative">
            <p class="text-sm font-medium text-sky-100"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> Total Produk</p>
            <p class="mt-2 text-2xl font-bold">{{ $totalProducts }}</p>
            <p class="text-xs text-sky-200 mt-1">{{ $totalCategories }} kategori</p>
        </div>
    </div>

    {{-- Card: Monthly Revenue --}}
    <div class="animate-fade-in-up delay-300 relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-white shadow-lg hover:shadow-amber-500/30 hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-4 -left-4 h-24 w-24 rounded-full bg-amber-400/20 blur-xl"></div>
        <div class="relative">
            <p class="text-sm font-medium text-amber-100"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Pendapatan Bulan Ini</p>
            <p class="mt-2 text-2xl font-bold">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- ─── Two Column Layout ──────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Weekly Sales Chart (Takes 2 columns) --}}
    <div class="lg:col-span-2 animate-fade-in-up delay-300 rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></span>
            Penjualan 7 Hari Terakhir
        </h2>

        @php
            $maxSale = $weeklySales->max('total') ?: 1;
        @endphp

        <div class="flex items-end gap-3 h-48">
            @forelse ($weeklySales as $day)
                @php
                    $heightPercent = ($day['total'] / $maxSale) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group">
                    <span class="text-xs font-medium text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        Rp {{ number_format($day['total'] / 1000, 0) }}k
                    </span>
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-indigo-500 to-purple-400 transition-all duration-500 hover:from-indigo-400 hover:to-purple-300 cursor-pointer"
                         style="height: {{ max($heightPercent, 4) }}%"
                         title="Rp {{ number_format($day['total'], 0, ',', '.') }}">
                    </div>
                    <span class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}
                    </span>
                </div>
            @empty
                <div class="flex-1 flex items-center justify-center text-gray-400 text-sm">
                    Belum ada data penjualan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Top Products (Takes 1 column) --}}
    <div class="animate-fade-in-up delay-300 rounded-2xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="p-2 bg-amber-50 text-amber-600 rounded-lg"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg></span>
            Produk Terlaris
        </h2>

        @if($topProducts->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">Belum ada data.</p>
        @else
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition-colors">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-bold
                            {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-200 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->total_sold }} terjual</p>
                        </div>
                        <span class="text-xs font-medium text-emerald-600">
                            Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ─── Quick Actions ─────────────────────────────────────────── --}}
<div class="animate-fade-in-up delay-300 mb-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
    <a href="{{ route('pos.index') }}" 
       class="group flex items-center gap-3 rounded-2xl bg-white border border-gray-100 p-4 shadow-sm hover:shadow-md hover:border-indigo-200 hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-lg group-hover:bg-indigo-100 transition-colors"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Buka Kasir</p>
            <p class="text-xs text-gray-400">Mulai transaksi</p>
        </div>
    </a>
    <a href="{{ route('products.create') }}" 
       class="group flex items-center gap-3 rounded-2xl bg-white border border-gray-100 p-4 shadow-sm hover:shadow-md hover:border-emerald-200 hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-lg group-hover:bg-emerald-100 transition-colors"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Tambah Produk</p>
            <p class="text-xs text-gray-400">Produk baru</p>
        </div>
    </a>
    <a href="{{ route('categories.index') }}" 
       class="group flex items-center gap-3 rounded-2xl bg-white border border-gray-100 p-4 shadow-sm hover:shadow-md hover:border-purple-200 hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-lg group-hover:bg-purple-100 transition-colors"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Kategori</p>
            <p class="text-xs text-gray-400">{{ $totalCategories }} kategori</p>
        </div>
    </a>
    <a href="{{ route('transactions.index') }}" 
       class="group flex items-center gap-3 rounded-2xl bg-white border border-gray-100 p-4 shadow-sm hover:shadow-md hover:border-amber-200 hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg group-hover:bg-amber-100 transition-colors"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Riwayat</p>
            <p class="text-xs text-gray-400">Lihat transaksi</p>
        </div>
    </a>
</div>

{{-- ─── Recent Transactions Table ──────────────────────────────── --}}
<div class="animate-fade-in-up delay-300 rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></span>
            <h2 class="text-lg font-bold text-gray-900">Transaksi Terbaru</h2>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
            Lihat Semua →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($recentTransactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-mono font-medium text-indigo-600">{{ $trx->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $trx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $methodColors = [
                                    'cash'  => 'bg-emerald-50 text-emerald-700',
                                    'debit' => 'bg-sky-50 text-sky-700',
                                    'qris'  => 'bg-purple-50 text-purple-700',
                                ];
                                $methodIcons = [
                                    'cash'  => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                                    'debit' => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
                                    'qris'  => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {{ $methodColors[$trx->payment_method] ?? 'bg-gray-50 text-gray-700' }}">
                                {!! $methodIcons[$trx->payment_method] ?? '' !!} {{ $trx->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('transactions.receipt', $trx->id) }}"
                               class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
