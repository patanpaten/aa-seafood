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