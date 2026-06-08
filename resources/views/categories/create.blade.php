@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Tambah Barang</h1>
                        <p class="text-slate-500 text-sm font-medium">Masukkan barang baru yang ingin dicatat.</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl">
                        <ul class="list-disc list-inside text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="group_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Kelompok Barang</label>
                            <input type="text" name="group_name" id="group_name" list="group_name_options" value="{{ old('group_name') }}" placeholder="Contoh: Kerang, Udang, Ikan"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                            <datalist id="group_name_options">
                                @foreach($categoryGroups as $groupName)
                                    <option value="{{ $groupName }}"></option>
                                @endforeach
                            </datalist>
                            <p class="text-xs text-slate-400 mt-2 ml-1">Dipakai untuk mengelompokkan barang sejenis, misalnya semua kerang masuk ke kelompok "Kerang".</p>
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Barang</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Kerang Hijau, Kerang Dara, Udang Windu"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-[0.25em]">Pengaturan Harga</p>
                                    <p class="text-sm text-slate-500 font-medium mt-2">Isi harga jual biasa dan harga jual untuk pembelian banyak.</p>
                                </div>
                                <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-blue-50 items-center justify-center text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4 1.343 4 3-1.79 3-4 3m0-12c1.966 0 3.6 1.06 3.949 2.454M12 8V6m0 14v-2m-3.949-2.454C8.4 16.94 10.034 18 12 18"></path></svg>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-5 bg-white rounded-2xl border border-slate-200">
                                    <label for="retail_price" class="block text-xs font-bold text-blue-500 uppercase tracking-widest mb-3">Harga Jual Biasa</label>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                        <input type="number" step="0.01" min="0" name="retail_price" id="retail_price" value="{{ old('retail_price') }}" placeholder="0"
                                            class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-3">Dipakai saat pembeli membeli sedikit.</p>
                                </div>

                                <div class="p-5 bg-white rounded-2xl border border-slate-200">
                                    <label for="wholesale_price" class="block text-xs font-bold text-emerald-500 uppercase tracking-widest mb-3">Harga Jual Banyak</label>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                        <input type="number" step="0.01" min="0" name="wholesale_price" id="wholesale_price" value="{{ old('wholesale_price') }}" placeholder="0"
                                            class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700" required>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-3">Dipakai saat pembeli membeli banyak.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Foto Barang</label>
                            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp"
                                class="w-full px-5 py-4 bg-slate-50 border border-dashed border-slate-300 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-500 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:rounded-xl file:text-blue-600 file:font-bold hover:file:bg-blue-100">
                            <p class="text-xs text-slate-400 mt-2 ml-1">Opsional. Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Barang
                        </button>
                        <a href="{{ route('categories.index') }}" class="flex-1 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 font-extrabold py-5 rounded-2xl transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
