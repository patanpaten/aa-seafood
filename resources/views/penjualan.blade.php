@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
    <div class="space-y-8">
        @include('sales.header')

        <div id="sale-feedback" class="hidden rounded-2xl border px-5 py-4 text-sm font-semibold"></div>

        @include('sales.partner-table')
    </div>

    @include('sales.sale-modal')

    @include('sales.add-partner-modal')

    @include('sales.edit-partner-modal')

    @include('sales.edit-sale-modal')
@endsection


@push('scripts')
<script>
    // FUNGSI UNTUK MODAL EDIT PELANGGAN (BARU)
    function openEditPartnerModal(id, name, contact, address) {
        document.getElementById('edit_partner_id').value = id;
        document.getElementById('edit_partner_name').value = name;
        document.getElementById('edit_partner_contact').value = contact || '';
        document.getElementById('edit_partner_address').value = address || '';
        document.getElementById('edit-partner-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditPartnerModal() {
        document.getElementById('edit-partner-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('edit-partner-form').reset();
        document.getElementById('edit-partner-errors').classList.add('hidden');
    }

    async function submitEditPartner() {
        const id = document.getElementById('edit_partner_id').value;
        const nameInput = document.getElementById('edit_partner_name').value.trim();
        const contactInput = document.getElementById('edit_partner_contact').value.trim();
        const addressInput = document.getElementById('edit_partner_address').value.trim();
        const errorBox = document.getElementById('edit-partner-errors');
        const btnUpdate = document.getElementById('btn-update-partner');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama pelanggan wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnUpdate.disabled = true;
        btnUpdate.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            const url = `/partners/${id}`;
            const response = await fetch(url, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ 
                    name: nameInput,
                    contact: contactInput,
                    address: addressInput
                })
            });

            const data = await response.json();

            if (!response.ok) {
                const errMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menyimpan.');
                errorBox.innerHTML = errMsg;
                errorBox.classList.remove('hidden');
                btnUpdate.disabled = false;
                btnUpdate.textContent = 'Simpan Perubahan';
                return;
            }

            window.location.reload();

        } catch (error) {
            errorBox.innerHTML = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
            btnUpdate.disabled = false;
            btnUpdate.textContent = 'Simpan Perubahan';
        }
    }

    // FUNGSI UNTUK MODAL TAMBAH PELANGGAN (AJAX)
    function openPartnerModal() {
        document.getElementById('add-partner-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        document.getElementById('new_partner_name').focus();
    }

    function closePartnerModal() {
        document.getElementById('add-partner-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('add-partner-form').reset();
        document.getElementById('partner-errors').classList.add('hidden');
    }

    async function submitPartner() {
        const nameInput = document.getElementById('new_partner_name').value.trim();
        const contactInput = document.getElementById('new_partner_contact').value.trim();
        const addressInput = document.getElementById('new_partner_address').value.trim();
        const errorBox = document.getElementById('partner-errors');
        const btnSave = document.getElementById('btn-save-partner');

        if (!nameInput) {
            errorBox.innerHTML = 'Nama pelanggan wajib diisi!';
            errorBox.classList.remove('hidden');
            return;
        }

        btnSave.disabled = true;
        btnSave.textContent = 'Menyimpan...';
        errorBox.classList.add('hidden');

        try {
            const response = await fetch("{{ route('partners.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ 
                    name: nameInput,
                    contact: contactInput,
                    address: addressInput
                })
            });

            const data = await response.json();

            if (!response.ok) {
                const errMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menyimpan.');
                errorBox.innerHTML = errMsg;
                errorBox.classList.remove('hidden');
                btnSave.disabled = false;
                btnSave.textContent = 'Simpan Pelanggan';
                return;
            }

            window.location.reload();

        } catch (error) {
            errorBox.innerHTML = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Pelanggan';
        }
    }

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


    function toggleDeliveryRow(partnerId) {
    const row = document.getElementById(`delivery-row-${partnerId}`);
    const arrow = document.getElementById(`icon-arrow-${partnerId}`);
    
    if (row.classList.contains('hidden')) {
        // Tampilkan baris kontainer pesanan
        row.classList.remove('hidden');
        // Putar arrow icon ke bawah
        arrow.classList.add('rotate-90');
    } else {
        // Sembunyikan baris kontainer pesanan
        row.classList.add('hidden');
        // Kembalikan arrow icon semula
        arrow.classList.remove('rotate-90');
    }
}


