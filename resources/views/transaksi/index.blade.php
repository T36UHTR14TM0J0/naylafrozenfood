@extends('layout.app')

@section('title', 'Transaksi Kasir')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
          <table class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th class="text-center">Nama Produk</th>
                      <th class="text-center">Harga</th>
                      <th class="text-center">Stok</th>
                      <th class="text-center">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($items as $item)
                  <tr>
                      <td class="text-center">{{ $item->nama }}</td>
                      <td class="text-center">{{ 'Rp. ' . number_format($item->harga_jual, 2) }}</td>
                      <td class="text-center">{{ $item->stokTotal->total_stok . ' ' . $item->satuan->nama }}</td>
                      <td class="text-center">
                          <button class="btn btn-success btn-sm add-to-cart" 
                                  data-id="{{ $item->id }}" 
                                  data-name="{{ $item->nama }}" 
                                  data-price="{{ $item->harga_jual }}"
                                  data-stock="{{ $item->stokTotal->total_stok }}">
                              <i class="bi bi-cart-plus"></i> Tambah
                          </button>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>

          <!-- Form Transaksi -->
          <form action="" method="POST">
              @csrf
              <!-- Daftar Produk yang dibeli -->
              <div class="mb-3">
                  <h5>Daftar Produk yang Dipilih</h5>
                  <table class="table table-striped table-bordered">
                      <thead>
                          <tr>
                              <th class="text-center">Produk</th>
                              <th class="text-center">Harga</th>
                              <th class="text-center">Jumlah</th>
                              <th class="text-center">Total</th>
                              <th class="text-center">Aksi</th>
                          </tr>
                      </thead>
                      <tbody id="product-list">
                          <!-- Daftar produk yang ditambahkan akan tampil disini -->
                      </tbody>
                  </table>
              </div>

              <!-- Diskon dan Metode Pembayaran -->
              <div class="row mb-3">
                  <div class="col-md-3">
                      <label for="discount" class="form-label">Diskon (%)</label>
                      <input type="number" id="discount" name="discount" class="form-control" placeholder="Masukkan diskon" min="0" max="100">
                  </div>
                  <div class="col-md-3">
                      <label for="total_amount" class="form-label">Total Pembayaran</label>
                      <input type="text" id="total_amount" name="total_amount" class="form-control" readonly>
                  </div>
                  <div class="col-md-3">
                    <label for="payment" class="form-label">Pembayaran (Rp.)</label>
                    <input type="number" id="payment" name="payment" class="form-control" placeholder="Masukkan pembayaran" required>
                </div>
                <div class="col-md-3">
                    <label for="change" class="form-label">Kembalian</label>
                    <input type="text" id="change" name="change" class="form-control" readonly>
                </div>
              </div>

              <!-- Tombol Pilihan Metode Pembayaran -->
              <div class="mt-4 text-right">
                  <button type="submit" class="btn btn-primary" name="payment_method" value="cash">
                      <i class="bi bi-cash-stack"></i> Pembayaran Tunai
                  </button>
                  <button type="submit" class="btn btn-info" name="payment_method" value="qris">
                      <i class="bi bi-qrcode"></i> Pembayaran QRIS
                  </button>
              </div>
          </form>
          
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.add-to-cart').forEach(button => {
  button.addEventListener('click', function() {
      const productId = this.dataset.id;
      const productName = this.dataset.name;
      const productPrice = parseFloat(this.dataset.price);
      const productStock = parseInt(this.dataset.stock);
      
      // Cek apakah produk sudah ada di daftar
      const existingProductRow = document.querySelector(`#product-${productId}`);
      
      if (existingProductRow) {
        // Jika produk sudah ada, tambah jumlahnya
        const quantityInput = existingProductRow.querySelector('[name="product_quantity[]"]');
        let quantity = parseInt(quantityInput.value);
        if (quantity < productStock) { // Periksa jika stok masih mencukupi
            quantityInput.value = quantity + 1; // Tambah jumlah
            existingProductRow.querySelector('[name="product_total[]"]').value = (productPrice * (quantity + 1)).toFixed(2); // Update total
        }
      } else {
          // Jika produk belum ada, tambahkan ke daftar
          const row = document.createElement('tr');
          row.id = `product-${productId}`;
          row.innerHTML = `
              <td>${productName}</td>
              <td><input type="number" name="product_price[]" class="form-control" value="${productPrice}" readonly></td>
              <td><input type="number" name="product_quantity[]" class="form-control" value="1" min="1" max="${productStock}" required></td>
              <td><input type="number" name="product_total[]" class="form-control" value="${productPrice}" readonly></td>
              <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i> Hapus</button></td>
          `;
          document.getElementById('product-list').appendChild(row);
      }

      // Update total setelah menambah produk
      updateTotal();
  });
});

// Fungsi untuk menghapus produk dari daftar transaksi
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('remove-product')) {
      event.target.closest('tr').remove();
      updateTotal();
  }
});

// Fungsi untuk memperbarui total transaksi
function updateTotal() {
  let totalAmount = 0;
  document.querySelectorAll('[name="product_total[]"]').forEach(function(input) {
      totalAmount += parseFloat(input.value) || 0;
  });

  let discount = parseFloat(document.getElementById('discount').value) || 0;
  totalAmount -= totalAmount * (discount / 100);

  document.getElementById('total_amount').value = totalAmount.toFixed(2);
  updateChange();
}

// Fungsi untuk menghitung kembalian
document.getElementById('payment').addEventListener('input', updateChange);

function updateChange() {
  let totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
  let payment = parseFloat(document.getElementById('payment').value) || 0;
  let change = document.getElementById('change');

  if (payment >= totalAmount) {
      change.value = (payment - totalAmount).toFixed(2);
  } else {
      change.value = '';
  }
}

// Update total saat jumlah produk berubah
document.addEventListener('input', function(event) {
  if (event.target.name === 'product_quantity[]') {
      let row = event.target.closest('tr');
      let price = parseFloat(row.querySelector('[name="product_price[]"]').value);
      let quantity = parseInt(event.target.value);
      let totalInput = row.querySelector('[name="product_total[]"]');

      if (!isNaN(price) && !isNaN(quantity)) {
          totalInput.value = price * quantity;
      }

      updateTotal();
  }

  // Update total jika diskon berubah
  if (event.target.id === 'discount') {
      updateTotal();
  }
});

</script>
@endpush
