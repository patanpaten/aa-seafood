<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengiriman - {{ $sale->partner->name ?? $sale->buyer_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <header class="bg-white border-b border-slate-100 py-4 px-6 sticky top-0 z-10 shadow-sm">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <h1 class="font-black text-lg text-slate-900 tracking-tight">Driver Portal</h1>
            <span class="text-xs font-semibold bg-slate-100 px-2.5 py-1 rounded-full text-slate-600">
                ID Nota: #{{ $sale->id }}
            </span>
        </div>
    </header>

    <main class="max-w-md w-full mx-auto p-4 flex-grow">
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-medium shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Detail Tujuan</span>
                
                @if($sale->status === 'sedang diproses')
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-widest">Diproses</span>
                @elseif($sale->status === 'dalam perjalanan')
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-widest">Di Jalan</span>
                @else
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest">Selesai</span>
                @endif
            </div>

            <h2 class="text-xl font-black text-slate-800 mb-1">{{ $sale->partner->name ?? $sale->buyer_name }}</h2>
            <p class="text-sm text-slate-500 font-medium mb-3">{{ $sale->partner->address ?? 'Alamat Umum / Luar Pelanggan' }}</p>
            
            {{-- TOMBOL GOOGLE MAPS BARU --}}
            @php
                $destinationName = $sale->partner->name ?? $sale->buyer_name;
                $destinationAddress = $sale->partner->address ?? '';
                // Satukan nama lokasi & alamat untuk pencarian akurat di Maps, lalu lakukan URL encode
                $mapsQuery = urlencode($destinationName . ' ' . $destinationAddress);
                $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . $mapsQuery;
            @endphp
            
            <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                Buka Rute Maps
            </a>

            <hr class="border-slate-100 my-4">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Seafood</span>
                    <span class="text-base font-bold text-slate-700">{{ $sale->category->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Berat</span>
                    <span class="text-base font-black text-emerald-600">{{ number_format($sale->quantity_sold_kg, 2) }} Kg</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider text-center">Bukti Pengiriman</h3>

            @if($sale->status !== 'selesai')
                <form action="{{ route('delivery.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($sale->delivery_proof)
                        <div class="text-center mb-4">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Terunggah Saat Ini:</span>
                            <img src="{{ asset('storage/delivery_proofs/' . $sale->delivery_proof) }}" class="w-32 h-32 object-cover mx-auto rounded-xl border border-slate-200 shadow-inner mb-2">
                            <p class="text-[11px] text-amber-600 font-medium">Anda bisa mengambil foto ulang di bawah ini jika ingin menggantinya.</p>
                        </div>
                    @endif

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide text-center">Ambil Foto (Nota / Lokasi Toko)</label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <circle cx="12" cy="13" r="3" stroke-width="2"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-600" id="file-label">Klik untuk Kamera / Ambil Foto</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG (Maks 5MB)</p>
                                </div>
                                <input type="file" name="delivery_proof" id="delivery_proof" class="hidden" accept="image/*" capture="environment" onchange="previewName()" />
                            </label>
                        </div>
                        @error('delivery_proof')
                            <p class="text-rose-500 text-xs mt-2 font-medium text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all text-center block text-sm tracking-wide">
                        📤 UNGGAH BUKTI FOTO
                    </button>
                    
                    <p class="text-[11px] text-slate-400 text-center mt-3 leading-relaxed">
                        Setelah foto diunggah, mohon infokan ke Owner agar status transaksi dapat diselesaikan di sistem.
                    </p>
                </form>
            @else
                <div class="text-center py-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h4 class="font-black text-slate-800 text-base">Transaksi Selesai & Dikonfirmasi</h4>
                    <p class="text-xs text-slate-400 mt-1 mb-4">Owner telah memverifikasi pengiriman ini.</p>
                    
                    @if($sale->delivery_proof)
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Bukti Pengiriman Dokumen:</span>
                            <img src="{{ asset('storage/delivery_proofs/' . $sale->delivery_proof) }}" class="w-48 h-48 object-cover mx-auto rounded-xl border border-slate-200 shadow-inner">
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <footer class="py-4 text-center text-[10px] font-medium text-slate-400 tracking-wide">
        &copy; {{ date('Y') }} Fish Management System. All Rights Reserved.
    </footer>

    <script>
        function previewName() {
            const input = document.getElementById('delivery_proof');
            const label = document.getElementById('file-label');
            if(input.files && input.files.length > 0) {
                label.innerText = "📸 Foto Terpilih: " + input.files[0].name;
                label.classList.remove('text-slate-600');
                label.classList.add('text-emerald-600');
            }
        }
    </script>
</body>
</html>