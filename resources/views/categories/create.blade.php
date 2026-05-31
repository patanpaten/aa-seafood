@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Tambah Kategori</h1>
                        <p class="text-slate-500 text-sm font-medium">Buat kategori seafood baru.</p>
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
                            <label for="group_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Grup Kategori</label>
                            <input type="text" name="group_name" id="group_name" list="group_name_options" value="{{ old('group_name') }}" placeholder="Contoh: Kerang, Udang, Ikan"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                            <datalist id="group_name_options">
                                @foreach($categoryGroups as $groupName)
                                    <option value="{{ $groupName }}"></option>
                                @endforeach
                            </datalist>
                            <p class="text-xs text-slate-400 mt-2 ml-1">Dipakai untuk pengelompokan dropdown, misalnya semua item kerang masuk ke grup "Kerang".</p>
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Item Seafood</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Kerang Hijau, Kerang Dara, Udang Windu"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="retail_price" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Eceran</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                    <input type="number" step="0.01" min="0" name="retail_price" id="retail_price" value="{{ old('retail_price') }}" placeholder="0"
                                        class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div>
                                <label for="wholesale_price" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Grosir</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                    <input type="number" step="0.01" min="0" name="wholesale_price" id="wholesale_price" value="{{ old('wholesale_price') }}" placeholder="0"
                                        class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Gambar Item</label>
                            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp"
                                class="w-full px-5 py-4 bg-slate-50 border border-dashed border-slate-300 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-500 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:rounded-xl file:text-blue-600 file:font-bold hover:file:bg-blue-100">
                            <p class="text-xs text-slate-400 mt-2 ml-1">Opsional. Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Kategori
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
