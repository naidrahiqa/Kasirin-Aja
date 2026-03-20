@extends('layouts.app')
@section('content')

{{-- ─── Receipt View ────────────────────────────────────────────── --}}
<div class="max-w-md mx-auto">
    {{-- Action Buttons (no-print) --}}
    <div class="mb-4 flex items-center gap-3 print:hidden animate-fade-in-up">
        <a href="{{ route('pos.index') }}"
           class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-all duration-200">
            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Kembali ke Kasir
        </a>
        <a href="{{ route('transactions.index') }}"
           class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-all duration-200">
            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Riwayat
        </a>
        <button onclick="window.print()"
                class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-all duration-200">
            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak Struk
        </button>
    </div>

    {{-- Receipt Card --}}
    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden animate-fade-in-up delay-100" id="receipt">
        {{-- Header --}}
        <div class="bg-slate-900 px-6 py-6 text-center text-white border-b border-slate-800">
            <h1 class="text-xl font-bold tracking-tight">Kasirin Aja</h1>
            <p class="mt-1 text-indigo-200 text-xs">Sistem Point of Sale</p>
        </div>

        <div class="px-6 py-5">
            {{-- Invoice Info --}}
            <div class="border-b border-dashed border-gray-200 pb-4 mb-4">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>No. Invoice</span>
                    <span class="font-mono font-semibold text-gray-900">{{ $transaction->invoice_number }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Tanggal</span>
                    <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Kasir</span>
                    <span>{{ $transaction->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Pembayaran</span>
                    @php
                        $methodIcons = ['cash' => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>', 'debit' => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>', 'qris' => '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>'];
                    @endphp
                    <span class="capitalize font-medium">{!! $methodIcons[$transaction->payment_method] ?? '' !!} {{ $transaction->payment_method }}</span>
                </div>
            </div>

            {{-- Line Items --}}
            <div class="border-b border-dashed border-gray-200 pb-4 mb-4 space-y-2">
                @foreach ($transaction->details as $detail)
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $detail->product->name ?? 'Produk Dihapus' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $detail->quantity }} × Rp {{ number_format($detail->unit_price_at_time_of_sale, 0, ',', '.') }}
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 ml-4">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="space-y-2 mb-3">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Subtotal ({{ $transaction->details->sum('quantity') }} item)</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Total --}}
            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                <span class="text-base font-bold text-gray-900">TOTAL</span>
                <span class="text-xl font-bold text-indigo-600">
                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-6 py-4 text-center">
            <p class="text-xs text-gray-400">Terima kasih atas kunjungan Anda!</p>
            <p class="text-xs text-gray-300 mt-0.5">— Kasirin Aja POS —</p>
        </div>
    </div>
</div>

{{-- Print-specific styles --}}
<style>
    @media print {
        nav, .print\:hidden { display: none !important; }
        body { background: white !important; }
        #receipt { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
    }
</style>

@endsection
