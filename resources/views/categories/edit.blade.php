@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Edit Kategori</h1>
                        <p class="text-slate-500 text-sm font-medium">Perbarui informasi kategori seafood.</p>
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

                <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="group_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Grup Kategori</label>
                            <input type="text" name="group_name" id="group_name" list="group_name_options" value="{{ old('group_name', $category->group_name) }}" placeholder="Contoh: Kerang, Udang, Ikan"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                            <datalist id="group_name_options">
                                @foreach($categoryGroups as $groupName)
                                    <option value="{{ $groupName }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Item Seafood</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" placeholder="Masukkan nama item"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="retail_price" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Eceran</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                    <input type="number" step="0.01" min="0" name="retail_price" id="retail_price" value="{{ old('retail_price', $category->retail_price ?? $category->price) }}" placeholder="0"
                                        class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div>
                                <label for="wholesale_price" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Grosir</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                    <input type="number" step="0.01" min="0" name="wholesale_price" id="wholesale_price" value="{{ old('wholesale_price', $category->wholesale_price ?? $category->price) }}" placeholder="0"
                                        class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Gambar Item</label>
                                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp"
                                    class="w-full px-5 py-4 bg-slate-50 border border-dashed border-slate-300 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-500 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:rounded-xl file:text-blue-600 file:font-bold hover:file:bg-blue-100">
                                <p class="text-xs text-slate-400 mt-2 ml-1">Opsional. Upload baru jika ingin mengganti gambar lama.</p>
                            </div>

                            @if($category->image_url)
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200">
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Gambar Saat Ini</p>
                                        <p class="text-sm font-semibold text-slate-600">{{ $category->name }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Update Kategori
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
