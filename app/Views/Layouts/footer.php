<script>
    // Global SweetAlert2 Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    // Check for URL parameters for Success/Error messages (PHP Redirection)
    const urlParams = new URLSearchParams(window.location.search);
    const successMsg = urlParams.get('success');
    const errorMsg = urlParams.get('error');

    if (successMsg) {
        Toast.fire({
            icon: 'success',
            title: successMsg
        });
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: errorMsg,
            confirmButtonColor: '#ef4444', // Tailwind red-500
        });
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Global Confirm Action with SweetAlert2
    window.confirmAction = function(event, url, title, message, icon, confirmBtnText, confirmBtnColor) {
        event.preventDefault();
        
        Swal.fire({
            title: title || 'Apakah Anda yakin?',
            text: message || "Tindakan ini tidak dapat dibatalkan!",
            icon: icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor || '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmBtnText || 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
        
        return false;
    };
</script>
</body>
</html>