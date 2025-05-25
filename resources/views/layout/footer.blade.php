<footer class="pc-footer">
  <div class="footer-wrapper container-fluid">
    <div class="row">
      <!-- Kolom pertama untuk informasi footer -->
      <div class="col-sm my-1">
        <p class="m-0">
          Mantis &#9829; crafted by Team <a href="" target="_blank">Codedthemes</a>  <!-- Menampilkan informasi pembuat -->
        </p>
      </div>
      <!-- Kolom kedua untuk link footer -->
      <div class="col-auto my-1">
        <ul class="list-inline footer-link mb-0">
          <li class="list-inline-item"><a href="../index.html">Home</a></li>  <!-- Link ke halaman utama -->
        </ul>
      </div>
    </div>
  </div>
</footer>

<!-- Script untuk menghubungkan file JavaScript yang dibutuhkan -->
<script src="{{ asset('js/plugins/popper.min.js')}}"></script>  <!-- Popper.js untuk komponen yang membutuhkan positioning -->
<script src="{{ asset('js/plugins/simplebar.min.js')}}"></script>  <!-- Simplebar untuk custom scrollbar -->
<script src="{{ asset('js/plugins/bootstrap.min.js')}}"></script>  <!-- Bootstrap JS untuk komponen seperti dropdown, modal, dll -->
<script src="{{ asset('js/fonts/custom-font.js')}}"></script>  <!-- Custom fonts JS -->
<script src="{{ asset('js/pcoded.js')}}"></script>  <!-- Script utama untuk template -->
<script src="{{ asset('js/plugins/feather.min.js')}}"></script>  <!-- Feather Icons JS -->
<script src="{{ asset('js/plugins/jquery.min.js')}}"></script>  <!-- jQuery untuk manipulasi DOM -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  <!-- SweetAlert2 untuk notifikasi interaktif -->

<!-- Menambahkan JS Select2 untuk elemen select yang lebih interaktif -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- jQuery versi 3.6 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>  <!-- Select2 untuk elemen select yang lebih baik -->

<!-- Midtrans Snap.js untuk integrasi pembayaran -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>  <!-- Snap.js untuk Midtrans -->

<!-- Menyertakan JS file untuk alert -->
@include('js_default.js_alert')

<!-- Menambahkan stack untuk script tambahan yang mungkin diperlukan di halaman lain -->
@stack('scripts')

<!-- Pengaturan layout -->
<script>layout_change('light');</script>  <!-- Mengubah tema layout menjadi terang -->

<script>change_box_container('false');</script>  <!-- Mengubah pengaturan container box menjadi false -->

<script>layout_rtl_change('false');</script>  <!-- Mengubah pengaturan layout RTL menjadi false -->

<script>preset_change("preset-1");</script>  <!-- Mengubah preset tema menjadi preset-1 -->

<script>font_change("Public-Sans");</script>  <!-- Mengubah font yang digunakan menjadi Public-Sans -->

</body>
<!-- [Body] selesai -->

</html>
