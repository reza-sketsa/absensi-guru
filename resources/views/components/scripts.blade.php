<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jam Digital
        function updateJam() {
            const jamElement = document.getElementById('jam');
            if (jamElement) {
                const now = new Date();
                jamElement.innerText = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });
            }
        }
        if (document.getElementById('jam')) {
            setInterval(updateJam, 1000);
            updateJam();
        }

        // Toggle Password
        const toggleBtn = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#passwordField');
        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                const eyeIcon = document.querySelector('#eyeIcon');
                if (eyeIcon) {
                    eyeIcon.classList.toggle('bi-eye');
                    eyeIcon.classList.toggle('bi-eye-slash');
                }
            });
        }

        // SweetAlert Hapus Guru
        document.addEventListener('click', function(event) {
            const btnHapus = event.target.closest('.btn-hapus');
            if (btnHapus) {
                const id   = btnHapus.getAttribute('data-id');
                const nama = btnHapus.getAttribute('data-nama');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data guru ' + nama + ' akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            }
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    });
</script>
