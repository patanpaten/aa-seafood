@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Stok Masuk</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Pilih tempat beli terlebih dahulu, lalu input barang masuk lewat pop-up.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="openSupplierModal()" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-sm font-bold shadow-lg shadow-slate-200 transition-all uppercase tracking-wider">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tempat Beli
                </button>
            </div>
        </div>

        <div id="incoming-stock-feedback" class="hidden rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Tempat Beli</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Alamat</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-slate-700">{{ $supplier->name }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $supplier->contact ?: '-' }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $supplier->address ?: '-' }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="open-incoming-stock-modal inline-flex items-center justify-center w-11 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg shadow-blue-200 transition-all"
                                            data-supplier-id="{{ $supplier->id }}"
                                            data-supplier-name="{{ $supplier->name }}"
                                            data-supplier-contact="{{ $supplier->contact }}"
                                            data-supplier-address="{{ $supplier->address }}"
                                            title="Input Stok"
                                            aria-label="Input Stok"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                                        </button>
                                        @if(auth()->user()?->isOwner())
                                            <button
                                                type="button"
                                                onclick="openEditSupplierModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ addslashes($supplier->contact) }}', '{{ addslashes($supplier->address) }}')"
                                                class="inline-flex items-center justify-center w-11 h-11 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all"
                                                title="Edit"
                                                aria-label="Edit"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus tempat beli ini?')">
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
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data tempat beli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="add-supplier-modal" class="hidden fixed inset-0 z-[120]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeSupplierModal()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Tambah Tempat Beli</h2>
                                <p class="text-slate-500 text-sm font-medium">Supplier atau nelayan baru.</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeSupplierModal()" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div id="supplier-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>
                    
                    <form id="add-supplier-form" class="space-y-6">
                        <div class="group">
                            <label for="new_supplier_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Nama Tempat Beli *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <input type="text" id="new_supplier_name" required placeholder="Contoh: Nelayan Pak Budi"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="new_supplier_contact" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Kontak / No HP (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" id="new_supplier_contact" placeholder="Contoh: 0812xxxxxx"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="group">
                            <label for="new_supplier_address" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-slate-900">Alamat (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-slate-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.828a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="text" id="new_supplier_address" placeholder="Contoh: Muara Angke"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="button" onclick="submitSupplier()" id="btn-save-supplier" class="flex-[2] bg-slate-900 hover:bg-slate-800 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-slate-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Supplier
                            </button>
                            <button type="button" onclick="closeSupplierModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="incoming-stock-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" data-close-incoming-stock-modal></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg max-h-[90vh] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto custom-scrollbar transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Input Stok Masuk</h2>
                                <p class="text-slate-500 text-sm font-medium">Catat barang masuk dari supplier.</p>
                            </div>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors" data-close-incoming-stock-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-[2.5rem] border border-slate-100 mb-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-5 relative z-10">Data Supplier Terpilih</p>
                        <div class="grid grid-cols-1 gap-4 relative z-10">
                            <div class="flex items-start gap-4 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Nama Tempat Beli</p>
                                    <p id="incoming-selected-supplier-name" class="text-sm font-extrabold text-slate-700">-</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kontak / No HP</p>
                                    <p id="incoming-selected-supplier-contact" class="text-sm font-extrabold text-slate-700">-</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.828a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Alamat Lengkap</p>
                                    <p id="incoming-selected-supplier-address" class="text-sm font-extrabold text-slate-700 leading-tight">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="incoming-stock-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>

                    <form id="incoming-stock-form" action="{{ route('incoming-stocks.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="supplier_id" id="incoming_supplier_id">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="group">
                                <label for="incoming_date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Tanggal Masuk</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" name="date" id="incoming_date" value="{{ date('Y-m-d') }}"
                                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div class="group">
                                <label for="incoming_category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Nama Barang</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <select name="category_id" id="incoming_category_id"
                                        class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none cursor-pointer" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($groupedCategories as $groupName => $groupCategories)
                                            <optgroup label="{{ $groupName }}">
                                                @foreach($groupCategories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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

                        <div class="group">
                            <label for="incoming_purchase_price_per_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Harga Beli Per Kg</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <span class="text-lg font-black text-slate-300 group-focus-within:text-blue-400 transition-colors">Rp</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="purchase_price_per_kg" id="incoming_purchase_price_per_kg" placeholder="0"
                                    class="w-full pl-16 pr-5 py-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-slate-700 placeholder:text-slate-200" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-6 bg-blue-50/50 rounded-[2.5rem] border border-blue-100/50">
                            <div class="group">
                                <label for="incoming_receipt_weight" class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2 ml-1">Berat Di Nota (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="receipt_weight" id="incoming_receipt_weight" placeholder="0.00"
                                    class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-black text-xl text-blue-600 placeholder:text-blue-200" required>
                            </div>
                            <div class="group">
                                <label for="incoming_actual_weight" class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2 ml-1">Hasil Timbang (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="actual_weight" id="incoming_actual_weight" placeholder="0.00"
                                    class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-black text-xl text-blue-600 placeholder:text-blue-200" required>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                            <div class="flex items-center justify-between relative z-10">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimasi Total Bayar</p>
                                    <p id="incoming-total-purchase-price" class="text-3xl font-black text-white tracking-tight">Rp 0</p>
                                </div>
                                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white/50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <button type="submit" id="incoming-stock-submit-button" class="flex-[2] bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Stok Masuk
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs" data-close-incoming-stock-modal>
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tempat Beli (BARU) -->
    <div id="edit-supplier-modal" class="hidden fixed inset-0 z-[120]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeEditSupplierModal()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-100">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Edit Tempat Beli</h2>
                                <p class="text-slate-500 text-sm font-medium">Ubah data supplier terpilih.</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeEditSupplierModal()" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div id="edit-supplier-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700 animate-pulse"></div>
                    
                    <form id="edit-supplier-form" class="space-y-6">
                        <input type="hidden" id="edit_supplier_id">
                        <div class="group">
                            <label for="edit_supplier_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Nama Tempat Beli *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <input type="text" id="edit_supplier_name" required placeholder="Contoh: Nelayan Pak Budi"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700">
                            </div>
                        </div>
                        <div class="group">
                            <label for="edit_supplier_contact" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Kontak / No HP (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" id="edit_supplier_contact" placeholder="Contoh: 0812xxxxxx"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700">
                            </div>
                        </div>
                        <div class="group">
                            <label for="edit_supplier_address" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-blue-600">Alamat (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.828a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="text" id="edit_supplier_address" placeholder="Contoh: Muara Angke"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="button" onclick="submitEditSupplier()" id="btn-update-supplier" class="flex-[2] bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeEditSupplierModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                                Batal
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
    // FUNGSI UNTUK MODAL EDIT SUPPLIER (BARU)
    function openEditSupplierModal(id, name, contact, address) {
        document.getElementById('edit_supplier_id').value = id;
        document.getElementById('edit_supplier_name').value = name;
        document.getElementById('edit_supplier_contact').value = contact || '';
        document.getElementById('edit_supplier_address').value = address || '';
        document.getElementById('edit-supplier-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditSupplierModal() {
        document.getElementById('edit-supplier-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('edit-supplier-form').reset();
        document.getElementById('edit-supplier-errors').classList.add('hidden');
    }

    async function submitEditSupplier() {
        const id = document.getElementById('edit_supplier_id').value;
        const nameInput = document.getElementById('edit_supplier_name').value.trim();
        const contactInput = document.getElementById('edit_supplier_contact').value.trim();
        const addressInput = document.getElementById('edit_supplier_address').value.trim();
        const errorBox = document.getElementById('edit-supplier-errors');
        const btnUpdate = document.getElementById('btn-update-supplier');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama tempat beli wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnUpdate.disabled = true;
        btnUpdate.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            // Kita hit route update (PUT/PATCH)
            const url = `/suppliers/${id}`;
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

    // FUNGSI UNTUK MODAL TAMBAH SUPPLIER (BARU)
    function openSupplierModal() {
        document.getElementById('add-supplier-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('new_supplier_name').focus();
    }

    function closeSupplierModal() {
        document.getElementById('add-supplier-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('add-supplier-form').reset();
        document.getElementById('supplier-errors').classList.add('hidden');
    }

    async function submitSupplier() {
        const nameInput = document.getElementById('new_supplier_name').value.trim();
        const contactInput = document.getElementById('new_supplier_contact').value.trim();
        const addressInput = document.getElementById('new_supplier_address').value.trim();
        const errorBox = document.getElementById('supplier-errors');
        const btnSave = document.getElementById('btn-save-supplier');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama tempat beli wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnSave.disabled = true;
        btnSave.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            const response = await fetch("{{ route('suppliers.store') }}", {
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
                // Tampilkan error validasi dari laravel
                const errMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menyimpan.');
                errorBox.innerHTML = errMsg;
                errorBox.classList.remove('hidden');
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan Supplier';
                return;
            }

            // Jika berhasil, refresh halaman agar data masuk ke dalam tabel
            // Kita refresh karena UI Anda berbentuk list tabel (bukan dropdown)
            window.location.reload();

        } catch (error) {
            errorBox.innerHTML = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Supplier';
        }
    }


    // FUNGSI UNTUK MODAL STOK MASUK (BAWAAN ANDA)
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('incoming-stock-modal');
        const form = document.getElementById('incoming-stock-form');
        const feedback = document.getElementById('incoming-stock-feedback');
        const errorsBox = document.getElementById('incoming-stock-errors');
        const submitButton = document.getElementById('incoming-stock-submit-button');
        const supplierIdInput = document.getElementById('incoming_supplier_id');
        const selectedName = document.getElementById('incoming-selected-supplier-name');
        const selectedContact = document.getElementById('incoming-selected-supplier-contact');
        const selectedAddress = document.getElementById('incoming-selected-supplier-address');
        const purchasePriceInput = document.getElementById('incoming_purchase_price_per_kg');
        const actualWeightInput = document.getElementById('incoming_actual_weight');
        const totalPurchasePrice = document.getElementById('incoming-total-purchase-price');

        function showFeedback(message, type) {
            feedback.className = 'rounded-2xl border px-5 py-4 text-sm font-semibold mb-6';
            if (type === 'warning') {
                feedback.classList.add('bg-amber-50', 'border-amber-100', 'text-amber-700');
            } else {
                feedback.classList.add('bg-emerald-50', 'border-emerald-100', 'text-emerald-700');
            }
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

        function updateTotalPurchasePrice() {
            const price = parseFloat(purchasePriceInput.value || 0);
            const weight = parseFloat(actualWeightInput.value || 0);
            const total = price * weight;

            totalPurchasePrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        function openModal(button) {
            supplierIdInput.value = button.dataset.supplierId;
            selectedName.textContent = button.dataset.supplierName || '-';
            selectedContact.textContent = button.dataset.supplierContact || '-';
            selectedAddress.textContent = button.dataset.supplierAddress || '-';
            updateTotalPurchasePrice();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            document.getElementById('incoming_date').value = '{{ date('Y-m-d') }}';
            supplierIdInput.value = '';
            selectedName.textContent = '-';
            selectedContact.textContent = '-';
            selectedAddress.textContent = '-';
            totalPurchasePrice.textContent = 'Rp 0';
            resetErrors();
        }

        document.querySelectorAll('.open-incoming-stock-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-incoming-stock-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        purchasePriceInput.addEventListener('input', updateTotalPurchasePrice);
        actualWeightInput.addEventListener('input', updateTotalPurchasePrice);

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
                const successMessage = data.total_purchase_price
                    ? data.message + ' Total belanja: Rp ' + data.total_purchase_price
                    : data.message;
                showFeedback(data.warning || successMessage, data.warning ? 'warning' : 'success');
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Barang Masuk';
            }
        });
    });
</script>
@endpush