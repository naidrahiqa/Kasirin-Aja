@extends('layouts.app')
@section('content')

{{-- ─── Page Header ────────────────────────────────────────────── --}}
<div class="mb-8 animate-fade-in-up">
    <a href="{{ route('categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 w-fit">
        ← Kembali ke Daftar Kategori
    </a>
    <h1 class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Kategori Baru</h1>
</div>

{{-- ─── Form ───────────────────────────────────────────────────── --}}
<div class="max-w-xl animate-fade-in-up delay-100">
    <div class="rounded-3xl bg-white p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="contoh: Makanan Berat"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400
                              focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all duration-200
                              @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-all duration-200 hover:shadow-md">
                    💾 Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}"
                   class="rounded-xl bg-gray-100 px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-200 transition-all duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
