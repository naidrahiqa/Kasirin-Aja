@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 animate-fade-in-up gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Transaksi</h1>
        <p class="mt-1 text-sm text-gray-500">Semua transaksi yang telah dilakukan.</p>
    </div>
    
    <a href="{{ route('transactions.export', request()->all()) }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-500 hover:scale-[1.02] hover:-translate-y-0.5 transition-all duration-300 hover:shadow-emerald-500/40">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Export CSV
    </a>
</div>

{{-- ─── Filter & Search Bar ──────────────────────────────────────── --}}
<div class="animate-fade-in-up delay-100 mb-6 rounded-2xl bg-white p-5 shadow-sm border border-gray-100">
    <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col sm:flex-row gap-3">
        {{-- Search --}}
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari no. invoice..."
                   class="w-full rounded-xl border border-gray-200 pl-10 pr-4 py-2.5 text-sm
                          focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200">
            <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>

        {{-- Date From --}}
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm
                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200"
               title="Dari tanggal">

        {{-- Date To --}}
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm
                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200"
               title="Sampai tanggal">

        {{-- Payment Method --}}
        <select name="method"
                class="rounded-xl border border-gray-200 px-3 py-2.5 text-sm
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200">
            <option value="all" {{ request('method') === 'all' ? 'selected' : '' }}>Semua Metode</option>
            <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Cash</option>
            <option value="debit" {{ request('method') === 'debit' ? 'selected' : '' }}><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Debit</option>
            <option value="qris" {{ request('method') === 'qris' ? 'selected' : '' }}><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> QRIS</option>
        </select>

        {{-- Buttons --}}
        <button type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all duration-200">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Filter
        </button>
        @if(request()->hasAny(['search', 'date_from', 'date_to', 'method']))
            <a href="{{ route('transactions.index') }}"
               class="rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200 transition-all duration-200 text-center">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Reset
            </a>
        @endif
    </form>
</div>

{{-- ─── Summary Strip ────────────────────────────────────────── --}}
<div class="animate-fade-in-up delay-100 mb-6 flex flex-col sm:flex-row gap-3">
    <div class="flex-1 rounded-xl bg-indigo-50 border border-indigo-100 px-4 py-3 flex items-center gap-3">
        <span class="text-xl"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
        <div>
            <p class="text-xs font-medium text-indigo-500 uppercase">Total Transaksi</p>
            <p class="text-lg font-bold text-indigo-700">{{ number_format($countFiltered) }}</p>
        </div>
    </div>
    <div class="flex-1 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-center gap-3">
        <span class="text-xl"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
        <div>
            <p class="text-xs font-medium text-emerald-500 uppercase">Total Pendapatan</p>
            <p class="text-lg font-bold text-emerald-700">Rp {{ number_format($totalFiltered, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- ─── Transactions Table ─────────────────────────────────────── --}}
<div class="animate-fade-in-up delay-200 rounded-3xl bg-white shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($transactions as $trx)
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
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('transactions.receipt', $trx->id) }}"
                               class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 transition-colors duration-200">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Lihat Struk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            @if(request()->hasAny(['search', 'date_from', 'date_to', 'method']))
                                Tidak ada transaksi yang cocok dengan filter.
                                <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:underline ml-1">Reset filter</a>
                            @else
                                Belum ada transaksi.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

@endsection
