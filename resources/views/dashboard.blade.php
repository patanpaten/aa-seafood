@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
    <header class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Executive Dashboard</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Ringkasan performa bisnis Anda bulan ini.</p>
            </div>
            <div class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-3"></div>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </header>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Stok Masuk</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($thisMonthIncoming, 1) }} <span class="text-sm font-bold text-slate-400">kg</span></h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pendapatan</p>
            <h3 class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Penyusutan</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($thisMonthShrinkage, 1) }} <span class="text-sm font-bold text-slate-400">kg</span></h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Stok</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($currentStock, 1) }} <span class="text-sm font-bold text-slate-400">kg</span></h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-8">Trend Penyusutan (6 Bln)</h3>
            <div class="h-72">
                <canvas id="shrinkageTrendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-8">Produk Terlaris</h3>
            <div class="h-72 flex items-center justify-center">
                <canvas id="bestSellingChart"></canvas>
            </div>
        </div>
    </div>

    @if(auth()->user()->isOwner())
    <!-- Management Hub Section -->
    <div class="bg-slate-900 p-10 rounded-[3rem] shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-10">
                <div class="w-1 h-8 bg-blue-500 rounded-full"></div>
                <h2 class="text-2xl font-extrabold text-white uppercase tracking-tight">Management Hub</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('suppliers.index') }}" class="group bg-white/5 hover:bg-white/10 p-6 rounded-3xl border border-white/10 transition-all">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-sm uppercase">Supplier</h4>
                    <p class="text-xs text-slate-400 mt-1">Daftar pemasok utama.</p>
                </a>

                <a href="{{ route('partners.index') }}" class="group bg-white/5 hover:bg-white/10 p-6 rounded-3xl border border-white/10 transition-all">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-sm uppercase">Partner</h4>
                    <p class="text-xs text-slate-400 mt-1">Daftar restoran partner.</p>
                </a>

                <a href="{{ route('categories.index') }}" class="group bg-white/5 hover:bg-white/10 p-6 rounded-3xl border border-white/10 transition-all">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-sm uppercase">Kategori</h4>
                    <p class="text-xs text-slate-400 mt-1">Jenis kategori produk.</p>
                </a>

                <a href="{{ route('stock-adjustments.index') }}" class="group bg-blue-600 p-6 rounded-3xl transition-all hover:bg-blue-700 shadow-lg shadow-blue-900/20">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-sm uppercase">Stock Opname</h4>
                    <p class="text-xs text-blue-100 mt-1">Sesuaikan stok fisik gudang.</p>
                </a>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    // Config Chart.js
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // Data for Shrinkage Trend
    const shrinkageLabels = @json($dripLossTrend->pluck('month_year'));
    const shrinkageData = @json($dripLossTrend->pluck('total_shrinkage'));

    const ctxShrinkage = document.getElementById('shrinkageTrendChart').getContext('2d');
    new Chart(ctxShrinkage, {
        type: 'line',
        data: {
            labels: shrinkageLabels,
            datasets: [{
                label: 'Kg',
                data: shrinkageData,
                borderColor: '#3b82f6',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.1)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                    return gradient;
                },
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9', borderDash: [5, 5] },
                    ticks: { padding: 10 }
                },
                x: { 
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            }
        }
    });

    // Data for Best Selling
    const bestSellingLabels = @json($bestSelling->pluck('category_name'));
    const bestSellingData = @json($bestSelling->pluck('total_qty'));

    const ctxBestSelling = document.getElementById('bestSellingChart').getContext('2d');
    new Chart(ctxBestSelling, {
        type: 'doughnut',
        data: {
            labels: bestSellingLabels,
            datasets: [{
                data: bestSellingData,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6'],
                borderWidth: 8,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 25,
                        font: { size: 12, weight: '600' }
                    }
                }
            }
        }
    });
</script>
@endpush