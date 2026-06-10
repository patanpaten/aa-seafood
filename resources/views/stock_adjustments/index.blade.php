@extends('layouts.app')

@section('title', 'Cek Stok')

@section('content')
    <div class="mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Cek Stok</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Lihat dulu stok yang tersedia di gudang, lalu pilih barang yang mau diedit atau dicek ulang.</p>
        </div>
    </div>

    <div id="stock-adjustment-feedback" class="hidden mb-8 rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

    <!-- Filter Card -->
    <form method="GET" action="{{ route('stock-adjustments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-10 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
        <div>
            <label for="start_date" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 text-sm font-bold text-slate-700 outline-none transition">
        </div>
        
        <div>
            <label for="end_date" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 text-sm font-bold text-slate-700 outline-none transition">
        </div>
    
        <div>
            <label for="supplier_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tempat Beli</label>
            <select name="supplier_id" id="supplier_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 text-sm font-bold text-slate-700 outline-none transition appearance-none cursor-pointer">
                <option value="">Semua Tempat</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div>
            <label for="category_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Barang</label>
            <select name="category_id" id="category_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 text-sm font-bold text-slate-700 outline-none transition appearance-none cursor-pointer">
                <option value="">Semua Barang</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-95 uppercase text-[10px] tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Filter Data
            </button>
        </div>
    </form>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Stok Yang Tersedia Di Gudang</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Siap Dicek</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal & Waktu</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tempat Beli</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Barang Masuk</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Sisa Stok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Total Akumulasi</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($incomingStocks as $categoryId => $stocks)
                        @php
                            $category = $categories->get($categoryId);
                            $categoryGlobal = $categoriesWithSum->get($categoryId);
                            $rollingBalance = (float) ($initialBalances[$categoryId] ?? 0); // Mulai dari saldo sebelum filter
                            
                            $globalSisa = ($categoryGlobal->incoming_stocks_sum_actual_weight ?? 0) 
                                        - ($categoryGlobal->sales_sum_quantity_sold_kg ?? 0) 
                                        + ($categoryGlobal->adjustments_sum_difference ?? 0);
                        @endphp
                        
                        <!-- Header Kategori -->
                        <tr class="bg-slate-50/30">
                            <td colspan="8" class="px-8 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-blue-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest">
                                            {{ $category->display_group_name }}
                                        </span>
                                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $category->name }}</h4>
                                        <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-100 italic">Saldo Awal: {{ number_format($rollingBalance, 2) }} kg</span>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <div class="flex flex-col items-end">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Sisa Stok Gudang</span>
                                                <div class="group relative cursor-help">
                                                    <svg class="w-3 h-3 text-slate-300 hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <!-- Tooltip Rincian -->
                                                    <div class="absolute bottom-full right-0 mb-2 w-48 hidden group-hover:block z-20">
                                                        <div class="bg-slate-900 text-white text-[10px] p-3 rounded-xl shadow-xl border border-white/10">
                                                            <p class="font-bold mb-2 border-b border-white/10 pb-1 uppercase tracking-wider text-blue-400">Rumus Perhitungan</p>
                                                            <div class="space-y-1.5 font-medium">
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">Total Masuk:</span>
                                                                    <span>{{ number_format($categoryGlobal->incoming_stocks_sum_actual_weight ?? 0, 2) }} kg</span>
                                                                </div>
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">Total Jual:</span>
                                                                    <span>-{{ number_format($categoryGlobal->sales_sum_quantity_sold_kg ?? 0, 2) }} kg</span>
                                                                </div>
                                                                @if(($categoryGlobal->adjustments_sum_difference ?? 0) != 0)
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">Penyesuaian:</span>
                                                                    <span class="{{ $categoryGlobal->adjustments_sum_difference > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                                        {{ $categoryGlobal->adjustments_sum_difference > 0 ? '+' : '' }}{{ number_format($categoryGlobal->adjustments_sum_difference, 2) }} kg
                                                                    </span>
                                                                </div>
                                                                @endif
                                                                <div class="pt-1 mt-1 border-t border-white/10 flex justify-between font-black text-blue-400">
                                                                    <span>SISA AKHIR:</span>
                                                                    <span>{{ number_format($globalSisa, 2) }} kg</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="w-2 h-2 bg-slate-900 rotate-45 absolute -bottom-1 right-2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="hidden sm:flex items-center gap-1.5 px-2 py-0.5 bg-slate-100 rounded-lg border border-slate-200">
                                                    <span class="text-[9px] font-bold text-slate-500">{{ number_format($categoryGlobal->incoming_stocks_sum_actual_weight ?? 0, 1) }} (M)</span>
                                                    <span class="text-[9px] font-bold text-slate-300">|</span>
                                                    <span class="text-[9px] font-bold text-slate-500">{{ number_format($categoryGlobal->sales_sum_quantity_sold_kg ?? 0, 1) }} (J)</span>
                                                </div>
                                                <span class="text-sm font-black text-blue-600">{{ number_format($globalSisa, 2) }} kg</span>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            class="open-stock-adjustment-modal w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-orange-500 rounded-xl hover:bg-orange-50 hover:border-orange-200 transition-all shadow-sm"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}"
                                            data-category-group="{{ $category->display_group_name }}"
                                            data-current-stock="{{ number_format($globalSisa, 2, '.', '') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris Detail Stok Masuk -->
                        @foreach($stocks as $incoming)
                            @php
                                $prevBalance = $rollingBalance;
                                $rollingBalance += $incoming->actual_weight;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-12 py-4">
                                    <span class="text-xs font-medium text-slate-500">{{ $incoming->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-xs font-bold text-slate-600">{{ $incoming->supplier->name ?? '-' }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-widest">
                                        {{ $incoming->category->display_group_name }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-xs font-bold text-slate-600">{{ $incoming->category->name }}</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-sm font-bold text-slate-600">+ {{ number_format($incoming->actual_weight, 2) }} kg</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-sm font-bold text-blue-500">{{ number_format($prevBalance, 2) }} kg</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-sm font-black text-emerald-600">{{ number_format($rollingBalance, 2) }} kg</span>
                                </td>
                                <td class="px-8 py-4"></td>
                            </tr>
                        @endforeach
                        
                        <!-- Spacer row -->
                        <tr class="h-4"></tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data stok masuk di gudang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Riwayat Cek Stok</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Riwayat Perubahan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Tercatat</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Hasil Hitung</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Selisih</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Catatan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($adjustments as $adj)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                                {{ $adj->category->display_group_name }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $adj->category->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->previous_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->actual_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black {{ $adj->difference >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $adj->difference > 0 ? '+' : '' }}{{ number_format($adj->difference, 2) }} kg
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-400 italic">"{{ $adj->reason }}"</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->user->name }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H4a2 2 0 00-2 2v7m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0l-2-2m-2-2l-2-2m-2-2l-2-2m-2-2L4 13"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium italic">Belum ada riwayat cek stok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>

    <div id="stock-adjustment-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-stock-adjustment-modal></div>
        <div class="relative flex min-h-full items-start justify-center p-4 sm:items-center sm:py-8 overflow-y-auto">
            <div class="w-full max-w-2xl max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Edit Stok</h2>
                            <p class="text-slate-500 text-sm font-medium mt-2">Isi stok hasil hitung dan catatan perubahan.</p>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center" data-close-stock-adjustment-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Barang</p>
                                <p id="stock-adjustment-category-name" class="text-base font-extrabold text-slate-700">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kelompok</p>
                                <p id="stock-adjustment-category-group" class="text-sm font-semibold text-slate-600">-</p>
                            </div>
                        </div>
                    </div>

                    <div id="stock-adjustment-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700"></div>

                    <form id="stock-adjustment-form" action="{{ route('stock-adjustments.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="category_id" id="stock_adjustment_category_id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stok Yang Tercatat</label>
                                <div class="flex items-baseline">
                                    <span id="stock-adjustment-current-stock" class="text-3xl font-black text-slate-700">0.00</span>
                                    <span class="ml-2 text-sm font-bold text-slate-400 uppercase">kg</span>
                                </div>
                            </div>
                            <div class="p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                                <label for="stock_adjustment_actual_stock" class="block text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-2 ml-1">Stok Hasil Hitung</label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" name="actual_stock" id="stock_adjustment_actual_stock" placeholder="0.00"
                                        class="w-full bg-transparent text-3xl font-black text-orange-600 outline-none placeholder-orange-200" required>
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-sm font-bold text-orange-300 uppercase">kg</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="stock_adjustment_reason" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Catatan</label>
                            <textarea name="reason" id="stock_adjustment_reason" rows="3" placeholder="Contoh: barang rusak, salah hitung, atau alasan lain"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-semibold text-slate-700" required></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="stock-adjustment-submit-button" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-orange-200 transition-all uppercase tracking-widest text-sm">
                                Simpan Hasil Cek
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-sm" data-close-stock-adjustment-modal>
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('stock-adjustment-modal');
        const form = document.getElementById('stock-adjustment-form');
        const feedback = document.getElementById('stock-adjustment-feedback');
        const errorsBox = document.getElementById('stock-adjustment-errors');
        const submitButton = document.getElementById('stock-adjustment-submit-button');
        const categoryIdInput = document.getElementById('stock_adjustment_category_id');
        const categoryName = document.getElementById('stock-adjustment-category-name');
        const categoryGroup = document.getElementById('stock-adjustment-category-group');
        const currentStock = document.getElementById('stock-adjustment-current-stock');
        const actualStockInput = document.getElementById('stock_adjustment_actual_stock');
        const reasonInput = document.getElementById('stock_adjustment_reason');

        function showFeedback(message) {
            feedback.className = 'mb-8 rounded-2xl border px-5 py-4 text-sm font-semibold bg-emerald-50 border-emerald-100 text-emerald-700';
            feedback.textContent = message;
            feedback.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showErrors(errors) {
            const items = Object.values(errors).flat();
            errorsBox.innerHTML = '<ul class="list-disc list-inside space-y-1">' + items.map(item => '<li>' + item + '</li>').join('') + '</ul>';
            errorsBox.classList.remove('hidden');
        }

        function resetErrors() {
            errorsBox.classList.add('hidden');
            errorsBox.innerHTML = '';
        }

        function openModal(button) {
            categoryIdInput.value = button.dataset.categoryId || '';
            categoryName.textContent = button.dataset.categoryName || '-';
            categoryGroup.textContent = button.dataset.categoryGroup || '-';
            currentStock.textContent = parseFloat(button.dataset.currentStock || 0).toFixed(2);
            actualStockInput.value = parseFloat(button.dataset.currentStock || 0).toFixed(2);
            reasonInput.value = '';
            resetErrors();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            categoryIdInput.value = '';
            categoryName.textContent = '-';
            categoryGroup.textContent = '-';
            currentStock.textContent = '0.00';
            resetErrors();
        }

        document.querySelectorAll('.open-stock-adjustment-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-stock-adjustment-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            resetErrors();
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        showErrors(data.errors);
                    } else {
                        showErrors({ general: [data.message || 'Terjadi kesalahan saat menyimpan data.'] });
                    }
                    return;
                }

                closeModal();
                showFeedback(data.message + ' Selisih: ' + data.difference + ' kg.');
                window.setTimeout(() => window.location.reload(), 900);
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Hasil Cek';
            }
        });
    });
</script>
@endpush
