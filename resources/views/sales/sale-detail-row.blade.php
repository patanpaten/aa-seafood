@php
    // 1. Ambil nomor HP mentah dari database
    $rawPhone = $sale->driver_phone ?? '';
    
    // 2. Bersihkan karakter non-angka seperti spasi, tanda minus (-), atau tanda plus (+)
    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
    
    // 3. Format nomor ke standar Internasional (Awalan 62)
    if (!empty($cleanPhone)) {
        // Jika nomor diawali angka '0', ganti menjadi '62'
        if (strpos($cleanPhone, '0') === 0) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        // Jika nomor diawali dengan '8' langsung, tambahkan '62' di depannya
        elseif (strpos($cleanPhone, '8') === 0) {
            $cleanPhone = '62' . $cleanPhone;
        }
    }

    // Menyiapkan parameter pesan WhatsApp
    $namaToko = urlencode($partner->name ?? $sale->buyer_name);
    $barang = urlencode($sale->category->name ?? '-');
    $jumlah = urlencode(number_format($sale->quantity_sold_kg, 2) . ' Kg');
    
    // Generate link input bukti pengiriman untuk kurir
    $linkBukti = route('delivery.input', $sale->id);
    
    // Template pesan otomatis untuk kurir/pengantar
    $pesan = "Halo, " . ($sale->driver_name ?? 'Pengantar') . ".\n\n" .
             "Berikut tugas pengantaran pesanan:\n" .
             "- Toko: " . ($partner->name ?? $sale->buyer_name) . "\n" .
             "- Barang: " . ($sale->category->name ?? '-') . " (" . number_format($sale->quantity_sold_kg, 2) . " Kg)\n\n" .
             "Jika barang sudah sampai, mohon upload bukti pengiriman melalui tautan berikut ya:\n" .
             $linkBukti;
             
    // Gunakan $cleanPhone yang sudah dipastikan berformat internasional
    $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($pesan);
@endphp

<tr class="hover:bg-slate-50/50 transition-colors text-slate-600">
    {{-- 1. Kolom Tanggal Nota --}}
    <td class="px-4 py-3.5 font-medium">{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
    
    {{-- 2. Kolom Tipe Seafood (Murni nama barang saja) --}}
    <td class="px-4 py-3.5 font-bold text-slate-800">{{ $sale->category->name ?? '-' }}</td>
    
    {{-- 3. Kolom Pengantar / Kurir (Menjadi Satu Kolom Sendiri) --}}
    <td class="px-4 py-3.5 text-slate-700">
        @if($sale->driver_name)
            <span class="font-bold text-slate-800">{{ $sale->driver_name }}</span>
            @if($sale->driver_phone)
                <div class="text-[11px] text-slate-400 font-normal tracking-wide mt-0.5">
                    {{ $sale->driver_phone }}
                </div>
            @endif
        @else
            <span class="text-xs text-slate-400 italic font-normal">Belum diatur</span>
        @endif
    </td>

    {{-- 4. Kolom Quantity --}}
    <td class="px-4 py-3.5 font-black text-emerald-600">{{ number_format($sale->quantity_sold_kg, 2) }} Kg</td>
    
    {{-- 5. Kolom Status --}}
    <td class="px-4 py-3.5">
        @if($sale->status === 'sedang diproses')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-black bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Diproses
            </span>
        @elseif($sale->status === 'dalam perjalanan')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span> Di Jalan
            </span>
        @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                Selesai
            </span>
        @endif
    </td>

    {{-- 6. Kolom Bukti Pengiriman --}}
    <td class="px-4 py-3.5">
        @if($sale->delivery_proof)
            <a href="{{ asset('storage/delivery_proofs/' . $sale->delivery_proof) }}" target="_blank" class="inline-block relative group">
                <img src="{{ asset('storage/delivery_proofs/' . $sale->delivery_proof) }}" class="w-8 h-8 rounded-lg object-cover border border-slate-200 shadow-sm">
            </a>
        @else
            <span class="text-xs text-slate-400 italic">Tidak ada</span>
        @endif
    </td>

    {{-- 7. Kolom Aksi / Tombol Kontrol Owner --}}
    {{-- 7. Kolom Aksi / Tombol Kontrol Owner --}}
<td class="px-4 py-3.5 text-center">
    <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation();">
        
        {{-- TOMBOL EDIT & DELETE HANYA MUNCUL JIKA STATUS 'SEDANG DIPROSES' --}}
        @if($sale->status === 'sedang diproses')
            {{-- Tombol Edit Data --}}
            <button type="button" 
                    onclick="openEditSaleModal(
                        {{ $sale->id }}, 
                        '{{ addslashes($sale->driver_name) }}', 
                        '{{ $sale->driver_phone }}', 
                        '{{ $sale->date }}', 
                        {{ $sale->category_id ?? 'null' }}, 
                        {{ $sale->quantity_sold_kg }}, 
                        {{ $sale->price_per_kg }}
                    )"
                    class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                    title="Edit Data Penjualan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </button>

            {{-- Tombol Hapus Data Kustom --}}
<button type="button" 
        onclick="triggerDeleteModal({{ $sale->id }}, '{{ addslashes($partner->name ?? $sale->buyer_name) }}')" 
        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" 
        title="Hapus Penjualan">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
    </svg>
</button>
        @endif

        {{-- Tombol Kirim WA Notifikasi Kurir --}}
        @if($sale->driver_phone)
            <a href="{{ $waUrl }}" target="_blank" 
               class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-md shadow-emerald-100 transition-all"
               title="Kirim ke WhatsApp Pengantar">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.457L0 24zm6.59-4.846c1.66.986 3.288 1.488 5.358 1.489 5.494 0 9.961-4.47 9.965-9.964.002-2.662-1.033-5.165-2.907-7.04C17.189 1.765 14.7 1.724 12.012 1.724c-5.501 0-9.969 4.471-9.973 9.965-.001 2.073.511 3.712 1.516 5.348L2.53 21.492l4.117-1.338z"/>
                </svg>
            </a>
        @endif

        {{-- FORM PERUBAHAN STATUS LANGSUNG OLEH OWNER --}}
        @if($sale->status === 'sedang diproses')
            <form action="{{ route('sales.update-status', $sale->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="dalam perjalanan">
                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-[10px] uppercase tracking-widest transition-all">
                    Kirim Barang
                </button>
            </form>
        @elseif($sale->status === 'dalam perjalanan')
            <form action="{{ route('sales.update-status', $sale->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="selesai">
                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-[10px] uppercase tracking-widest transition-all">
                    Selesai
                </button>
            </form>
        @else
            <span class="text-emerald-500">
                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
        @endif
    </div>
</td>
</tr>



<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 transition-all">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-xl border border-slate-100 p-6 transform transition-all scale-95 duration-200">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600 mb-4">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <div class="text-center mb-6">
            <h3 class="text-lg font-black text-slate-800 tracking-tight">Hapus Transaksi Penjualan?</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Tindakan ini tidak bisa dibatalkan. Data penjualan untuk pembeli <span id="deleteModalBuyer" class="font-bold text-slate-700"></span> akan dihapus permanen dan stok fisik barang akan otomatis dikembalikan.
            </p>
        </div>
        
        <form id="deleteSaleForm" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
            
            <button type="button" onclick="closeDeleteModal()" 
                    class="w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-red-100">
                Ya, Hapus Data
            </button>
        </form>
    </div>
</div>