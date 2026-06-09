@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Penjualan</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Pilih restoran dari tabel atau pakai tombol penjualan eceran untuk pembeli umum.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="button"
                    class="open-sale-modal inline-flex items-center justify-center gap-3 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl shadow-lg shadow-amber-200 transition-all font-extrabold text-sm uppercase tracking-wider"
                    data-buyer-mode="general"
                    data-buyer-name="Pembeli Umum"
                    title="Input Penjualan Eceran"
                    aria-label="Input Penjualan Eceran"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                    <span>Penjualan Eceran</span>
                </button>
                <div class="px-4 py-3 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-600 text-sm font-semibold">
                    Form input tampil tanpa pindah halaman
                </div>
            </div>
        </div>

        <div id="sale-feedback" class="hidden rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Nama Restoran</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Alamat</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-slate-700">{{ $partner->name }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $partner->contact ?: '-' }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-500">{{ $partner->address ?: '-' }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="open-sale-modal inline-flex items-center justify-center w-11 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-lg shadow-emerald-200 transition-all"
                                            data-partner-id="{{ $partner->id }}"
                                            data-partner-name="{{ $partner->name }}"
                                            data-partner-contact="{{ $partner->contact }}"
                                            data-partner-address="{{ $partner->address }}"
                                            title="Input Penjualan"
                                            aria-label="Input Penjualan"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m7-7H5"></path></svg>
                                        </button>
                                        @if(auth()->user()?->isOwner())
                                            <a href="{{ route('partners.edit', $partner) }}" class="inline-flex items-center justify-center w-11 h-11 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all" title="Edit" aria-label="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
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
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">Belum ada data restoran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="sale-modal" class="hidden fixed inset-0 z-[110]">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-sale-modal></div>
        <div class="relative flex min-h-full items-start justify-center p-4 sm:items-center sm:py-8 overflow-y-auto">
            <div class="w-full max-w-3xl max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-4rem)] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-y-auto">
                <div class="p-8 sm:p-10">
                    <div class="flex items-start justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Input Penjualan</h2>
                            <p class="text-slate-500 text-sm font-medium mt-2">Isi data seperlunya lalu simpan.</p>
                        </div>
                        <button type="button" class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center" data-close-sale-modal>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mb-6">
                        <p id="sale-mode-note" class="text-sm font-semibold text-slate-600">Pembeli mengikuti restoran yang dipilih.</p>
                    </div>

                    <div id="sale-partner-summary" class="p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 mb-6">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pembeli</p>
                        <p id="sale-selected-partner-name" class="text-base font-extrabold text-slate-700">-</p>
                    </div>

                    <div id="sale-errors" class="hidden mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm text-rose-700"></div>

                    <form id="sale-form" action="{{ route('sales.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="partner_id" id="sale_partner_id">
                        <input type="hidden" name="price_type" id="sale_price_type" value="eceran">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div id="sale-buyer-name-wrapper" class="hidden md:col-span-2">
                                <label for="sale_buyer_name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Pembeli</label>
                                <input type="text" name="buyer_name" id="sale_buyer_name" placeholder="Contoh: Bu Rina"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition font-semibold text-slate-700">
                            </div>

                            <div>
                                <label for="sale_date" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Tanggal Penjualan</label>
                                <input type="date" name="date" id="sale_date" value="{{ date('Y-m-d') }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700" required>
                            </div>

                            <div>
                                <label for="sale_category_id" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Barang</label>
                                <select name="category_id" id="sale_category_id"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700 appearance-none" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @forelse($groupedCategories as $groupName => $groupCategories)
                                        <optgroup label="{{ $groupName }}">
                                            @foreach($groupCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    data-retail-price="{{ $category->retail_price }}"
                                                    data-wholesale-price="{{ $category->wholesale_price }}">
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
                                <label for="sale_quantity_sold_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Jumlah Jual (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="quantity_sold_kg" id="sale_quantity_sold_kg" placeholder="0.00"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-black text-xl text-slate-700" required>
                            </div>

                            <div class="hidden md:block"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sale_price_per_kg" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Harga Jual Per Kg</label>
                                <input type="number" step="0.01" min="0" name="price_per_kg" id="sale_price_per_kg" placeholder="0"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition font-black text-xl text-slate-700" required>
                            </div>

                            <div id="sale-price-type-card" class="hidden p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Harga Acuan</p>
                                        <p id="sale-price-source-label" class="text-sm font-semibold text-slate-600"></p>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="p-3 rounded-xl bg-white border border-slate-200">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Biasa</p>
                                        <p id="sale-reference-retail" class="text-base font-black text-slate-900">Rp 0</p>
                                        <button type="button" id="sale-apply-retail-price" class="mt-3 inline-flex items-center justify-center w-full px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-[0.18em]">
                                            Pakai Harga Ini
                                        </button>
                                    </div>
                                    <div class="p-3 rounded-xl bg-white border border-slate-200">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Banyak</p>
                                        <p id="sale-reference-wholesale" class="text-base font-black text-slate-900">Rp 0</p>
                                        <button type="button" id="sale-apply-wholesale-price" class="mt-3 inline-flex items-center justify-center w-full px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-[0.18em]">
                                            Pakai Harga Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="sale-submit-button" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-5 rounded-2xl shadow-lg shadow-emerald-200 transition-all uppercase tracking-widest text-sm">
                                Simpan Penjualan
                            </button>
                            <button type="button" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-5 rounded-2xl transition-all uppercase tracking-widest text-sm" data-close-sale-modal>
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
        const modal = document.getElementById('sale-modal');
        const form = document.getElementById('sale-form');
        const feedback = document.getElementById('sale-feedback');
        const errorsBox = document.getElementById('sale-errors');
        const submitButton = document.getElementById('sale-submit-button');
        const modeNote = document.getElementById('sale-mode-note');
        const partnerSummary = document.getElementById('sale-partner-summary');
        const partnerIdInput = document.getElementById('sale_partner_id');
        const buyerNameWrapper = document.getElementById('sale-buyer-name-wrapper');
        const buyerNameInput = document.getElementById('sale_buyer_name');
        const selectedName = document.getElementById('sale-selected-partner-name');
        const categorySelect = document.getElementById('sale_category_id');
        const priceTypeInput = document.getElementById('sale_price_type');
        const priceInput = document.getElementById('sale_price_per_kg');
        const priceTypeCard = document.getElementById('sale-price-type-card');
        const priceSourceLabel = document.getElementById('sale-price-source-label');
        const referenceRetail = document.getElementById('sale-reference-retail');
        const referenceWholesale = document.getElementById('sale-reference-wholesale');
        const applyRetailPriceButton = document.getElementById('sale-apply-retail-price');
        const applyWholesalePriceButton = document.getElementById('sale-apply-wholesale-price');
        let currentMode = 'partner';
        let currentPartner = {
            id: '',
            name: '-',
            contact: '-',
            address: '-',
        };

        function showFeedback(message) {
            feedback.className = 'rounded-2xl border px-5 py-4 text-sm font-semibold bg-emerald-50 border-emerald-100 text-emerald-700';
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

        function setPartnerSummary(partner) {
            selectedName.textContent = partner.name || '-';
        }

        function syncBuyerMode() {
            const isGeneralBuyer = currentMode === 'general';

            buyerNameWrapper.classList.toggle('hidden', !isGeneralBuyer);
            partnerSummary.classList.toggle('hidden', isGeneralBuyer);
            buyerNameInput.required = isGeneralBuyer;
            partnerIdInput.value = isGeneralBuyer ? '' : currentPartner.id;

            if (isGeneralBuyer) {
                modeNote.textContent = 'Isi nama pembeli untuk penjualan eceran.';
                setPartnerSummary({ name: '-', contact: '-', address: '-' });
            } else {
                modeNote.textContent = 'Pembeli mengikuti restoran yang dipilih.';
                setPartnerSummary(currentPartner);
                buyerNameInput.value = currentPartner.name !== '-' ? currentPartner.name : '';
            }
        }

        function getSelectedDefaultPrice() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const priceType = priceTypeInput.value;

            return priceType === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;
        }

        function setPriceFromReference(type) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const selectedPrice = type === 'grosir'
                ? selectedOption?.dataset?.wholesalePrice
                : selectedOption?.dataset?.retailPrice;

            if (!selectedPrice) {
                return;
            }

            priceTypeInput.value = type;
            priceInput.value = selectedPrice;
            priceInput.dataset.manual = 'false';
            syncDefaultPrice(false, type);
        }

        function openModal(button) {
            currentMode = button.dataset.buyerMode || (button.dataset.partnerId ? 'partner' : 'general');
            currentPartner = {
                id: button.dataset.partnerId || '',
                name: button.dataset.partnerName || '-',
                contact: button.dataset.partnerContact || '-',
                address: button.dataset.partnerAddress || '-',
            };
            buyerNameInput.value = currentMode === 'general'
                ? (button.dataset.buyerName || '')
                : (button.dataset.partnerName || '');
            syncBuyerMode();
            syncDefaultPrice(true);
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            form.reset();
            document.getElementById('sale_date').value = '{{ date('Y-m-d') }}';
            currentMode = 'partner';
            currentPartner = {
                id: '',
                name: '-',
                contact: '-',
                address: '-',
            };
            partnerIdInput.value = '';
            priceTypeInput.value = 'eceran';
            setPartnerSummary(currentPartner);
            buyerNameInput.required = false;
            priceTypeCard.classList.add('hidden');
            priceInput.value = '';
            resetErrors();
        }

        function syncDefaultPrice(forceUpdate = false, preferredType = null) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const retailPrice = selectedOption?.dataset?.retailPrice;
            const wholesalePrice = selectedOption?.dataset?.wholesalePrice;

            if (!retailPrice && !wholesalePrice) {
                priceTypeCard.classList.add('hidden');
                referenceRetail.textContent = 'Rp 0';
                referenceWholesale.textContent = 'Rp 0';
                if (forceUpdate) {
                    priceInput.value = '';
                }
                return;
            }

            const formatter = new Intl.NumberFormat('id-ID');
            const typedPrice = parseFloat(priceInput.value || 0);
            const retailValue = parseFloat(retailPrice || 0);
            const wholesaleValue = parseFloat(wholesalePrice || 0);
            let matchedType = preferredType;

            if (!matchedType) {
                if (typedPrice > 0 && Math.abs(typedPrice - wholesaleValue) < 0.0001) {
                    matchedType = 'grosir';
                } else if (typedPrice > 0 && Math.abs(typedPrice - retailValue) < 0.0001) {
                    matchedType = 'eceran';
                } else {
                    matchedType = priceTypeInput.value || 'eceran';
                }
            }

            priceTypeCard.classList.remove('hidden');
            priceSourceLabel.textContent = selectedOption.text
                ? 'Acuan harga untuk ' + selectedOption.text
                : '';
            referenceRetail.textContent = 'Rp ' + formatter.format(parseFloat(retailPrice || 0));
            referenceWholesale.textContent = 'Rp ' + formatter.format(parseFloat(wholesalePrice || 0));

            if (forceUpdate || !priceInput.value || priceInput.dataset.manual !== 'true') {
                priceTypeInput.value = preferredType || 'eceran';
                priceInput.value = (preferredType || 'eceran') === 'grosir'
                    ? (wholesalePrice || retailPrice || '')
                    : (retailPrice || wholesalePrice || '');
                priceInput.dataset.manual = 'false';
            } else {
                priceTypeInput.value = matchedType;
            }
        }

        document.querySelectorAll('.open-sale-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-sale-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        categorySelect.addEventListener('change', syncDefaultPrice);
        priceInput.addEventListener('input', function () {
            this.dataset.manual = 'true';
            syncDefaultPrice();
        });
        applyRetailPriceButton.addEventListener('click', function () {
            setPriceFromReference('eceran');
        });
        applyWholesalePriceButton.addEventListener('click', function () {
            setPriceFromReference('grosir');
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
                showFeedback(data.total ? data.message + ' Total: Rp ' + data.total : data.message);
            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Penjualan';
            }
        });
    });
</script>
@endpush
