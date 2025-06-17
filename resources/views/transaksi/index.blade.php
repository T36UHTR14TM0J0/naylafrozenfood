@extends('layout.app')

@section('title', 'Transaksi Kasir')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12 shadow shadow-sm p-2">
            <!-- ==================== SEARCH FORM ==================== -->
            <div class="mb-3">
                <form action="{{ route('transaksi.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ==================== PRODUCT TABLE ==================== -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="bg-secondary">
                        <th class="text-center text-light" width="30%">Nama Item</th>
                        <th class="text-center text-light" width="30%">Harga</th>
                        <th class="text-center text-light" width="30%">Stok</th>
                        <th class="text-center text-light" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="text-center">{{ $item->nama }}</td>
                        <td class="text-center">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->stokTotal && $item->stokTotal->total_stok !== NULL)
                                {{ $item->stokTotal->total_stok . ' ' . ($item->satuan ? $item->satuan->nama : '') }}
                            @else
                                <span class="text-danger">Stok Habis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <?php
                            dd($item->stokTotal);
                            ?>
                            @if($item->stokTotal && $item->stokTotal->total_stok !== NULL || $item->stokTotal && $item->stokTotal->total_stok !== '0' )
                                <button class="btn btn-success btn-sm add-to-cart" 
                                        data-id="{{ $item->id }}" 
                                        data-name="{{ $item->nama }}" 
                                        data-price="{{ $item->harga_jual }}"
                                        data-stock="{{ $item->stokTotal->total_stok }}">
                                    <i class="bi bi-cart-plus"></i> Tambah
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-cart-x"></i> Stok Habis
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada produk ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION SECTION -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} entri
                </div>
                <div>
                    <!-- Pagination Links -->
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>

            <!-- ==================== TRANSACTION FORM ==================== -->
            <form id="form-transaksi" action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <!-- Hidden fields -->
                <input type="hidden" name="total_amount" id="total_amount_hidden">
                <input type="hidden" name="payment" id="payment_hidden">
                <input type="hidden" name="change" id="change_hidden">
                
                <!-- Selected Items Table -->
                <div class="mb-3">
                    <h5>Daftar Item yang Dipilih</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-center text-light" width="30%">Item</th>
                                <th class="text-center text-light" width="20%">Harga</th>
                                <th class="text-center text-light" width="10%">Jumlah</th>
                                <th class="text-center text-light" width="30%">Total</th>
                                <th class="text-center text-light" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="item-list">
                            <!-- Selected items will appear here -->
                        </tbody>
                    </table>
                </div>

                <!-- Payment Details -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="discount" class="form-label">Diskon (%)</label>
                        <input type="number" id="discount" name="discount" class="form-control" placeholder="Masukkan diskon" min="0" max="100" value="0">
                    </div>
                    <div class="col-md-3">
                        <label for="total_amount_display" class="form-label">Total Pembayaran</label>
                        <input type="text" id="total_amount_display" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="payment_display" class="form-label">Pembayaran (Rp)</label>
                        <input type="text" id="payment_display" class="form-control" placeholder="Masukkan pembayaran" required>
                    </div>
                    <div class="col-md-3">
                        <label for="change_display" class="form-label">Kembalian</label>
                        <input type="text" id="change_display" class="form-control" readonly>
                    </div>
                </div>

                <!-- Payment Method Buttons -->
                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-primary" id="btn-cash" data-method="cash">
                        <i class="bi bi-cash-stack"></i> Pembayaran Tunai
                    </button>
                    <button type="button" class="btn btn-info" id="btn-online" data-method="online">
                        <i class="bi bi-qrcode"></i> Pembayaran Online
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== RECEIPT MODAL ==================== -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Struk Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="receiptContent">
                <!-- Receipt content will be filled by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    // ==================== UTILITY FUNCTIONS ====================
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseRupiah(rupiah) {
        return parseInt(rupiah.replace(/[^0-9]/g, '')) || 0;
    }

    // ==================== EVENT LISTENERS ====================
    // Payment input formatting
    document.getElementById('payment_display').addEventListener('keyup', function(e) {
        let value = this.value.replace(/\./g, '');
        if (!/^\d*$/.test(value)) {
            value = value.replace(/[^\d]/g, '');
        }
        this.value = formatRupiah(value);
        document.getElementById('payment_hidden').value = value;
        updateChange();
    });

    // Add to cart buttons - dengan pengecekan stok tambahan
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemPrice = parseFloat(this.dataset.price);
            const itemStock = parseInt(this.dataset.stock);
            
            // Validasi stok
            if (itemStock <= 0) {
                Swal.fire('Error', 'Stok produk habis', 'error');
                return;
            }
            
            const existingProductRow = document.querySelector(`#item-${itemId}`);
            
            if (existingProductRow) {
                const quantityInput = existingProductRow.querySelector('[name="item_quantity[]"]');
                let quantity = parseInt(quantityInput.value);
                
                // Validasi stok sebelum menambah quantity
                if (quantity >= itemStock) {
                    Swal.fire('Error', 'Jumlah melebihi stok yang tersedia', 'error');
                    return;
                }
                
                quantityInput.value = quantity + 1;
                const subtotal = itemPrice * (quantity + 1);
                existingProductRow.querySelector('[name="item_total[]"]').value = subtotal;
                existingProductRow.querySelector('.subtotal-display').textContent = `Rp ${formatRupiah(subtotal)}`;
            } else {
                const row = document.createElement('tr');
                row.id = `item-${itemId}`;
                row.innerHTML = `
                    <td>${itemName}
                        <input type="hidden" name="item_id[]" value="${itemId}">
                    </td>
                    <td>
                        <input type="hidden" name="item_price[]" value="${itemPrice}">
                        Rp ${formatRupiah(itemPrice)}
                    </td>
                    <td><input type="number" name="item_quantity[]" class="form-control" value="1" min="1" max="${itemStock}" required></td>
                    <td>
                        <input type="hidden" name="item_total[]" value="${itemPrice}">
                        <span class="subtotal-display">Rp ${formatRupiah(itemPrice)}</span>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-trash"></i> Hapus</button></td>
                `;
                document.getElementById('item-list').appendChild(row);
            }
            updateTotal();
        });
    });

    // Quantity input validation
    document.addEventListener('change', function(event) {
        if (event.target.name === 'item_quantity[]') {
            const maxStock = parseInt(event.target.max);
            const enteredQuantity = parseInt(event.target.value);
            
            if (enteredQuantity > maxStock) {
                Swal.fire('Error', 'Jumlah melebihi stok yang tersedia', 'error');
                event.target.value = maxStock;
            }
            
            let row = event.target.closest('tr');
            const price = parseFloat(row.querySelector('[name="item_price[]"]').value);
            let quantity = parseInt(event.target.value);
            let totalInput = row.querySelector('[name="item_total[]"]');
            let subtotalDisplay = row.querySelector('.subtotal-display');

            if (!isNaN(price) && !isNaN(quantity)) {
                const subtotal = price * quantity;
                totalInput.value = subtotal;
                subtotalDisplay.textContent = `Rp ${formatRupiah(subtotal)}`;
            }
            updateTotal();
        }
    });

    // Remove item button
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-item')) {
            event.target.closest('tr').remove();
            updateTotal();
        }
    });

    // Quantity and discount changes
    document.addEventListener('input', function(event) {
        if (event.target.name === 'item_quantity[]') {
            let row = event.target.closest('tr');
            const price = parseFloat(row.querySelector('[name="item_price[]"]').value);
            let quantity = parseInt(event.target.value);
            let totalInput = row.querySelector('[name="item_total[]"]');
            let subtotalDisplay = row.querySelector('.subtotal-display');

            if (!isNaN(price) && !isNaN(quantity)) {
                const subtotal = price * quantity;
                totalInput.value = subtotal;
                subtotalDisplay.textContent = `Rp ${formatRupiah(subtotal)}`;
            }
            updateTotal();
        }

        if (event.target.id === 'discount') {
            updateTotal();
        }
    });

    // Payment method buttons
    document.getElementById('btn-cash').addEventListener('click', function() {
        processPayment('cash');
    });

    document.getElementById('btn-online').addEventListener('click', function() {
        processPayment('online');
    });

    // ==================== BUSINESS LOGIC FUNCTIONS ====================
    function updateTotal() {
        let totalAmount = 0;
        document.querySelectorAll('[name="item_total[]"]').forEach(function(input) {
            totalAmount += parseFloat(input.value);
        });

        let discount = parseFloat(document.getElementById('discount').value) || 0;
        totalAmount -= totalAmount * (discount / 100);

        document.getElementById('total_amount_display').value = `Rp ${formatRupiah(totalAmount)}`;
        document.getElementById('total_amount_hidden').value = totalAmount;
        updateChange();
    }

    function updateChange() {
        const totalAmount = parseFloat(document.getElementById('total_amount_hidden').value) || 0;
        const payment = parseFloat(document.getElementById('payment_hidden').value) || 0;
        const change = payment - totalAmount;
        
        document.getElementById('change_display').value = change >= 0 ? `Rp ${formatRupiah(change)}` : '';
        document.getElementById('change_hidden').value = change >= 0 ? change : 0;
    }

    
