@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan hari ini dan statistik terbaru.</p>
</div>

{{-- ─── Summary Cards ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    {{-- Card: Total Sales Today --}}
    <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Penjualan Hari Ini</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Card: Transaction Count Today --}}
    <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
            <p class="text-xl font-bold text-gray-900">{{ $todayTransactionCount }}</p>
        </div>
    </div>

    {{-- Card: Total Products --}}
    <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Total Produk</p>
            <p class="text-xl font-bold text-gray-900">{{ $totalProducts }} <span class="text-xs font-normal text-gray-400">/ {{ $totalCategories }} kategori</span></p>
        </div>
    </div>

    {{-- Card: Monthly Revenue --}}
    <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- ─── Two Column Layout ──────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Weekly Sales Chart (Takes 2 columns) --}}
    <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            Penjualan 7 Hari Terakhir
        </h2>

        @php
            $maxSale = $weeklySales->max('total') ?: 1;
            $chartW   = 560;
            $chartH   = 160;
            $padLeft  = 48;
            $padRight = 16;
            $padTop   = 12;
            $padBot   = 28;
            $innerW   = $chartW - $padLeft - $padRight;
            $innerH   = $chartH - $padTop - $padBot;
            $n        = count($weeklySales);
            $stepX    = $n > 1 ? $innerW / ($n - 1) : $innerW;

            $points = [];
            foreach ($weeklySales as $i => $day) {
                $x = $padLeft + ($n > 1 ? $i * $stepX : $innerW / 2);
                $y = $padTop + $innerH - ($day['total'] / $maxSale) * $innerH;
                $points[] = ['x' => $x, 'y' => $y, 'total' => $day['total'], 'date' => $day['date']];
            }

            // Polyline string
            $polyline = collect($points)->map(fn($p) => "{$p['x']},{$p['y']}")->implode(' ');

            // Area fill path: go down to baseline, back left, close
            $areaPath = "M {$points[0]['x']},{$points[0]['y']} ";
            foreach (array_slice($points, 1) as $p) {
                $areaPath .= "L {$p['x']},{$p['y']} ";
            }
            $baseline = $padTop + $innerH;
            $areaPath .= "L {$points[count($points)-1]['x']},{$baseline} L {$points[0]['x']},{$baseline} Z";

            // Y-axis ticks (4 lines)
            $yTicks = [0, 0.25, 0.5, 0.75, 1.0];
        @endphp

        <div class="w-full overflow-x-auto">
            <svg viewBox="0 0 {{ $chartW }} {{ $chartH }}" class="w-full" style="min-width:280px;height:180px;" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.25"/>
                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0.02"/>
                    </linearGradient>
                </defs>

                {{-- Y grid lines --}}
                @foreach($yTicks as $t)
                    @php $gy = $padTop + $innerH - $t * $innerH; @endphp
                    <line x1="{{ $padLeft }}" y1="{{ $gy }}" x2="{{ $chartW - $padRight }}" y2="{{ $gy }}"
                          stroke="#e5e7eb" stroke-width="1"/>
                    @if($t > 0)
                        <text x="{{ $padLeft - 4 }}" y="{{ $gy + 4 }}" text-anchor="end"
                              font-size="9" fill="#9ca3af">
                            {{ number_format(($maxSale * $t) / 1000, 0) }}k
                        </text>
                    @endif
                @endforeach

                {{-- Area fill --}}
                @if(count($points) > 1)
                    <path d="{{ $areaPath }}" fill="url(#areaGrad)"/>
                @endif

                {{-- Line --}}
                @if(count($points) > 1)
                    <polyline points="{{ $polyline }}" fill="none" stroke="#6366f1" stroke-width="2.5"
                              stroke-linejoin="round" stroke-linecap="round"/>
                @endif

                {{-- Data points + labels --}}
                @foreach($points as $p)
                    {{-- Group: circle + tooltip title --}}
                    <g>
                        <title>{{ \Carbon\Carbon::parse($p['date'])->format('d M Y') }}: Rp {{ number_format($p['total'], 0, ',', '.') }}</title>
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="6" fill="transparent" stroke="transparent"/>
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" fill="white" stroke="#6366f1" stroke-width="2"/>
                    </g>
                    <text x="{{ $p['x'] }}" y="{{ $padTop + $innerH + 18 }}" text-anchor="middle"
                          font-size="9" fill="#6b7280">
                        {{ \Carbon\Carbon::parse($p['date'])->format('d/m') }}
                    </text>
                @endforeach
            </svg>
        </div>

        {{-- Summary below chart --}}
        <div class="mt-2 flex items-center gap-2 text-xs text-gray-400">
            <span class="inline-block w-3 h-0.5 bg-indigo-500 rounded"></span>
            Total penjualan per hari (7 hari terakhir)
        </div>
    </div>


    {{-- Top Products (Takes 1 column) --}}
    <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
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
<div class="mb-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <a href="{{ route('pos.index') }}" 
       class="group flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm hover:border-gray-200 hover:shadow transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition-colors"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Buka Kasir</p>
            <p class="text-xs text-gray-500">Mulai transaksi</p>
        </div>
    </a>
    <a href="{{ route('products.create') }}" 
       class="group flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm hover:border-gray-200 hover:shadow transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Tambah Produk</p>
            <p class="text-xs text-gray-500">Produk baru</p>
        </div>
    </a>
    <a href="{{ route('categories.index') }}" 
       class="group flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm hover:border-gray-200 hover:shadow transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600 transition-colors"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Kategori</p>
            <p class="text-xs text-gray-500">{{ $totalCategories }} kategori</p>
        </div>
    </a>
    <a href="{{ route('transactions.index') }}" 
       class="group flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm hover:border-gray-200 hover:shadow transition-all">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition-colors"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Riwayat</p>
            <p class="text-xs text-gray-400">Lihat transaksi</p>
        </div>
    </a>
</div>

{{-- ─── Recent Transactions Table ──────────────────────────────── --}}
<div class="rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Transaksi Terbaru
        </h2>
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
