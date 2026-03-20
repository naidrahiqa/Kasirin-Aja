@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-8 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Kategori</h1>
        <p class="mt-1 text-sm text-gray-500">Atur kategori produk yang dijual.</p>
    </div>
    <a href="{{ route('categories.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-500 hover:scale-[1.02] hover:-translate-y-0.5 transition-all duration-300 hover:shadow-indigo-500/40">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Kategori
    </a>
</div>

{{-- ─── Categories Grid ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in-up delay-100">
    @forelse ($categories as $category)
        <div class="group relative rounded-2xl bg-white border border-gray-100 p-6 shadow-sm hover:shadow-xl hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300">
            {{-- Category Icon --}}
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 text-xl mb-4">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>

            {{-- Category Info --}}
            <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
            <p class="mt-1 text-sm text-gray-500">
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">
                    {{ $category->products_count }} produk
                </span>
            </p>
            <p class="mt-2 text-xs text-gray-400">
                Dibuat: {{ $category->created_at->format('d M Y') }}
            </p>

            {{-- Action Buttons --}}
            <div class="mt-4 flex items-center gap-2">
                <a href="{{ route('categories.edit', $category) }}"
                   class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100 transition-colors duration-200">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                </a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors duration-200">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-2xl bg-white border border-gray-100 p-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 mb-4">
                <span class="text-2xl"><svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></span>
            </div>
            <p class="text-gray-400 text-sm">Belum ada kategori.</p>
            <a href="{{ route('categories.create') }}" class="mt-2 inline-block text-indigo-600 text-sm hover:underline">Tambah sekarang →</a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endif

@endsection