// MEMBUKA MODAL EDIT & ISI DATA OTOMATIS
function openEditSaleModal(id, name, phone, date, categoryId, quantity, price) {
    const modal = document.getElementById('editSaleModal');
    const form = document.getElementById('editSaleForm');
    
    form.action = `/sales/${id}`; 
    
    // Isi data pengantar
    document.getElementById('edit_driver_name').value = name || '';
    document.getElementById('edit_driver_phone').value = phone || '';
    
    // Isi data transaksi utama (Tanggal langsung terisi & kalender tetap aktif)
    if (date) {
        // Jika formatnya timestamp/datetime, ambil 10 karakter pertama saja (YYYY-MM-DD)
        const formattedDate = date.includes(' ') ? date.split(' ')[0] : date.split('T')[0];
        document.getElementById('edit_sale_date').value = formattedDate;
    } else {
        document.getElementById('edit_sale_date').value = '';
    }
    document.getElementById('edit_sale_category_id').value = categoryId || '';
    document.getElementById('edit_sale_quantity_sold_kg').value = quantity || '';
    document.getElementById('edit_sale_price_per_kg').value = price || '';
    
    // Perbarui acuan harga card hitam berdasarkan barang terpilih
    updateEditPriceReferences();
    
    // Tampilkan Modal tepat di tengah (Menggunakan flex items-center)
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

// FUNGSI UPDATE HARGA ACUAN SECARA OTOMATIS SAAT GANTI BARANG
function updateEditPriceReferences() {
    const select = document.getElementById('edit_sale_category_id');
    const selectedOption = select.options[select.selectedIndex];
    
    const label = document.getElementById('edit-sale-price-source-label');
    const retailText = document.getElementById('edit-sale-reference-retail');
    const wholesaleText = document.getElementById('edit-sale-reference-wholesale');
    
    if (selectedOption && selectedOption.value !== "") {
        const name = selectedOption.text.trim();
        const retailPrice = parseFloat(selectedOption.getAttribute('data-retail-price')) || 0;
        const wholesalePrice = parseFloat(selectedOption.getAttribute('data-wholesale-price')) || 0;
        
        label.textContent = `Acuan Harga Untuk: ${name}`;
        retailText.textContent = `Rp ${retailPrice.toLocaleString('id-ID')}`;
        wholesaleText.textContent = `Rp ${wholesalePrice.toLocaleString('id-ID')}`;
        
        // Simpan data mentah ke atribut sementara untuk tombol klik
        retailText.setAttribute('data-raw', retailPrice);
        wholesaleText.setAttribute('data-raw', wholesalePrice);
    } else {
        label.textContent = "Silahkan pilih barang terlebih dahulu";
        retailText.textContent = "Rp 0";
        wholesaleText.textContent = "Rp 0";
    }
}

// MEMASUKKAN HARGA ACUAN KE INPUT UTAMA SAAT TOMBOL DIKLIK
function applyEditPrice(type) {
    const retailText = document.getElementById('edit-sale-reference-retail');
    const wholesaleText = document.getElementById('edit-sale-reference-wholesale');
    const inputPrice = document.getElementById('edit_sale_price_per_kg');
    
    if (type === 'retail') {
        const price = retailText.getAttribute('data-raw');
        if(price) inputPrice.value = price;
    } else if (type === 'wholesale') {
        const price = wholesaleText.getAttribute('data-raw');
        if(price) inputPrice.value = price;
    }
}

// MENUTUP MODAL
function closeEditSaleModal() {
    const modal = document.getElementById('editSaleModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}



function triggerDeleteModal(saleId, buyerName) {
    const modal = document.getElementById('deleteConfirmationModal');
    const form = document.getElementById('deleteSaleForm');
    const namePlaceholder = document.getElementById('deleteModalBuyer');

    namePlaceholder.textContent = buyerName;

    form.action = `/sales/${saleId}`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmationModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const editModal = document.getElementById('editSaleModal'); 
    
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>
@endpush