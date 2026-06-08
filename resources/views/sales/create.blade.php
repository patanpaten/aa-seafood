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
                        <p class="text-slate-500 text-sm font-medium">Catat barang yang dijual ke pelanggan.</p>
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

                <form action="{{ route('sales.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Penjualan</label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700" required>
                        </div>

                        <div>
                            <label for="partner_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Pembeli / Restoran</label>
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
                            <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Barang</label>
                            <select name="category_id" id="category_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih --</option>
                                @forelse($groupedCategories as $groupName => $groupCategories)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($groupCategories as $category)
                                            <option value="{{ $category->id }}"
                                                data-retail-price="{{ $category->retail_price }}"
                                                data-wholesale-price="{{ $category->wholesale_price }}"
                                                @selected(old('category_id') == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @empty
                                    <option value="" disabled>Tidak ada item untuk grup ini</option>
                                @endforelse
                            </select>
                            <p class="text-xs text-slate-400 mt-2 ml-1">Pilihan barang sudah dikelompokkan supaya lebih mudah dicari.</p>
                        </div>

                        <div>
                            <label for="price_type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilihan Harga</label>
                            <select name="price_type" id="price_type"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="eceran" @selected(old('price_type', 'eceran') === 'eceran')>Harga Biasa</option>
                                <option value="grosir" @selected(old('price_type') === 'grosir')>Harga Banyak</option>
                            </select>
                        </div>

                        <div>
                            <label for="quantity_sold_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jumlah Jual (Kg)</label>
                            <input type="number" step="0.01" name="quantity_sold_kg" id="quantity_sold_kg" value="{{ old('quantity_sold_kg') }}" placeholder="0.00"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-black text-xl text-slate-700" required>
                        </div>
                    </div>

                    <div id="price-type-card" class="hidden p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-2">Ringkasan Harga</p>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span id="price-type-badge" class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-[0.2em]"></span>
                                    <span id="price-source-label" class="text-sm font-semibold text-slate-500"></span>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Harga Yang Dipakai</p>
                                <p id="price-highlight" class="text-2xl font-black text-slate-900">Rp 0</p>
                            </div>
                        </div>

                        <div class="mt-5 p-4 rounded-2xl bg-white border border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan</p>
                            <p class="text-sm font-medium text-slate-500">Harga per kg sekarang otomatis mengikuti data barang. Jadi tidak perlu diisi lagi.</p>
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
        const priceTypeCard = document.getElementById('price-type-card');
        const priceTypeBadge = document.getElementById('price-type-badge');
        const priceSourceLabel = document.getElementById('price-source-label');
        const priceHighlight = document.getElementById('price-highlight');

        function syncDefaultPrice() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const priceType = priceTypeSelect.value;
            const defaultPrice = priceType === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;

            if (! defaultPrice) {
                priceTypeCard.classList.add('hidden');
                return;
            }

            const formatter = new Intl.NumberFormat('id-ID');
            const badgeClass = priceType === 'grosir'
                ? 'bg-emerald-50 text-emerald-600 border border-emerald-100'
                : 'bg-blue-50 text-blue-600 border border-blue-100';

            priceTypeCard.classList.remove('hidden');
            priceTypeBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-[0.2em] ' + badgeClass;
            priceTypeBadge.textContent = priceType === 'grosir' ? 'Harga Banyak' : 'Harga Biasa';
            priceSourceLabel.textContent = selectedOption.text ? 'Mengikuti barang ' + selectedOption.text : '';
            priceHighlight.textContent = 'Rp ' + formatter.format(parseFloat(defaultPrice || 0));
        }

        categorySelect.addEventListener('change', syncDefaultPrice);
        priceTypeSelect.addEventListener('change', syncDefaultPrice);
        syncDefaultPrice();
    });
</script>
@endpush
