import Swal from "sweetalert2";

window.Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

window.showSuccessAlert = (message) => {
    Toast.fire({
        icon: "success",
        title: message,
    });
};

window.showErrorAlert = (message) => {
    Toast.fire({
        icon: "error",
        title: message,
    });
};

window.showConfirmDialog = (title, text, confirmButtonText, callback) => {
    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

window.Swal = Swal;
