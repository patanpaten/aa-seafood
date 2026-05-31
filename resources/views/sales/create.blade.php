@extends('layouts.app')

@section('title', 'Catat Penjualan')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Catat Penjualan</h1>
                        <p class="text-slate-500 text-sm font-medium">Input pengiriman seafood ke restoran partner.</p>
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

                <form action="{{ route('sales.create') }}" method="GET" class="mb-8">
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4 items-end">
                            <div>
                                <label for="group" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Filter Grup Kategori</label>
                                <select name="group" id="group" class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none">
                                    <option value="">Semua Grup</option>
                                    @foreach($categoryGroups as $groupName)
                                        <option value="{{ $groupName }}" @selected($selectedGroup === $groupName)>{{ $groupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-6 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-extrabold uppercase tracking-widest text-xs transition-all">
                                Terapkan
                            </button>
                            <a href="{{ route('sales.create') }}" class="px-6 py-4 bg-white hover:bg-slate-100 text-slate-500 rounded-2xl font-extrabold uppercase tracking-widest text-xs transition-all text-center border border-slate-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <form action="{{ route('sales.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Penjualan</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div>
                            <label for="partner_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Restoran Partner</label>
                            <select name="partner_id" id="partner_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih Partner --</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jenis Seafood</label>
                            <select name="category_id" id="category_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih --</option>
                                @forelse($groupedCategories as $groupName => $groupCategories)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($groupCategories as $category)
                                            <option value="{{ $category->id }}"
                                                data-retail-price="{{ $category->retail_price ?? $category->price }}"
                                                data-wholesale-price="{{ $category->wholesale_price ?? $category->price }}"
                                                @selected(old('category_id') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @empty
                                    <option value="" disabled>Tidak ada item untuk grup ini</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label for="price_type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tipe Harga</label>
                            <select name="price_type" id="price_type"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="eceran" @selected(old('price_type', 'eceran') === 'eceran')>Eceran</option>
                                <option value="grosir" @selected(old('price_type') === 'grosir')>Grosir</option>
                            </select>
                        </div>

                        <div>
                            <label for="quantity_sold_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jumlah Jual (Kg)</label>
                            <input type="number" step="0.01" name="quantity_sold_kg" id="quantity_sold_kg" value="{{ old('quantity_sold_kg') }}" placeholder="0.00"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-black text-xl text-slate-700" required>
                        </div>
                    </div>

                    <div class="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100">
                        <label for="price_per_kg" class="block text-xs font-bold text-emerald-400 uppercase tracking-widest mb-3 ml-1">Harga Jual per Kg</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-emerald-600 text-xl">Rp</span>
                            <input type="number" name="price_per_kg" id="price_per_kg" value="{{ old('price_per_kg') }}" placeholder="0"
                                class="w-full pl-16 pr-5 py-5 bg-white border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-black text-3xl text-emerald-600" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Penjualan
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
        const priceTypeSelect = document.getElementById('price_type');
        const priceInput = document.getElementById('price_per_kg');

        function syncDefaultPrice() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const priceType = priceTypeSelect.value;
            const defaultPrice = priceType === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;

            if (! defaultPrice) {
                return;
            }

            priceInput.value = parseFloat(defaultPrice).toFixed(2);
        }

        categorySelect.addEventListener('change', syncDefaultPrice);
        priceTypeSelect.addEventListener('change', syncDefaultPrice);
        syncDefaultPrice();
    });
</script>
@endpush
