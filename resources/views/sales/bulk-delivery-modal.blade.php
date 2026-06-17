<div id="bulkStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            
            <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pembaruan Status Massal</h3>
                </div>
                <button type="button" onclick="closeBulkModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100/60 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs font-semibold text-blue-800 leading-relaxed">
                        Anda telah memilih <span id="modal-selected-count" class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 font-bold mx-0.5">0</span> transaksi. Silakan tentukan status pengiriman baru untuk seluruh transaksi tersebut.
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pilih Status Tujuan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-blue-500 transition-all select-none has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/20">
                            <input type="radio" name="modal_target_status" value="dalam perjalanan" checked class="sr-only">
                            <span class="text-xs font-bold text-slate-700 mb-1">Dalam Perjalanan</span>
                            <span class="text-[11px] text-slate-400 leading-normal">Status kurir membawa muatan ke pembeli.</span>
                        </label>
                        <label class="relative flex flex-col p-4 bg-white border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all select-none has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/20">
                            <input type="radio" name="modal_target_status" value="selesai" class="sr-only">
                            <span class="text-xs font-bold text-slate-700 mb-1">Selesai</span>
                            <span class="text-[11px] text-slate-400 leading-normal">Transaksi rampung tanpa lewat kurir luar.</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeBulkModal()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 transition-all">
                    Batal
                </button>
                <button type="button" id="modal-submit-btn" onclick="submitBulkStatus()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm shadow-blue-100 flex items-center gap-2">
                    <span>Perbarui Status</span>
                </button>
            </div>

        </div>
    </div>
</div>