function processPayment(paymentMethod) {
    // Validation
    if (document.querySelectorAll('[name="item_id[]"]').length === 0) {
        Swal.fire('Error', 'Silahkan tambahkan produk terlebih dahulu', 'error');
        return;
    }

    const totalBayar = parseFloat(document.getElementById('payment_hidden').value) || 0;
    const totalTransaksi = parseFloat(document.getElementById('total_amount_hidden').value) || 0;
    
    // Check if payment is sufficient for cash method
    if (paymentMethod === 'cash' && totalBayar < totalTransaksi) {
        Swal.fire('Error', 'Pembayaran kurang dari total transaksi', 'error');
        return;
    }

    // Prepare form data
    const formData = new FormData(document.getElementById('form-transaksi'));
    formData.append('metode_pembayaran', paymentMethod);
    formData.append('total_transaksi', totalTransaksi);
    formData.append('total_bayar', totalBayar);
    formData.append('kembalian', Math.max(0, totalBayar - totalTransaksi));

    // Show loading
    const submitBtn = document.getElementById(`btn-${paymentMethod}`);
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Memproses...';
    submitBtn.disabled = true;

    // Get CSRF token from meta tag
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : '';
    
    // Handle Online payment method
    if (paymentMethod === 'online') {
        fetch("{{ route('transaksi.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showOnline(data.snap_token); // Show the Online payment
            } else {
                throw new Error(data.message || 'Transaksi gagal');
            }
        })
        .catch(error => {
            Swal.fire('Error', error.message, 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    } else {
        // Handle other payment methods (Cash, for example)
        fetch('{{ route("transaksi.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // console.log(data);
                showReceipt({
                    transaction_id: data.faktur,
                    items: Array.from(document.querySelectorAll('[name="item_id[]"]')).map((item, index) => ({
                        name: item.closest('tr').querySelector('td').textContent.trim(),
                        quantity: document.querySelectorAll('[name="item_quantity[]"]')[index].value,
                        subtotal: parseFloat(document.querySelectorAll('[name="item_total[]"]')[index].value)
                    })),
                    subtotal: Array.from(document.querySelectorAll('[name="item_total[]"]')).reduce((sum, input) => sum + parseFloat(input.value), 0),
                    discount: parseFloat(document.getElementById('discount').value) || 0,
                    total: totalTransaksi,
                    payment: totalBayar,
                    change: parseFloat(document.getElementById('change_hidden').value),
                    payment_method: paymentMethod,
                    cashier: '{{ auth()->user()->name }}'
                });
                
                // Reset form
                document.getElementById('form-transaksi').reset();
                document.getElementById('item-list').innerHTML = '';
                document.getElementById('total_amount_display').value = '';
                document.getElementById('payment_display').value = '';
                document.getElementById('change_display').value = '';
            } else {
                throw new Error(data.message || 'Transaksi gagal');
            }
        })
        .catch(error => {
            Swal.fire('Error', error.message, 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
}


    function showOnline(snapToken) {
        console.log(snapToken);
        const totalBayar = parseFloat(document.getElementById('payment_hidden').value) || 0;
        const totalTransaksi = parseFloat(document.getElementById('total_amount_hidden').value) || 0;
        // Call Midtrans Snap API to show the QR code
        snap.pay(snapToken, {
            onSuccess: function(result) {
                // Tampilkan notifikasi pembayaran berhasil
                Swal.fire({
                    title: 'Pembayaran Berhasil',
                    text: 'Pembayaran menggunakan Online berhasil',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((resultSwal) => {
                    // Setelah notifikasi di-OK, tampilkan struk
                    if (resultSwal.isConfirmed) {
                        showReceipt({
                            transaction_id: result.order_id,
                            items: Array.from(document.querySelectorAll('[name="item_id[]"]')).map((item, index) => ({
                                name: item.closest('tr').querySelector('td').textContent.trim(),
                                quantity: document.querySelectorAll('[name="item_quantity[]"]')[index].value,
                                subtotal: parseFloat(document.querySelectorAll('[name="item_total[]"]')[index].value)
                            })),
                            subtotal: Array.from(document.querySelectorAll('[name="item_total[]"]')).reduce((sum, input) => sum + parseFloat(input.value), 0),
                            discount: parseFloat(document.getElementById('discount').value) || 0,
                            total: totalTransaksi,
                            payment: totalBayar,
                            change: parseFloat(document.getElementById('change_hidden').value),
                            payment_method: 'Online',
                            cashier: '{{ auth()->user()->name }}'
                        });

                        document.getElementById('form-transaksi').reset();
                        document.getElementById('item-list').innerHTML = '';
                        document.getElementById('total_amount_display').value = '';
                        document.getElementById('payment_display').value = '';
                        document.getElementById('change_display').value = '';
                    }
                });
            },
            onPending: function(result) {
                Swal.fire('Pembayaran Menunggu', 'Pembayaran Anda sedang diproses', 'info');
            },
            onError: function(result) {
                Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran Online', 'error');
            }
        });
    }


    function showReceipt(transactionData) {
        const now = new Date();
        const formattedDate = now.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        let receiptHTML = `
            <div class="receipt text-center">
                <h4>Nayla Frozen Food</h4>
                <p>Jl. Raya Kemiri, Kemiri, Kec. Jayakerta, Karawang, Jawa Barat 41352</p>
                <p>Telp: 08123456789</p>
                <hr>
                <p>${formattedDate}</p>
                <p>ID: ${transactionData.transaction_id}</p>
                <hr>
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th class="text-start">Item</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        transactionData.items.forEach(item => {
            receiptHTML += `
                <tr>
                    <td class="text-start">${item.name}</td>
                    <td class="text-end">${item.quantity}</td>
                    <td class="text-end">Rp ${formatRupiah(item.subtotal)}</td>
                </tr>
            `;
        });
        
        receiptHTML += `
                    </tbody>
                </table>
                <hr>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-start">Subtotal:</td>
                        <td class="text-end">Rp ${formatRupiah(transactionData.subtotal)}</td>
                    </tr>
                    <tr>
                        <td class="text-start">Diskon:</td>
                        <td class="text-end">${transactionData.discount}%</td>
                    </tr>
                    <tr>
                        <td class="text-start">Total:</td>
                        <td class="text-end">Rp ${formatRupiah(transactionData.total)}</td>
                    </tr>
                    <tr>
                        <td class="text-start">Bayar:</td>
                        <td class="text-end">Rp ${formatRupiah(transactionData.payment)}</td>
                    </tr>
                    <tr>
                        <td class="text-start">Kembali:</td>
                        <td class="text-end">Rp ${formatRupiah(transactionData.change)}</td>
                    </tr>
                </table>
                <hr>
                <p>Metode: ${transactionData.payment_method === 'cash' ? 'Tunai' : 'Online'}</p>
                <p>Kasir: ${transactionData.cashier}</p>
                <hr>
                <p>Terima kasih telah berbelanja</p>
                <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
            </div>
        `;
        
        document.getElementById('receiptContent').innerHTML = receiptHTML;
        new bootstrap.Modal(document.getElementById('receiptModal')).show();
    }

    function printReceipt() {
        const receiptContent = document.getElementById('receiptContent').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = receiptContent;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }

    // ==================== STYLES ====================
    const style = document.createElement('style');
    style.innerHTML = `
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush