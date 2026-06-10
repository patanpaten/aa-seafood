@props(['partners'])

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="w-10 px-6 py-5"></th>
                    <th class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Restoran / Pembeli</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Alamat</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                {{-- A. LOOPING MITRA RESTORAN RESMI --}}
                @forelse($partners as $partner)
                    <tr class="hover:bg-slate-50/40 transition-colors cursor-pointer" onclick="toggleDeliveryRow({{ $partner->id }})">
                        <td class="px-6 py-6 text-center">
                            <button type="button" id="icon-arrow-{{ $partner->id }}" class="text-slate-400 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </td>
                        <td class="px-6 py-6 font-bold text-slate-700">
                            {{ $partner->name }}
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-md bg-slate-100 text-slate-600">
                                {{ $partner->sales->count() }} Transaksi
                            </span>
                        </td>
                        <td class="px-6 py-6 text-sm font-medium text-slate-500">{{ $partner->contact ?: '-' }}</td>
                        <td class="px-6 py-6 text-sm font-medium text-slate-500">{{ $partner->address ?: '-' }}</td>
                        <td class="px-6 py-6 text-center" onclick="event.stopPropagation();">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    class="open-sale-modal inline-flex items-center justify-center w-11 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-lg shadow-emerald-200 transition-all"
                                    data-partner-id="{{ $partner->id }}"
                                    data-partner-name="{{ $partner->name }}"
                                    title="Input Penjualan"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                                </button>
                                
                                @if(auth()->user()?->isOwner())
                                    <button
                                        type="button"
                                        onclick="openEditPartnerModal({{ $partner->id }}, '{{ addslashes($partner->name) }}', '{{ addslashes($partner->contact) }}', '{{ addslashes($partner->address) }}')"
                                        class="inline-flex items-center justify-center w-11 h-11 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-11 h-11 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.895-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- SUB-TABLE DETAIL RESTORAN --}}
                    <tr id="delivery-row-{{ $partner->id }}" class="hidden bg-slate-50/60">
                        <td colspan="5" class="px-8 py-4 border-t border-b border-slate-100">
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-inner overflow-hidden p-2">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                            <th class="px-4 py-3">Tanggal</th>
                                            <th class="px-4 py-3">Tipe Seafood</th>
                                            <th class="px-4 py-3">Pengantar</th>
                                            <th class="px-4 py-3">Jumlah (Kg)</th>
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3">Bukti</th>
                                            <th class="px-4 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @forelse($partner->sales as $sale)
                                            @include('sales.sale-detail-row')
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-6 text-center text-xs text-slate-400 italic">Belum ada riwayat pembelian seafood untuk restoran ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data restoran.</td>
                    </tr>
                @endforelse

                {{-- B. LOGIKA TAMBAHAN: KHUSUS PEMBELI ECERAN UMUM (PARTNER ID = NULL) --}}
                @php
                    // Mengambil penjualan yang tidak terikat partner manapun (seperti Jekson)
                    $retailSales = \App\Models\Sale::whereNull('partner_id')->get();
                @endphp

                @if($retailSales->count() > 0)
                    {{-- Row Kepala Grup Eceran --}}
                    <tr class="hover:bg-slate-50/40 transition-colors cursor-pointer" onclick="toggleDeliveryRow('retail-group')">
                        <td class="px-6 py-6 text-center">
                            <button type="button" id="icon-arrow-retail-group" class="text-slate-400 transition-transform duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </td>
                        <td class="px-6 py-6 font-bold text-amber-600">
                            Penjualan Eceran (Umum / Walk-in)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-md bg-amber-50 text-amber-700 border border-amber-100">
                                {{ $retailSales->count() }} Transaksi
                            </span>
                        </td>
                        <td class="px-6 py-6 text-sm font-medium text-slate-400">Multi Kontak</td>
                        <td class="px-6 py-6 text-sm font-medium text-slate-400">Luar Toko / Langsung</td>
                        <td class="px-6 py-6 text-center text-xs text-slate-400 italic">-</td>
                    </tr>

                    {{-- SUB-TABLE DETAIL KHUSUS TRANSAKSI ECERAN --}}
                    <tr id="delivery-row-retail-group" class="hidden bg-slate-50/60">
                        <td colspan="5" class="px-8 py-4 border-t border-b border-slate-100">
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-inner overflow-hidden p-2">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                            <th class="px-4 py-3">Tanggal</th>
                                            <th class="px-4 py-3">Nama Pembeli & Jenis</th>
                                            <th class="px-4 py-3">Pengantar</th>
                                            <th class="px-4 py-3">Jumlah (Kg)</th>
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3">Bukti</th>
                                            <th class="px-4 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($retailSales as $sale)
                                            @include('sales.sale-detail-row')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>