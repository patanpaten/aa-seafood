<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - AA Seafood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
            50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
        }
        .animate-bounce-slow { animation: bounce-slow 2s infinite; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden transition-opacity" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 z-50 lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-sm">
        <div class="h-full flex flex-col p-6">
            <div class="flex items-center space-x-3 mb-10">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-extrabold text-xl tracking-tighter text-slate-900 uppercase">AA Seafood</span>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto pr-2 custom-scrollbar">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 ml-3">Menu Utama</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('incoming-stocks.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('incoming-stocks.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Stok Masuk</span>
                </a>

                <a href="{{ route('sales.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('sales.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Penjualan</span>
                </a>

                @if(auth()->user()->isOwner())
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-8 mb-4 ml-3">Manajemen Master</p>
                    
                    <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('suppliers.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Supplier</span>
                    </a>

                    <a href="{{ route('partners.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('partners.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Partner Resto</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('categories.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span>Kategori Produk</span>
                    </a>

                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-8 mb-4 ml-3">Analitik</p>

                    <a href="{{ route('stock-adjustments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('stock-adjustments.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Stock Opname</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Laporan PDF</span>
                    </a>
                @endif
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight truncate">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200 lg:hidden">
            <div class="flex items-center justify-between p-4">
                <span class="font-extrabold text-lg tracking-tighter text-slate-900 uppercase">AA Seafood</span>
                <button @click="sidebarOpen = true" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </header>

        <div class="p-4 lg:p-10 max-w-7xl mx-auto">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div x-data="{ open: true }" 
                     x-show="open" 
                     x-cloak
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                    <!-- Overlay -->
                    <div x-show="open" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="open = false" 
                         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                    <!-- Modal Content -->
                    <div x-show="open"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-rose-100">
                        
                        <div class="p-8 sm:p-10">
                            <div class="flex flex-col items-center text-center">
                                <!-- Warning Icon -->
                                <div class="w-20 h-20 bg-rose-50 rounded-3xl flex items-center justify-center text-rose-500 mb-6 shadow-inner animate-bounce-slow">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>

                                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">Peringatan Penyusutan!</h2>
                                <p class="text-slate-500 font-medium leading-relaxed">
                                    {{ session('warning') }}
                                </p>

                                <div class="mt-10 w-full flex flex-col gap-3">
                                    <button @click="open = false" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-rose-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs">
                                        Saya Mengerti
                                    </button>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Segera periksa kualitas seafood atau timbangan supplier.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

    </main>

    @stack('scripts')
</body>
</html>