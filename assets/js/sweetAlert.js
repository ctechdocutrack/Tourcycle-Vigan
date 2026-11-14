
// Reusable SweetAlert Functions
const AppAlert = {

    success: function (title, message = "") {
        return Swal.fire({
            icon: "success",
            title: title,
            text: message,
            confirmButtonText: "OK",
            confirmButtonColor: "#198754"
        });
    },

    error: function (title, message = "") {
        return Swal.fire({
            icon: "error",
            title: title,
            text: message,
            confirmButtonText: "OK",
            confirmButtonColor: "#dc3545"
        });
    },

    warning: function (title, message = "") {
        return Swal.fire({
            icon: "warning",
            title: title,
            text: message,
            confirmButtonText: "OK",
            confirmButtonColor: "#ffc107"
        });
    },

    info: function (title, message = "") {
        return Swal.fire({
            icon: "info",
            title: title,
            text: message,
            confirmButtonText: "OK",
            confirmButtonColor: "#0d6efd"
        });
    },

    confirm: function (title, message = "") {
        return Swal.fire({
            icon: "question",
            title: title,
            text: message,
            showCancelButton: true,
            confirmButtonText: "Yes, continue",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d"
        });
    }
};
