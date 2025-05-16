<footer class="pc-footer">
  <div class="footer-wrapper container-fluid">
    <div class="row">
      <div class="col-sm my-1">
        <p class="m-0"
          >Mantis &#9829; crafted by Team <a href="" target="_blank">Codedthemes</a></p
        >
      </div>
      <div class="col-auto my-1">
        <ul class="list-inline footer-link mb-0">
          <li class="list-inline-item"><a href="../index.html">Home</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer> <!-- Required Js -->
<script src="{{ asset('js/plugins/popper.min.js')}}"></script>
<script src="{{ asset('js/plugins/simplebar.min.js')}}"></script>
<script src="{{ asset('js/plugins/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/fonts/custom-font.js')}}"></script>
<script src="{{ asset('js/pcoded.js')}}"></script>
<script src="{{ asset('js/plugins/feather.min.js')}}"></script>
<script src="{{ asset('js/plugins/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Menambahkan JS Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

@include('js_default.js_alert')
@stack('scripts')



<script>layout_change('light');</script>




<script>change_box_container('false');</script>



<script>layout_rtl_change('false');</script>


<script>preset_change("preset-1");</script>


<script>font_change("Public-Sans");</script>




</body>
<!-- [Body] end -->

</html>