<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kasirin Aja' }} — POS</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via Vite (built by Breeze) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js for interactive UI (lightweight) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Animations for Premium UX */
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark-glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Scrollbar hide utility */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Flash message fade-out */
        .flash-message {
            animation: flashFadeOut 4s ease-in-out forwards;
        }
        @keyframes flashFadeOut {
            0%, 70% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); pointer-events: none; }
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full">
        {{-- ─── Navigation Bar ──────────────────────────────────── --}}
        <nav class="bg-slate-900 border-b border-slate-800 shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    {{-- Logo / Brand --}}
                    <div class="flex items-center">
                        <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('pos.index') }}" class="flex items-center gap-2">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            <span class="text-xl font-bold text-white tracking-tight">Kasirin Aja</span>
                        </a>
                    </div>

                    {{-- Nav Links --}}
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-center space-x-1">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('dashboard') }}"
                                   class="rounded-lg px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200
                                          {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : '' }}">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Dashboard
                                </a>
                            @endif
                            <a href="{{ route('pos.index') }}"
                               class="rounded-lg px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200
                                      {{ request()->routeIs('pos.*') ? 'bg-white/15 text-white' : '' }}">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Kasir
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('products.index') }}"
                                   class="rounded-lg px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200
                                          {{ request()->routeIs('products.*') ? 'bg-white/15 text-white' : '' }}">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> Produk
                                </a>
                                <a href="{{ route('categories.index') }}"
                                   class="rounded-lg px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200
                                          {{ request()->routeIs('categories.*') ? 'bg-white/15 text-white' : '' }}">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Kategori
                                </a>
                            @endif
                            <a href="{{ route('transactions.index') }}"
                               class="rounded-lg px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200
                                      {{ request()->routeIs('transactions.*') ? 'bg-white/15 text-white' : '' }}">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Transaksi
                            </a>
                        </div>
                    </div>

                    {{-- User Dropdown --}}
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium text-white capitalize">
                                {{ Auth::user()->role }}
                            </span>
                            <span class="text-sm text-white/70">{{ Auth::user()->name ?? 'Kasir' }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-lg bg-white/10 px-3 py-1.5 text-sm font-medium text-white hover:bg-white/20 transition-all duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile Nav --}}
            <div class="md:hidden border-t border-white/10">
                <div class="flex space-x-1 px-4 py-2 overflow-x-auto scrollbar-hide">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('dashboard') }}" 
                           class="flex-shrink-0 rounded-lg px-3 py-2 text-center text-xs font-medium text-white/80 hover:bg-white/10
                                  {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : '' }}">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Dashboard
                        </a>
                    @endif
                    <a href="{{ route('pos.index') }}" 
                       class="flex-shrink-0 rounded-lg px-3 py-2 text-center text-xs font-medium text-white/80 hover:bg-white/10
                              {{ request()->routeIs('pos.*') ? 'bg-white/15 text-white' : '' }}">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Kasir
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('products.index') }}" 
                           class="flex-shrink-0 rounded-lg px-3 py-2 text-center text-xs font-medium text-white/80 hover:bg-white/10
                                  {{ request()->routeIs('products.*') ? 'bg-white/15 text-white' : '' }}">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> Produk
                        </a>
                        <a href="{{ route('categories.index') }}" 
                           class="flex-shrink-0 rounded-lg px-3 py-2 text-center text-xs font-medium text-white/80 hover:bg-white/10
                                  {{ request()->routeIs('categories.*') ? 'bg-white/15 text-white' : '' }}">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Kategori
                        </a>
                    @endif
                    <a href="{{ route('transactions.index') }}" 
                       class="flex-shrink-0 rounded-lg px-3 py-2 text-center text-xs font-medium text-white/80 hover:bg-white/10
                              {{ request()->routeIs('transactions.*') ? 'bg-white/15 text-white' : '' }}">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Transaksi
                    </a>
                </div>
            </div>
        </nav>

        {{-- ─── Flash Messages ──────────────────────────────────── --}}
        @if (session('success'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4 flash-message">
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700 flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4 flash-message">
                <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700 flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- ─── Main Content ───────────────────────────────────── --}}
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>
    </div>

    {{-- Per-page scripts --}}
    @stack('scripts')
</body>
</html>
