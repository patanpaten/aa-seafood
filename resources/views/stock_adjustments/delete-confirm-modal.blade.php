<div id="delete-confirmation-modal" class="hidden fixed inset-0 z-[120]">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="close-delete-modal-backdrop"></div>
    
    <div class="relative flex min-h-full items-center justify-center p-4 text-center">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-[2.5rem] bg-white p-8 text-left shadow-2xl border border-slate-100 transition-all">
            
            <div class="flex flex-col items-center text-center mt-2 mb-6">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 border border-rose-100 mb-4">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 uppercase tracking-tight">Hapus Transaksi?</h3>
                <p class="text-sm font-medium text-slate-500 mt-2">Apakah Anda yakin ingin menghapus data transaksi masuk ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" id="confirm-delete-btn" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-rose-100 transition-all uppercase tracking-widest text-xs transform active:scale-95">
                    Ya, Hapus Data
                </button>
                <button type="button" id="cancel-delete-btn" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-4 rounded-2xl transition-all uppercase tracking-widest text-xs">
                    Batalkan
                </button>
            </div>
        </div>
    </div>
</div>