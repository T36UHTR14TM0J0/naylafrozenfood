<script>
  // Fungsi dasar SweetAlert
  function showSuccessAlert(message) {
      Swal.fire({
          icon: 'success',
          title: message,
          timer: 3000,
          toast: true,
          position: 'top-end',
          showConfirmButton: false
      });
  }
  
  function showErrorAlert(message) {
      Swal.fire({
          icon: 'error',
          title: message,
          timer: 3000,
          toast: true,
          position: 'top-end',
          showConfirmButton: false
      });
  }
  </script>
  
  @if(session('success'))
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          showSuccessAlert("{{ session('success') }}");
      });
  </script>
  @endif
  
  @if(session('error'))
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          showErrorAlert("{{ session('error') }}");
      });
  </script>
  @endif