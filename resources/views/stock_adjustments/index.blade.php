@extends('layouts.app')

@section('title', 'Cek Stok')

@section('content')
    <div class="mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Cek Stok</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Lihat dulu stok yang tersedia di gudang, lalu pilih barang yang mau diedit atau dicek ulang.</p>
        </div>
    </div>

    <div id="stock-adjustment-feedback" class="hidden mb-8 rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Stok Yang Tersedia Di Gudang</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Siap Dicek</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Barang Masuk</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Barang Terjual</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-blue-400 uppercase tracking-[0.2em]">Sisa Stok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $category)
                        @php
                            $currentStock = ((float) ($category->incoming_stocks_sum_actual_weight ?? 0))
                                - ((float) ($category->sales_sum_quantity_sold_kg ?? 0))
                                + ((float) ($category->adjustments_sum_difference ?? 0));
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                                    {{ $category->display_group_name }}
                                </span>
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-700">{{ $category->name }}</td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ number_format($category->incoming_stocks_sum_actual_weight ?? 0, 2) }} kg</td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ number_format($category->sales_sum_quantity_sold_kg ?? 0, 2) }} kg</td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-black text-sm">
                                    {{ number_format($currentStock, 2) }} kg
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <button
                                    type="button"
                                    class="open-stock-adjustment-modal inline-flex items-center justify-center w-11 h-11 text-orange-500 hover:text-orange-600 hover:bg-orange-50 rounded-2xl transition-all"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ $category->name }}"
                                    data-category-group="{{ $category->display_group_name }}"
                                    data-current-stock="{{ number_format($currentStock, 2, '.', '') }}"
                                    title="Edit Stok"
                                    aria-label="Edit Stok"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data barang di gudang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Riwayat Cek Stok</h3>
            <span class="px-3 py-1 bg-white rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 uppercase tracking-widest">Riwayat Perubahan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kelompok</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Barang</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Tercatat</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Stok Hasil Hitung</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Selisih</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Catatan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($adjustments as $adj)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                                {{ $adj->category->display_group_name }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $adj->category->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->previous_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-500">{{ number_format($adj->actual_stock, 2) }} kg</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black {{ $adj->difference >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $adj->difference > 0 ? '+' : '' }}{{ number_format($adj->difference, 2) }} kg
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-400 italic">"{{ $adj->reason }}"</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-medium text-slate-500">{{ $adj->user->name }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H4a2 2 0 00-2 2v7m18 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5m18 0l-2-2m-2-2l-2-2m-2-2l-2-2m-2-2L4 13"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium italic">Belum ada riwayat cek stok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>

    <div id="stock-adjustment-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-stock-adjustment-modal></div>
        <div class="relative flex min-h-full items-start justify-center p-4 sm:items-center sm:py-8 overflow-y-auto">
            <div class="w-full max-w-2xl max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Edit Stok</h2>
                            <p class="text-slate-500 text-sm font-medium mt-2">Isi stok hasil hitung dan catatan perubahan.</p>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center" data-close-stock-adjustment-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Barang</p>
                                <p id="stock-adjustment-category-name" class="text-base font-extrabold text-slate-700">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kelompok</p>
                                <p id="stock-adjustment-category-group" class="text-sm font-semibold text-slate-600">-</p>
                            </div>
                        </div>
                    </div>

                    <div id="stock-adjustment-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700"></div>

                    <form id="stock-adjustment-form" action="{{ route('stock-adjustments.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="category_id" id="stock_adjustment_category_id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stok Yang Tercatat</label>
                                <div class="flex items-baseline">
                                    <span id="stock-adjustment-current-stock" class="text-3xl font-black text-slate-700">0.00</span>
                                    <span class="ml-2 text-sm font-bold text-slate-400 uppercase">kg</span>
                                </div>
                            </div>
                            <div class="p-6 bg-orange-50/50 rounded-2xl border border-orange-100">
                                <label for="stock_adjustment_actual_stock" class="block text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-2 ml-1">Stok Hasil Hitung</label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" name="actual_stock" id="stock_adjustment_actual_stock" placeholder="0.00"
                                        class="w-full bg-transparent text-3xl font-black text-orange-600 outline-none placeholder-orange-200" required>
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 text-sm font-bold text-orange-300 uppercase">kg</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="stock_adjustment_reason" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Catatan</label>
                            <textarea name="reason" id="stock_adjustment_reason" rows="3" placeholder="Contoh: barang rusak, salah hitung, atau alasan lain"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition font-semibold text-slate-700" required></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="stock-adjustment-submit-button" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-orange-200 transition-all uppercase tracking-widest text-sm">
                                Simpan Hasil Cek
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-sm" data-close-stock-adjustment-modal>
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('stock-adjustment-modal');
        const form = document.getElementById('stock-adjustment-form');
        const feedback = document.getElementById('stock-adjustment-feedback');
        const errorsBox = document.getElementById('stock-adjustment-errors');
        const submitButton = document.getElementById('stock-adjustment-submit-button');
        const categoryIdInput = document.getElementById('stock_adjustment_category_id');
        const categoryName = document.getElementById('stock-adjustment-category-name');
        const categoryGroup = document.getElementById('stock-adjustment-category-group');
        const currentStock = document.getElementById('stock-adjustment-current-stock');
        const actualStockInput = document.getElementById('stock_adjustment_actual_stock');
        const reasonInput = document.getElementById('stock_adjustment_reason');

        function showFeedback(message) {
            feedback.className = 'mb-8 rounded-2xl border px-5 py-4 text-sm font-semibold bg-emerald-50 border-emerald-100 text-emerald-700';
            feedback.textContent = message;
            feedback.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showErrors(errors) {
            const items = Object.values(errors).flat();
            errorsBox.innerHTML = '<ul class="list-disc list-inside space-y-1">' + items.map(item => '<li>' + item + '</li>').join('') + '</ul>';
            errorsBox.classList.remove('hidden');
        }

        function resetErrors() {
            errorsBox.classList.add('hidden');
            errorsBox.innerHTML = '';
        }

        function openModal(button) {
            categoryIdInput.value = button.dataset.categoryId || '';
            categoryName.textContent = button.dataset.categoryName || '-';
            categoryGroup.textContent = button.dataset.categoryGroup || '-';
            currentStock.textContent = parseFloat(button.dataset.currentStock || 0).toFixed(2);
            actualStockInput.value = parseFloat(button.dataset.currentStock || 0).toFixed(2);
            reasonInput.value = '';
            resetErrors();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            categoryIdInput.value = '';
            categoryName.textContent = '-';
            categoryGroup.textContent = '-';
            currentStock.textContent = '0.00';
            resetErrors();
        }

        document.querySelectorAll('.open-stock-adjustment-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-stock-adjustment-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            resetErrors();
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        showErrors(data.errors);
                    } else {
                        showErrors({ general: [data.message || 'Terjadi kesalahan saat menyimpan data.'] });
                    }
                    return;
                }

                closeModal();
                showFeedback(data.message + ' Selisih: ' + data.difference + ' kg.');
                window.setTimeout(() => window.location.reload(), 900);
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Hasil Cek';
            }
        });
    });
</script>
@endpush
