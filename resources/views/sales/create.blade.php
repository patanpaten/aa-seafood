@extends('layouts.app')

@section('title', 'Penjualan')

{{-- @section('content')
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Penjualan</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Pilih restoran dari tabel atau pakai tombol penjualan eceran untuk pembeli umum.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="openPartnerModal()" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-sm font-bold shadow-lg shadow-slate-200 transition-all uppercase tracking-wider">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pelanggan
                </button>
                <button
                    type="button"
                    class="open-sale-modal inline-flex items-center justify-center gap-3 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all font-extrabold text-sm uppercase tracking-wider"
                    data-buyer-mode="general"
                    data-buyer-name="Pembeli Umum"
                    title="Input Penjualan Eceran"
                    aria-label="Input Penjualan Eceran"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                    <span>Penjualan Eceran</span>
                </button>
            </div>
        </div>

        <div id="sale-feedback" class="hidden rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Restoran</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Alamat</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-slate-700">{{ $partner->name }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $partner->contact ?: '-' }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $partner->address ?: '-' }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="open-sale-modal inline-flex items-center justify-center w-11 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-lg shadow-emerald-200 transition-all"
                                            data-partner-id="{{ $partner->id }}"
                                            data-partner-name="{{ $partner->name }}"
                                            data-partner-contact="{{ $partner->contact }}"
                                            data-partner-address="{{ $partner->address }}"
                                            title="Input Penjualan"
                                            aria-label="Input Penjualan"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                                        </button>
                                        @if(auth()->user()?->isOwner())
                                            <button
                                                type="button"
                                                onclick="openEditPartnerModal({{ $partner->id }}, '{{ addslashes($partner->name) }}', '{{ addslashes($partner->contact) }}', '{{ addslashes($partner->address) }}')"
                                                class="inline-flex items-center justify-center w-11 h-11 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all"
                                                title="Edit"
                                                aria-label="Edit"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-11 h-11 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all" title="Hapus" aria-label="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data restoran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="sale-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" data-close-sale-modal></div>
        <div class="relative flex min-h-full items-start justify-center p-4 sm:items-center sm:py-8 overflow-y-auto custom-scrollbar">
            <div class="w-full max-w-3xl max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto custom-scrollbar transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Input Penjualan</h2>
                                <p class="text-slate-500 text-sm font-medium">Catat transaksi penjualan baru.</p>
                            </div>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors" data-close-sale-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-4 mb-8">
                        <div id="sale-partner-summary" class="flex-1 p-6 bg-slate-50 rounded-[2.5rem] border border-slate-100 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-5 relative z-10">Data Pelanggan Terpilih</p>
                            <div class="grid grid-cols-1 gap-3 relative z-10">
                                <div class="flex items-start gap-4 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Nama Restoran</p>
                                        <p id="sale-selected-partner-name" class="text-sm font-extrabold text-slate-700">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center px-6 py-4 bg-amber-50 rounded-[2.5rem] border border-amber-100/50">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                                <p id="sale-mode-note" class="text-xs font-bold text-amber-700 uppercase tracking-wider leading-tight">Mode Penjualan Aktif</p>
                            </div>
                        </div>
                    </div>

                    <div id="sale-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>

                    <form id="sale-form" action="{{ route('sales.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="partner_id" id="sale_partner_id">
                        <input type="hidden" name="price_type" id="sale_price_type" value="eceran">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div id="sale-buyer-name-wrapper" class="hidden md:col-span-2 group">
                                <label for="sale_buyer_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-amber-600">Nama Pembeli</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <input type="text" name="buyer_name" id="sale_buyer_name" placeholder="Contoh: Bu Rina"
                                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                                </div>
                            </div>

                            <div class="group">
                                <label for="sale_date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-emerald-600">Tanggal Penjualan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" name="date" id="sale_date" value="{{ date('Y-m-d') }}"
                                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div class="group">
                                <label for="sale_category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-emerald-600">Nama Barang</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <select name="category_id" id="sale_category_id"
                                        class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none cursor-pointer" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($groupedCategories as $groupName => $groupCategories)
                                            <optgroup label="{{ $groupName }}">
                                                @foreach($groupCategories as $category)
                                                    <option value="{{ $category->id }}"
                                                        data-retail-price="{{ $category->retail_price }}"
                                                        data-wholesale-price="{{ $category->wholesale_price }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label for="sale_quantity_sold_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-emerald-600">Jumlah Jual (Kg)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" name="quantity_sold_kg" id="sale_quantity_sold_kg" placeholder="0.00"
                                        class="w-full pl-12 pr-5 py-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-black text-2xl text-slate-700 placeholder:text-slate-200" required>
                                </div>
                            </div>

                            <div class="group">
                                <label for="sale_price_per_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-amber-600">Harga Jual Per Kg</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                        <span class="text-lg font-black text-slate-300 group-focus-within:text-amber-400 transition-colors">Rp</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" name="price_per_kg" id="sale_price_per_kg" placeholder="0"
                                        class="w-full pl-16 pr-5 py-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition font-black text-2xl text-slate-700 placeholder:text-slate-200" required>
                                </div>
                            </div>
                        </div>

                        <div id="sale-price-type-card" class="hidden p-8 bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white/5 rounded-full transition-transform group-hover:scale-150 duration-1000"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 relative z-10 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Harga Acuan Barang</p>
                                        <p id="sale-price-source-label" class="text-base font-extrabold text-white"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10">
                                <div class="p-5 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors group/item">
                                    <div class="flex justify-between items-start mb-4">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Harga Eceran</p>
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                    </div>
                                    <p id="sale-reference-retail" class="text-2xl font-black text-white mb-4">Rp 0</p>
                                    <button type="button" id="sale-apply-retail-price" class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] transition-all transform active:scale-95">
                                        Gunakan Harga
                                    </button>
                                </div>
                                <div class="p-5 rounded-3xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors group/item">
                                    <div class="flex justify-between items-start mb-4">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Harga Grosir</p>
                                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                    </div>
                                    <p id="sale-reference-wholesale" class="text-2xl font-black text-white mb-4">Rp 0</p>
                                    <button type="button" id="sale-apply-wholesale-price" class="w-full py-3 rounded-2xl bg-amber-600 hover:bg-amber-500 text-white text-[10px] font-black uppercase tracking-[0.2em] transition-all transform active:scale-95">
                                        Gunakan Harga
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" id="sale-submit-button" class="flex-[2] bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-emerald-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Penjualan
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs" data-close-sale-modal>
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pelanggan (BARU) -->
    <div id="add-partner-modal" class="hidden fixed inset-0 z-[120]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closePartnerModal()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Tambah Pelanggan</h2>
                                <p class="text-slate-500 text-sm font-medium">Restoran atau mitra baru.</p>
                            </div>
                        </div>
                        <button type="button" onclick="closePartnerModal()" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div id="partner-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>
                    
                    <form id="add-partner-form" class="space-y-6">
                        <div class="group">
                            <label for="new_partner_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Nama Pelanggan Resto *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <input type="text" id="new_partner_name" required placeholder="Contoh: Resto Seafood Sentosa"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="new_partner_contact" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Kontak / No HP (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" id="new_partner_contact" placeholder="Contoh: 0812xxxxxx"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="new_partner_address" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Alamat (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.828a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="text" id="new_partner_address" placeholder="Contoh: Jl. Sudirman No. 12"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="button" onclick="submitPartner()" id="btn-save-partner" class="flex-[2] bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-slate-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Pelanggan
                            </button>
                            <button type="button" onclick="closePartnerModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pelanggan (BARU) -->
    <div id="edit-partner-modal" class="hidden fixed inset-0 z-[120]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeEditPartnerModal()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Edit Pelanggan</h2>
                                <p class="text-slate-500 text-sm font-medium">Ubah data pelanggan terpilih.</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeEditPartnerModal()" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div id="edit-partner-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>
                    
                    <form id="edit-partner-form" class="space-y-6">
                        <input type="hidden" id="edit_partner_id">
                        <div class="group">
                            <label for="edit_partner_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Nama Pelanggan Resto *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <input type="text" id="edit_partner_name" required placeholder="Contoh: Resto Seafood Sentosa"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="edit_partner_contact" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Kontak / No HP (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" id="edit_partner_contact" placeholder="Contoh: 0812xxxxxx"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="edit_partner_address" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Alamat (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.828a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="text" id="edit_partner_address" placeholder="Contoh: Jl. Sudirman No. 12"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="button" onclick="submitEditPartner()" id="btn-update-partner" class="flex-[2] bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeEditPartnerModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@push('scripts')
<script>
    // FUNGSI UNTUK MODAL EDIT PELANGGAN (BARU)
    function openEditPartnerModal(id, name, contact, address) {
        document.getElementById('edit_partner_id').value = id;
        document.getElementById('edit_partner_name').value = name;
        document.getElementById('edit_partner_contact').value = contact || '';
        document.getElementById('edit_partner_address').value = address || '';
        document.getElementById('edit-partner-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditPartnerModal() {
        document.getElementById('edit-partner-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('edit-partner-form').reset();
        document.getElementById('edit-partner-errors').classList.add('hidden');
    }

    async function submitEditPartner() {
        const id = document.getElementById('edit_partner_id').value;
        const nameInput = document.getElementById('edit_partner_name').value.trim();
        const contactInput = document.getElementById('edit_partner_contact').value.trim();
        const addressInput = document.getElementById('edit_partner_address').value.trim();
        const errorBox = document.getElementById('edit-partner-errors');
        const btnUpdate = document.getElementById('btn-update-partner');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama pelanggan wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnUpdate.disabled = true;
        btnUpdate.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            const url = `/partners/${id}`;
            const response = await fetch(url, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ 
                    name: nameInput,
                    contact: contactInput,
                    address: addressInput
                })
            });

            const data = await response.json();

            if (!response.ok) {
                const errMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menyimpan.');
                errorBox.innerHTML = errMsg;
                errorBox.classList.remove('hidden');
                btnUpdate.disabled = false;
                btnUpdate.textContent = 'Simpan Perubahan';
                return;
            }

            window.location.reload();

        } catch (error) {
            errorBox.innerHTML = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
            btnUpdate.disabled = false;
            btnUpdate.textContent = 'Simpan Perubahan';
        }
    }

    // FUNGSI UNTUK MODAL TAMBAH PELANGGAN (AJAX)
    function openPartnerModal() {
        document.getElementById('add-partner-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('new_partner_name').focus();
    }

    function closePartnerModal() {
        document.getElementById('add-partner-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('add-partner-form').reset();
        document.getElementById('partner-errors').classList.add('hidden');
    }

    async function submitPartner() {
        const nameInput = document.getElementById('new_partner_name').value.trim();
        const contactInput = document.getElementById('new_partner_contact').value.trim();
        const addressInput = document.getElementById('new_partner_address').value.trim();
        const errorBox = document.getElementById('partner-errors');
        const btnSave = document.getElementById('btn-save-partner');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama pelanggan wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnSave.disabled = true;
        btnSave.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            const response = await fetch("{{ route('partners.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ 
                    name: nameInput,
                    contact: contactInput,
                    address: addressInput
                })
            });

            const data = await response.json();

            if (!response.ok) {
                const errMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menyimpan.');
                errorBox.innerHTML = errMsg;
                errorBox.classList.remove('hidden');
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan Pelanggan';
                return;
            }

            window.location.reload();

        } catch (error) {
            errorBox.innerHTML = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Pelanggan';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('sale-modal');
        const form = document.getElementById('sale-form');
        const feedback = document.getElementById('sale-feedback');
        const errorsBox = document.getElementById('sale-errors');
        const submitButton = document.getElementById('sale-submit-button');
        const modeNote = document.getElementById('sale-mode-note');
        const partnerSummary = document.getElementById('sale-partner-summary');
        const partnerIdInput = document.getElementById('sale_partner_id');
        const buyerNameWrapper = document.getElementById('sale-buyer-name-wrapper');
        const buyerNameInput = document.getElementById('sale_buyer_name');
        const selectedName = document.getElementById('sale-selected-partner-name');
        const categorySelect = document.getElementById('sale_category_id');
        const priceTypeInput = document.getElementById('sale_price_type');
        const priceInput = document.getElementById('sale_price_per_kg');
        const priceTypeCard = document.getElementById('sale-price-type-card');
        const priceSourceLabel = document.getElementById('sale-price-source-label');
        const referenceRetail = document.getElementById('sale-reference-retail');
        const referenceWholesale = document.getElementById('sale-reference-wholesale');
        const applyRetailPriceButton = document.getElementById('sale-apply-retail-price');
        const applyWholesalePriceButton = document.getElementById('sale-apply-wholesale-price');
        let currentMode = 'partner';
        let currentPartner = {
            id: '',
            name: '-',
            contact: '-',
            address: '-',
        };

        function showFeedback(message) {
            feedback.className = 'rounded-2xl border px-5 py-4 text-sm font-semibold bg-emerald-50 border-emerald-100 text-emerald-700';
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

        function setPartnerSummary(partner) {
            selectedName.textContent = partner.name || '-';
        }

        function syncBuyerMode() {
            const isGeneralBuyer = currentMode === 'general';

            buyerNameWrapper.classList.toggle('hidden', !isGeneralBuyer);
            partnerSummary.classList.toggle('hidden', isGeneralBuyer);
            buyerNameInput.required = isGeneralBuyer;
            partnerIdInput.value = isGeneralBuyer ? '' : currentPartner.id;

            if (isGeneralBuyer) {
                modeNote.textContent = 'Isi nama pembeli untuk penjualan eceran.';
                setPartnerSummary({ name: '-', contact: '-', address: '-' });
            } else {
                modeNote.textContent = 'Pembeli mengikuti restoran yang dipilih.';
                setPartnerSummary(currentPartner);
                buyerNameInput.value = currentPartner.name !== '-' ? currentPartner.name : '';
            }
        }

        function getSelectedDefaultPrice() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const priceType = priceTypeInput.value;

            return priceType === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;
        }

        function setPriceFromReference(type) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const selectedPrice = type === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;

            if (!selectedPrice) {
                return;
            }

            priceTypeInput.value = type;
            priceInput.value = selectedPrice;
            priceInput.dataset.manual = 'false';
            syncDefaultPrice(false, type);
        }

        function openModal(button) {
            currentMode = button.dataset.buyerMode || (button.dataset.partnerId ? 'partner' : 'general');
            currentPartner = {
                id: button.dataset.partnerId || '',
                name: button.dataset.partnerName || '-',
                contact: button.dataset.partnerContact || '-',
                address: button.dataset.partnerAddress || '-',
            };
            buyerNameInput.value = currentMode === 'general'
                ? (button.dataset.buyerName || '')
                : (button.dataset.partnerName || '');
            syncBuyerMode();
            syncDefaultPrice(true);
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            document.getElementById('sale_date').value = '{{ date('Y-m-d') }}';
            currentMode = 'partner';
            currentPartner = {
                id: '',
                name: '-',
                contact: '-',
                address: '-',
            };
            partnerIdInput.value = '';
            priceTypeInput.value = 'eceran';
            setPartnerSummary(currentPartner);
            buyerNameInput.required = false;
            priceTypeCard.classList.add('hidden');
            priceInput.value = '';
            resetErrors();
        }

        function syncDefaultPrice(forceUpdate = false, preferredType = null) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const retailPrice = selectedOption?.dataset?.retailPrice;
            const wholesalePrice = selectedOption?.dataset?.wholesalePrice;

            if (!retailPrice && !wholesalePrice) {
                priceTypeCard.classList.add('hidden');
                referenceRetail.textContent = 'Rp 0';
                referenceWholesale.textContent = 'Rp 0';
                if (forceUpdate) {
                    priceInput.value = '';
                }
                return;
            }

            const formatter = new Intl.NumberFormat('id-ID');
            const typedPrice = parseFloat(priceInput.value || 0);
            const retailValue = parseFloat(retailPrice || 0);
            const wholesaleValue = parseFloat(wholesalePrice || 0);
            let matchedType = preferredType;

            if (!matchedType) {
                if (typedPrice > 0 && Math.abs(typedPrice - wholesaleValue) < 0.0001) {
                    matchedType = 'grosir';
                } else if (typedPrice > 0 && Math.abs(typedPrice - retailValue) < 0.0001) {
                    matchedType = 'eceran';
                } else {
                    matchedType = priceTypeInput.value || 'eceran';
                }
            }

            priceTypeCard.classList.remove('hidden');
            priceSourceLabel.textContent = selectedOption.text
                ? 'Acuan harga untuk ' + selectedOption.text
                : '';
            referenceRetail.textContent = 'Rp ' + formatter.format(parseFloat(retailPrice || 0));
            referenceWholesale.textContent = 'Rp ' + formatter.format(parseFloat(wholesalePrice || 0));

            if (forceUpdate || !priceInput.value || priceInput.dataset.manual !== 'true') {
                priceTypeInput.value = preferredType || 'eceran';
                priceInput.value = (preferredType || 'eceran') === 'grosir'
                    ? (wholesalePrice || retailPrice || '')
                    : (retailPrice || wholesalePrice || '');
                priceInput.dataset.manual = 'false';
            } else {
                priceTypeInput.value = matchedType;
            }
        }

        document.querySelectorAll('.open-sale-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-sale-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        categorySelect.addEventListener('change', syncDefaultPrice);
        priceInput.addEventListener('input', function () {
            this.dataset.manual = 'true';
            syncDefaultPrice();
        });
        applyRetailPriceButton.addEventListener('click', function () {
            setPriceFromReference('eceran');
        });
        applyWholesalePriceButton.addEventListener('click', function () {
            setPriceFromReference('grosir');
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
                showFeedback(data.total ? data.message + ' Total: Rp ' + data.total : data.message);
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Penjualan';
            }
        });
    });
</script>
@endpush
