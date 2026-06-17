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
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-extrabold text-slate-700 uppercase tracking-widest">Daftar Barang</h3>
                            <button type="button" onclick="addSaleItem()" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Barang
                            </button>
                        </div>
                        <div id="sale-items-container" class="space-y-4"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-dashed border-slate-200 pt-6">
    <div class="group">
        <label for="driver_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-blue-600">Nama Pengantar / Sopir</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <select name="driver_name" id="driver_name" 
                class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none">
                <option value="">-- Pilih Kurir / Sopir --</option>
                <option value="Fatan" data-phone="6283819442738">Fatan</option>
                <option value="Venta" data-phone="62882008021544">Venta</option>
                <option value="Gyan" data-phone="6287875772919">Gyan</option>
                <option value="Eki" data-phone="6282135664668">Eki</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <div class="group">
        <label for="driver_phone" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 transition-colors group-focus-within:text-emerald-600">No. WhatsApp Pengantar</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            </div>
            <input type="text" name="driver_phone" id="driver_phone" placeholder="Pilih sopir untuk mengisi nomor" readonly
                class="w-full pl-12 pr-5 py-4 bg-slate-100 border border-slate-200 rounded-2xl outline-none transition font-semibold text-slate-500 cursor-not-allowed">
            <p class="text-[10px] text-slate-400 mt-1 ml-1">*Nomor terisi otomatis menggunakan format kode negara 62.</p>
        </div>
    </div>
</div>
                    <div class="p-6 bg-emerald-600 rounded-[2.5rem] shadow-xl shadow-emerald-200 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest mb-1">Estimasi Total Penjualan</p>
                                <p id="sale-total-price" class="text-3xl font-black text-white tracking-tight">Rp 0</p>
                            </div>
                            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white/50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
