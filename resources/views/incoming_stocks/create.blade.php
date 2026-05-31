@extends('layouts.app')

@section('title', 'Catat Stok Masuk')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Stok Masuk</h1>
                        <p class="text-slate-500 text-sm font-medium">Input penerimaan seafood dari supplier.</p>
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

                <form action="{{ route('incoming-stocks.create') }}" method="GET" class="mb-8">
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4 items-end">
                            <div>
                                <label for="group" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Filter Grup Kategori</label>
                                <select name="group" id="group" class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none">
                                    <option value="">Semua Grup</option>
                                    @foreach($categoryGroups as $groupName)
                                        <option value="{{ $groupName }}" @selected($selectedGroup === $groupName)>{{ $groupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-6 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-extrabold uppercase tracking-widest text-xs transition-all">
                                Terapkan
                            </button>
                            <a href="{{ route('incoming-stocks.create') }}" class="px-6 py-4 bg-white hover:bg-slate-100 text-slate-500 rounded-2xl font-extrabold uppercase tracking-widest text-xs transition-all text-center border border-slate-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <form action="{{ route('incoming-stocks.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Masuk</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div>
                            <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jenis Seafood</label>
                            <select name="category_id" id="category_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih Jenis --</option>
                                @forelse($groupedCategories as $groupName => $groupCategories)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($groupCategories as $category)
                                            <option value="{{ $category->id }}"
                                                data-group="{{ $groupName }}"
                                                data-price="{{ $category->price }}"
                                                data-image="{{ $category->image_url }}"
                                                @selected(old('category_id') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @empty
                                    <option value="" disabled>Tidak ada item untuk grup ini</option>
                                @endforelse
                            </select>
                            <p class="text-xs text-slate-400 mt-2 ml-1">Dropdown sudah dikelompokkan per grup agar item lebih cepat ditemukan.</p>
                        </div>
                    </div>

                    <div>
                        <label for="supplier_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Supplier</label>
                        <select name="supplier_id" id="supplier_id" 
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="category-info-card" class="hidden p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div id="category-image-wrapper" class="hidden">
                                <img id="category-image-preview" src="" alt="Preview item" class="w-20 h-20 rounded-2xl object-cover border border-slate-200">
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Info Item Terpilih</p>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span id="category-group-badge" class="inline-flex items-center px-3 py-1 bg-white rounded-xl border border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-widest"></span>
                                    <span id="category-price-badge" class="inline-flex items-center px-3 py-1 bg-blue-50 rounded-xl text-xs font-bold text-blue-600 uppercase tracking-widest"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                        <div>
                            <label for="receipt_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Nota (Kg)</label>
                            <input type="number" step="0.01" name="receipt_weight" id="receipt_weight" value="{{ old('receipt_weight') }}" placeholder="0.00"
                                class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                        </div>
                        <div>
                            <label for="actual_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Riil (Kg)</label>
                            <input type="number" step="0.01" name="actual_weight" id="actual_weight" value="{{ old('actual_weight') }}" placeholder="0.00"
                                class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Data Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const infoCard = document.getElementById('category-info-card');
        const imageWrapper = document.getElementById('category-image-wrapper');
        const imagePreview = document.getElementById('category-image-preview');
        const groupBadge = document.getElementById('category-group-badge');
        const priceBadge = document.getElementById('category-price-badge');

        function updateCategoryInfo() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const group = selectedOption?.dataset?.group;
            const price = selectedOption?.dataset?.price;
            const image = selectedOption?.dataset?.image;

            if (! group) {
                infoCard.classList.add('hidden');
                imageWrapper.classList.add('hidden');
                return;
            }

            infoCard.classList.remove('hidden');
            groupBadge.textContent = group;
            priceBadge.textContent = 'Harga Default Rp ' + new Intl.NumberFormat('id-ID').format(parseFloat(price || 0));

            if (image) {
                imagePreview.src = image;
                imageWrapper.classList.remove('hidden');
            } else {
                imagePreview.src = '';
                imageWrapper.classList.add('hidden');
            }
        }

        categorySelect.addEventListener('change', updateCategoryInfo);
        updateCategoryInfo();
    });
</script>
@endpush
