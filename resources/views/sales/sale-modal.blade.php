@props(['groupedCategories'])

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
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-5 relative z-10">Data Pelanggan Terpilih</p>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-dashed border-slate-200 pt-6">
                        <div class="group">
                            <label for="driver_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-blue-600">Nama Pengantar / Sopir</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input type="text" name="driver_name" id="driver_name" placeholder="Contoh: Pak Joko"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                            </div>
                        </div>

                        <div class="group">
                            <label for="driver_phone" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-emerald-600">No. WhatsApp Pengantar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" name="driver_phone" id="driver_phone" placeholder="Contoh: 628123456789"
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                                <p class="text-[10px] text-slate-400 mt-1 ml-1">*Gunakan format kode negara (misal: 62812...)</p>
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