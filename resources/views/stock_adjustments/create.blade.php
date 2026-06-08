@extends('layouts.app')

@section('title', 'Cek Stok Barang')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex items-center space-x-4 mb-10">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Cek Stok Barang</h1>
                        <p class="text-slate-500 text-sm font-medium">Cocokkan catatan stok dengan jumlah barang yang benar-benar ada.</p>
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

                <form action="{{ route('stock-adjustments.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Pilih Nama Barang</label>
                            <select name="category_id" id="category_id" 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                <option value="">-- Pilih Jenis --</option>
                                @forelse($groupedCategories as $groupName => $groupCategories)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($groupCategories as $category)
                                            <option value="{{ $category->id }}"
                                                data-stock="{{ $category->current_stock }}"
                                                data-group="{{ $groupName }}"
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

                        <div id="category-group-info" class="hidden p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Kelompok Barang</p>
                            <span id="category-group-value" class="inline-flex items-center px-3 py-2 bg-white rounded-xl border border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-widest"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stok Yang Tercatat</label>
                                <div class="flex items-baseline">
                                    <span id="current_stock_display" class="text-3xl font-black text-slate-300">0.00</span>
                                    <span class="ml-2 text-sm font-bold text-slate-400 uppercase">kg</span>
                                </div>
                            </div>
                            <div class="p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                                <label for="actual_stock" class="block text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-2 ml-1">Stok Hasil Hitung</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="actual_stock" id="actual_stock" value="{{ old('actual_stock') }}" placeholder="0.00"
                                        class="w-full bg-transparent text-3xl font-black text-orange-600 outline-none placeholder-orange-200" required>
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-sm font-bold text-orange-300 uppercase">kg</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="reason" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Catatan</label>
                            <textarea name="reason" id="reason" rows="3" placeholder="Contoh: barang rusak, salah hitung, atau alasan lain"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-semibold text-slate-700" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-orange-200 transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Simpan Hasil Cek
                        </button>
                        <a href="{{ route('stock-adjustments.index') }}" class="flex-1 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 font-extrabold py-5 rounded-2xl transition-all transform active:scale-[0.98] uppercase tracking-widest text-sm">
                            Batal
                        </a>
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
        const stockDisplay = document.getElementById('current_stock_display');
        const groupInfo = document.getElementById('category-group-info');
        const groupValue = document.getElementById('category-group-value');

        categorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const group = selectedOption.getAttribute('data-group');
            
            if (stock) {
                stockDisplay.textContent = parseFloat(stock).toFixed(2);
                stockDisplay.classList.remove('text-slate-300');
                stockDisplay.classList.add('text-slate-700');
                groupValue.textContent = group;
                groupInfo.classList.remove('hidden');
            } else {
                stockDisplay.textContent = '0.00';
                stockDisplay.classList.add('text-slate-300');
                stockDisplay.classList.remove('text-slate-700');
                groupValue.textContent = '';
                groupInfo.classList.add('hidden');
            }
        });

        categorySelect.dispatchEvent(new Event('change'));
    });
</script>
@endpush
