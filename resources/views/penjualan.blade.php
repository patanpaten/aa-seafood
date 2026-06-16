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

    @include('sales.bulk-delivery-modal')
    
@endsection

@push('scripts')
<script>
    const groupedCategories = @json($groupedCategories);

    // Toggle all sales checkbox
    document.getElementById('select-all-sales').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.sale-checkbox').forEach(checkbox => {
            checkbox.checked = checked;
        });
        updateBulkButton();
    });

    // Partner checkbox toggle
    document.querySelectorAll('.partner-sale-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const partnerId = this.dataset.partnerId;
            const row = document.getElementById(`delivery-row-${partnerId}`);
            if (row) {
                const checkboxes = row.querySelectorAll('.sale-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            }
            updateBulkButton();
        });
    });

    // Individual checkbox change
    document.querySelectorAll('.sale-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButton);
    });

    function updateBulkButton() {
        const selected = document.querySelectorAll('.sale-checkbox:checked');
        const btn = document.getElementById('bulk-delivery-btn');
        btn.disabled = selected.length === 0;
    }

    async function bulkMarkAsDelivering() {
        const selectedCheckboxes = document.querySelectorAll('.sale-checkbox:checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('Pilih setidaknya satu transaksi untuk dikirim!');
            return;
        }

        // Collect data from selected checkboxes
        const selectedData = Array.from(selectedCheckboxes).map(cb => ({
            id: cb.dataset.saleId,
            driverName: cb.dataset.driverName,
            driverPhone: cb.dataset.driverPhone,
            buyerName: cb.dataset.buyerName,
            buyerAddress: cb.dataset.buyerAddress,
            categoryName: cb.dataset.categoryName,
            quantity: cb.dataset.quantity
        }));

        // Group by driver first, then group driver's items by buyer
        const drivers = {};
        selectedData.forEach(item => {
            const driverKey = item.driverPhone || 'no-driver';
            if (!drivers[driverKey]) {
                drivers[driverKey] = {
                    name: item.driverName || 'Pengantar',
                    phone: item.driverPhone,
                    buyers: {} // key = buyerName, value = { address, items: [] }
                };
            }

            const buyerKey = item.buyerName;
            if (!drivers[driverKey].buyers[buyerKey]) {
                drivers[driverKey].buyers[buyerKey] = {
                    address: item.buyerAddress,
                    items: []
                };
            }

            drivers[driverKey].buyers[buyerKey].items.push(item);
        });

        // Show modal or process each driver
        const driverKeys = Object.keys(drivers);
        if (driverKeys.length === 0) {
            alert('Tidak ada transaksi yang dapat dikirim!');
            return;
        }

        let proceed = true;
        for (const driverKey of driverKeys) {
            const driver = drivers[driverKey];
            
            if (!driver.phone) {
                alert(`Driver ${driver.name} tidak memiliki nomor WhatsApp! Mohon isi terlebih dahulu.`);
                proceed = false;
                break;
            }

            // Generate WhatsApp message
            let message = `Halo ${driver.name},\n\nBerikut tugas pengantaran pesanan:\n\n`;
            
            for (const buyerName in driver.buyers) {
                const buyer = driver.buyers[buyerName];
                message += `*Toko: ${buyerName}*\n`;
                if (buyer.address) {
                    message += `*Alamat: ${buyer.address}*\n`;
                }
                message += `*Barang:*\n`;
                buyer.items.forEach((item, idx) => {
                    message += `${idx + 1}. ${item.categoryName} (${parseFloat(item.quantity).toLocaleString('id-ID')} Kg)\n`;
                });
                message += `\n`;
            }
            
            // Add upload proof links for each sale (optional, but let's keep it)
            message += `Jika barang sudah sampai, mohon upload bukti pengiriman ya!\n`;
            
            // Clean phone number
            let cleanPhone = driver.phone.replace(/[^0-9]/g, '');
            if (cleanPhone.startsWith('0')) {
                cleanPhone = '62' + cleanPhone.substring(1);
            } else if (cleanPhone.startsWith('8')) {
                cleanPhone = '62' + cleanPhone;
            }

            if (!confirm(`Yakin ingin mengirim pesan ke ${driver.name} (${cleanPhone}) dan mengubah status ${selectedData.length} transaksi?`)) {
                proceed = false;
                break;
            }

            // Open WhatsApp
            const waUrl = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        if (!proceed) {
            return;
        }

        // Proceed to update status on backend
        const saleIds = selectedData.map(item => item.id);
        const btn = document.getElementById('bulk-delivery-btn');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        try {
            const response = await fetch('{{ route("sales.bulk-delivery") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ sale_ids: saleIds }),
            });

            const data = await response.json();

            if (response.ok) {
                alert(data.message);
                window.location.reload();
            } else {
                throw new Error(data.message || 'Gagal memproses pengiriman');
            }
        } catch (error) {
            alert(error.message);
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

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

// FUNGSI UNTUK MODAL PENJUALAN DENGAN MULTI ITEM
let saleItemIndex = 0;

function getCategoryOptionsHtml() {
    // 1. CEK GLOBAL: Apakah ada minimal satu barang yang punya stok?
    let isAnyProductAvailable = false;
    for (let groupName in groupedCategories) {
        let hasStock = groupedCategories[groupName].some(cat => 
            cat.current_stock !== null && cat.current_stock !== undefined && parseFloat(cat.current_stock) > 0
        );
        if (hasStock) {
            isAnyProductAvailable = true;
            break;
        }
    }

    // 2. JIKA SEMUA STOK DI TOKO BENAR-BENAR HABIS (0)
    if (!isAnyProductAvailable) {
        return '<option value="" disabled selected>⚠️ STOK SEMUA BARANG HABIS</option>';
    }

    // 3. JIKA STOK AMAN, RENDER DROPDOWN SEPERTI BIASA
    let html = '<option value="">Pilih Barang</option>';
    
    for (let groupName in groupedCategories) {
        let categories = groupedCategories[groupName];
        
        // Cek apakah di dalam grup ini ada barang yang ready
        let hasAvailableItems = categories.some(cat => 
            cat.current_stock !== null && cat.current_stock !== undefined && parseFloat(cat.current_stock) > 0
        );
        
        // Jika isi grup habis semua, skip/jangan tampilkan nama grupnya
        if (!hasAvailableItems) continue;

        html += `<optgroup label="${groupName}">`;
        
        categories.forEach(cat => {
            // Hanya tampilkan barang yang stoknya lebih dari 0
            if (cat.current_stock !== null && cat.current_stock !== undefined && parseFloat(cat.current_stock) > 0) {
                let stockInfo = parseFloat(cat.current_stock).toFixed(2);
                
                html += `<option value="${cat.id}" 
                                 data-retail-price="${cat.retail_price}" 
                                 data-wholesale-price="${cat.wholesale_price}"
                                 data-stock="${cat.current_stock}">
                            ${cat.name} (Stok: ${stockInfo} kg)
                         </option>`;
            }
        });
        
        html += '</optgroup>';
    }
    return html;
}

    function addSaleItem() {
        const container = document.getElementById('sale-items-container');
        const index = saleItemIndex++;
        const html = `
            <div class="item-row bg-slate-50 border border-slate-200 rounded-2xl p-5" data-index="${index}">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Barang #${index + 1}</span>
                    <button type="button" onclick="removeSaleItem(this)" class="text-rose-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Barang</label>
                        <select name="items[${index}][category_id]" required onchange="updateSaleItemPrice(this)"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700">
                            ${getCategoryOptionsHtml()}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Tipe Harga</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="setSaleItemPriceType(this, 'retail')" class="flex-1 px-4 py-3 bg-white border-2 border-emerald-500 text-emerald-600 font-bold rounded-xl transition hover:bg-emerald-50 active-price-type" data-price-type="retail">Harga Biasa</button>
                            <button type="button" onclick="setSaleItemPriceType(this, 'wholesale')" class="flex-1 px-4 py-3 bg-white border-2 border-slate-200 text-slate-500 font-bold rounded-xl transition hover:bg-slate-100" data-price-type="wholesale">Harga Banyak</button>
                        </div>
                        <input type="hidden" name="items[${index}][price_type]" value="eceran">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Harga/Kg</label>
                        <input type="number" step="0.01" min="0" name="items[${index}][price_per_kg]" required oninput="updateSaleTotal()" placeholder="0"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-2 ml-1">Jumlah (Kg)</label>
                        <input type="number" step="0.01" min="0.01" name="items[${index}][quantity_sold_kg]" required oninput="updateSaleTotal()" placeholder="0.00"
                            class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition font-semibold text-slate-700">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        updateSaleTotal();
    }

   function setSaleItemPriceType(btn, type) {
        const row = btn.closest('.item-row');
        const priceTypeInput = row.querySelector('input[name*="price_type"]');
        const buttons = row.querySelectorAll('[data-price-type]');
        const select = row.querySelector('select[name*="category_id"]');
        
        // Update button styling
        buttons.forEach(b => {
            b.classList.remove('border-emerald-500', 'text-emerald-600', 'bg-emerald-50', 'active-price-type');
            b.classList.add('border-slate-200', 'text-slate-500');
        });
        btn.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50', 'active-price-type');
        
        // Update price type input value (use eceran/grosir for backend)
        priceTypeInput.value = type === 'retail' ? 'eceran' : 'grosir';
        
        // Update price (pass the select element)
        updateSaleItemPrice(select);
    }
    
    function updateSaleItemPrice(selectOrElement) {
        const row = selectOrElement.closest('.item-row');
        const priceInput = row.querySelector('input[name*="price_per_kg"]');
        const priceTypeInput = row.querySelector('input[name*="price_type"]');
        const select = row.querySelector('select[name*="category_id"]');
        
        if (!select) return;
        
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value !== "") {
            let price;
            const backendPriceType = priceTypeInput.value;
            if (backendPriceType === 'grosir') {
                price = parseFloat(selectedOption.dataset.wholesalePrice || 0);
            } else {
                price = parseFloat(selectedOption.dataset.retailPrice || 0);
            }
            priceInput.value = price;
        }
        updateSaleTotal();
    }

    function removeSaleItem(btn) {
        btn.closest('.item-row').remove();
        updateSaleTotal();
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function updateSaleTotal() {
        let total = 0;
        const items = document.querySelectorAll('#sale-items-container .item-row');
        items.forEach(item => {
            const price = parseFloat(item.querySelector('input[name*="price_per_kg"]').value || 0);
            const qty = parseFloat(item.querySelector('input[name*="quantity_sold_kg"]').value || 0);
            total += price * qty;
        });
        document.getElementById('sale-total-price').textContent = formatRupiah(total);
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
            
            // Reset items container and add first item
            document.getElementById('sale-items-container').innerHTML = '';
            saleItemIndex = 0;
            addSaleItem();
            
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
            setPartnerSummary(currentPartner);
            buyerNameInput.required = false;
            resetErrors();
            updateSaleTotal();
        }

        document.querySelectorAll('.open-sale-modal').forEach((button) => {
            button.addEventListener('click', function () {
                openModal(this);
            });
        });

        document.querySelectorAll('[data-close-sale-modal]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            resetErrors();
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        showErrors(data.errors);
                    } else {
                        showErrors({ general: [data.message || 'Terjadi kesalahan saat menyimpan data.'] });
                    }
                    submitButton.disabled = false;
                    submitButton.textContent = 'Simpan Penjualan';
                    return;
                }

                closeModal();
                showFeedback(data.message + (data.total ? ' Total: ' + data.total : ''));
                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            } catch (error) {
                showErrors({ general: ['Koneksi ke server gagal. Silakan coba lagi.'] });
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
            label.textContent = "Silakan pilih barang terlebih dahulu";
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




// 1. Logika Check All Global (Paling Atas)
function toggleSelectAllGlobal(masterCheckbox) {
    const allSaleCheckboxes = document.querySelectorAll('.sale-checkbox');
    const allPartnerCheckboxes = document.querySelectorAll('.partner-sale-checkbox');
    
    allSaleCheckboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    allPartnerCheckboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    
    updateBulkButtonState();
}

// 2. Logika Check All berdasarkan grup Mitra/Restoran tertentu
function toggleSelectAllPartner(partnerId, partnerMasterCheckbox) {
    const partnerCheckboxes = document.querySelectorAll(`.partner-${partnerId}-sales`);
    partnerCheckboxes.forEach(cb => cb.checked = partnerMasterCheckbox.checked);
    
    updateBulkButtonState();
}

// 3. Update Text & Keaktifan Tombol "Kirim yang Dipilih"
function updateBulkButtonState() {
    const checkedSales = document.querySelectorAll('.sale-checkbox:checked');
    const bulkBtn = document.getElementById('bulk-delivery-btn');
    const countSpan = document.getElementById('selected-count');
    
    countSpan.innerText = checkedSales.length;
    
    if (checkedSales.length > 0) {
        bulkBtn.removeAttribute('disabled');
    } else {
        bulkBtn.setAttribute('disabled', 'disabled');
        document.getElementById('select-all-sales').checked = false;
    }
}

// Menampung data sementara agar bisa diakses antar-fungsi modal
let globalSelectedIds = [];
let globalPartnerContact = "";
let globalPartnerName = "";
let isMultiPartner = false; 
let globalTaskDetailsText = ""; // Menampung list rincian pesanan untuk WA

function bulkMarkAsDelivering() {
    const checkedSales = document.querySelectorAll('.sale-checkbox:checked');
    
    globalSelectedIds = [];
    globalPartnerContact = "";
    globalPartnerName = "";
    isMultiPartner = false;
    globalTaskDetailsText = ""; 
    
    if (checkedSales.length === 0) return;

    // Ambil nomor kontak & nama supir dari baris PERTAMA sebagai acuan dasar grup kurir
    const firstContact = checkedSales[0].getAttribute('data-partner-contact') ? checkedSales[0].getAttribute('data-partner-contact').trim() : "";
    const firstName = checkedSales[0].getAttribute('data-partner-name') || "Pengantar";

    globalPartnerContact = firstContact;
    globalPartnerName = firstName;

    // Looping item terpilih untuk menyusun teks rincian tugas pengiriman
    checkedSales.forEach((cb, index) => {
        globalSelectedIds.push(cb.getAttribute('data-sale-id'));
        
        let currentContact = cb.getAttribute('data-partner-contact') ? cb.getAttribute('data-partner-contact').trim() : "";
        if (currentContact !== firstContact) {
            isMultiPartner = true;
        }

        // Ambil data manifest baris barang
        let storeName = cb.getAttribute('data-store-name') || "-";
        let itemName = cb.getAttribute('data-item-name') || "-";
        let itemQty = cb.getAttribute('data-item-qty') || "-";
        let proofLink = cb.getAttribute('data-proof-link') || "#";

        // Susun per tugas pesanan dengan format template baru
        globalTaskDetailsText += `Berikut tugas pengantaran pesanan:\n` +
                                 `- Toko: ${storeName}\n` +
                                 `- Barang: ${itemName} (${itemQty})\n\n` +
                                 `Jika barang sudah sampai, mohon upload bukti pengiriman melalui tautan berikut ya:\n` +
                                 `${proofLink}`;
        
        // Beri jeda baris pemisah antar tugas jika ada lebih dari satu nota pesanan yang dikirimkan bersamaan
        if (index < checkedSales.length - 1) {
            globalTaskDetailsText += `\n\n====================\n\n`;
        }
    });

    document.getElementById('modal-selected-count').innerText = globalSelectedIds.length;

    const modal = document.getElementById('bulkStatusModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeBulkModal() {
    const modal = document.getElementById('bulkStatusModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function submitBulkStatus() {
    if (globalSelectedIds.length === 0) return;

    const selectedRadio = document.querySelector('input[name="modal_target_status"]:checked');
    const statusResult = selectedRadio ? selectedRadio.value : 'dalam perjalanan';

    const submitBtn = document.getElementById('modal-submit-btn');
    submitBtn.setAttribute('disabled', 'disabled');
    submitBtn.innerText = "Memproses...";

    fetch('/sales/bulk-update-status', {  
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            sale_ids: globalSelectedIds,
            status: statusResult
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // JIKA TARGET KURIR BERBEDA (Klik Pilih Semua secara acak lintas kurir)
            // Hanya update dashboard, jangan trigger redirect tab WhatsApp API
            if (isMultiPartner) {
                window.location.reload();
                return;
            }

            // --- MODIFIKASI DISINI ---
            // Logika WA HANYA berjalan jika status diubah ke 'dalam perjalanan'
            if (statusResult === 'dalam perjalanan') {
                let nomorHp = globalPartnerContact.trim();
                if (nomorHp.startsWith('0')) {
                    nomorHp = '62' + nomorHp.slice(1);
                }

                // Merangkai isi keseluruhan pesan WhatsApp sesuai spesifikasi template
                let templateFullPesan = `Halo, ${globalPartnerName}.\n\n${globalTaskDetailsText}`;
                let waFormattedText = rawurlencode(templateFullPesan);

                if (nomorHp !== "" && nomorHp !== "-") {
                    window.open(`https://wa.me/${nomorHp}?text=${waFormattedText}`, '_blank');
                }
            }
            
            // Apapun statusnya, setelah proses selesai akan reload halaman
            window.location.reload();
            // -------------------------

        } else {
            alert('Gagal memperbarui status: ' + data.message);
            resetModalButton();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi server.');
        resetModalButton();
    });
}

function resetModalButton() {
    const submitBtn = document.getElementById('modal-submit-btn');
    submitBtn.removeAttribute('disabled');
    submitBtn.innerText = "Perbarui Status";
}

// Helper function untuk menyamakan sistem enkripsi URL bawaan PHP rawurlencode
function rawurlencode(str) {
    return encodeURIComponent(str)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A');
}





document.addEventListener('DOMContentLoaded', function () {
    const driverSelect = document.getElementById('driver_name');
    const driverPhoneInput = document.getElementById('driver_phone');

    if (driverSelect && driverPhoneInput) {
        driverSelect.addEventListener('change', function () {
            // Ambil option yang sedang aktif/dipilih
            const selectedOption = this.options[this.selectedIndex];
            // Ambil data-phone dari atribut option tersebut
            const phoneNumber = selectedOption.getAttribute('data-phone');

            if (phoneNumber) {
                driverPhoneInput.value = phoneNumber;
            } else {
                driverPhoneInput.value = ''; // Kosongkan jika memilih default opsi kembali
            }
        });
    }
});
</script>
@endpush
