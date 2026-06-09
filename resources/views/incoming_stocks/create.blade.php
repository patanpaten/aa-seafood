@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Stok Masuk</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Pilih tempat beli terlebih dahulu, lalu input barang masuk lewat pop-up.</p>
            </div>
            <div class="px-4 py-3 bg-blue-50 rounded-2xl border border-blue-100 text-blue-600 text-sm font-semibold">
                Form input tampil tanpa pindah halaman
            </div>
        </div>

        <div id="incoming-stock-feedback" class="hidden rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Tempat Beli</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Alamat</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-slate-700">{{ $supplier->name }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $supplier->contact ?: '-' }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $supplier->address ?: '-' }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="open-incoming-stock-modal inline-flex items-center justify-center w-11 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg shadow-blue-200 transition-all"
                                            data-supplier-id="{{ $supplier->id }}"
                                            data-supplier-name="{{ $supplier->name }}"
                                            data-supplier-contact="{{ $supplier->contact }}"
                                            data-supplier-address="{{ $supplier->address }}"
                                            title="Input Stok"
                                            aria-label="Input Stok"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                                        </button>
                                        @if(auth()->user()?->isOwner())
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center justify-center w-11 h-11 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all" title="Edit" aria-label="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus tempat beli ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-11 h-11 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all" title="Hapus" aria-label="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data tempat beli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="incoming-stock-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-incoming-stock-modal></div>
        <div class="relative flex min-h-full items-start justify-center p-4 sm:items-center sm:py-8 overflow-y-auto">
            <div class="w-full max-w-3xl max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Input Stok Masuk</h2>
                            <p class="text-slate-500 text-sm font-medium mt-2">Isi data barang yang baru datang untuk tempat beli yang dipilih.</p>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center" data-close-incoming-stock-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-5 bg-slate-50 rounded-[2rem] border border-slate-100 mb-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-3">Tempat Beli Yang Dipilih</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama</p>
                                <p id="incoming-selected-supplier-name" class="text-sm font-extrabold text-slate-700">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kontak</p>
                                <p id="incoming-selected-supplier-contact" class="text-sm font-semibold text-slate-600">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                                <p id="incoming-selected-supplier-address" class="text-sm font-semibold text-slate-600">-</p>
                            </div>
                        </div>
                    </div>

                    <div id="incoming-stock-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700"></div>

                    <form id="incoming-stock-form" action="{{ route('incoming-stocks.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="supplier_id" id="incoming_supplier_id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="incoming_date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Masuk</label>
                                <input type="date" name="date" id="incoming_date" value="{{ date('Y-m-d') }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700" required>
                            </div>

                            <div>
                                <label for="incoming_category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Barang</label>
                                <select name="category_id" id="incoming_category_id"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @forelse($groupedCategories as $groupName => $groupCategories)
                                        <optgroup label="{{ $groupName }}">
                                            @foreach($groupCategories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @empty
                                        <option value="" disabled>Tidak ada barang.</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="incoming_purchase_price_per_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Beli Per Kg</label>
                                <input type="number" step="0.01" min="0" name="purchase_price_per_kg" id="incoming_purchase_price_per_kg" placeholder="0"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-xl text-slate-700" required>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Belanja</p>
                                <p id="incoming-total-purchase-price" class="text-2xl font-black text-slate-900">Rp 0</p>
                                <p class="mt-2 text-xs font-medium text-slate-500">Dihitung dari berat hasil timbang x harga beli per kg.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                            <div>
                                <label for="incoming_receipt_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Di Nota (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="receipt_weight" id="incoming_receipt_weight" placeholder="0.00"
                                    class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                            </div>
                            <div>
                                <label for="incoming_actual_weight" class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-3 ml-1">Berat Hasil Timbang (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="actual_weight" id="incoming_actual_weight" placeholder="0.00"
                                    class="w-full px-5 py-4 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition font-black text-2xl text-blue-600" required>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="incoming-stock-submit-button" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-blue-200 transition-all uppercase tracking-widest text-sm">
                                Simpan Barang Masuk
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-sm" data-close-incoming-stock-modal>
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
        const modal = document.getElementById('incoming-stock-modal');
        const form = document.getElementById('incoming-stock-form');
        const feedback = document.getElementById('incoming-stock-feedback');
        const errorsBox = document.getElementById('incoming-stock-errors');
        const submitButton = document.getElementById('incoming-stock-submit-button');
        const supplierIdInput = document.getElementById('incoming_supplier_id');
        const selectedName = document.getElementById('incoming-selected-supplier-name');
        const selectedContact = document.getElementById('incoming-selected-supplier-contact');
        const selectedAddress = document.getElementById('incoming-selected-supplier-address');
        const purchasePriceInput = document.getElementById('incoming_purchase_price_per_kg');
        const actualWeightInput = document.getElementById('incoming_actual_weight');
        const totalPurchasePrice = document.getElementById('incoming-total-purchase-price');

        function showFeedback(message, type) {
            feedback.className = 'rounded-2xl border px-5 py-4 text-sm font-semibold';
            if (type === 'warning') {
                feedback.classList.add('bg-amber-50', 'border-amber-100', 'text-amber-700');
            } else {
                feedback.classList.add('bg-emerald-50', 'border-emerald-100', 'text-emerald-700');
            }
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

        function updateTotalPurchasePrice() {
            const price = parseFloat(purchasePriceInput.value || 0);
            const weight = parseFloat(actualWeightInput.value || 0);
            const total = price * weight;

            totalPurchasePrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        function openModal(button) {
            supplierIdInput.value = button.dataset.supplierId;
            selectedName.textContent = button.dataset.supplierName || '-';
            selectedContact.textContent = button.dataset.supplierContact || '-';
            selectedAddress.textContent = button.dataset.supplierAddress || '-';
            updateTotalPurchasePrice();
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            document.getElementById('incoming_date').value = '{{ date('Y-m-d') }}';
            supplierIdInput.value = '';
            selectedName.textContent = '-';
            selectedContact.textContent = '-';
            selectedAddress.textContent = '-';
            totalPurchasePrice.textContent = 'Rp 0';
            resetErrors();
        }

        document.querySelectorAll('.open-incoming-stock-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-incoming-stock-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        purchasePriceInput.addEventListener('input', updateTotalPurchasePrice);
        actualWeightInput.addEventListener('input', updateTotalPurchasePrice);

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
                const successMessage = data.total_purchase_price
                    ? data.message + ' Total belanja: Rp ' + data.total_purchase_price
                    : data.message;
                showFeedback(data.warning || successMessage, data.warning ? 'warning' : 'success');
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Barang Masuk';
            }
        });
    });
</script>
@endpush
