<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /**
     * --- LOGIKA DASHBOARD (JAM & GRAFIK) ---
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Logika Jam Digital
        function updateJam() {
            const jamElement = document.getElementById('jam');
            if (jamElement) {
                const now = new Date();
                jamElement.innerText = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }
        if (document.getElementById('jam')) {
            setInterval(updateJam, 1000);
            updateJam();
        }

        // Logika Chart Doughnut
        const ctx = document.getElementById('schoolAttendanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                    datasets: [{
                        // Mengambil data langsung dari variabel yang dikirim Controller
                        data: [
                            {{ $hadir ?? 0 }},
                            {{ $izin ?? 0 }},
                            {{ $sakit ?? 0 }},
                            {{ $alpha ?? 0 }}
                        ],
                        backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });

    /**
     * --- LOGIKA HAPUS NILAI (PENILAIAN) ---
     */
    document.addEventListener('click', function(event) {
        const btn = event.target.closest('.btn-hapus-nilai');
        if (!btn) return;

        event.preventDefault();
        const idNilai = btn.getAttribute('data-id');

        if (confirm('Apakah Anda yakin ingin menghapus nilai ini?')) {
            fetch(`/evaluation-detail/${idNilai}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const row = document.getElementById(`row-nilai-${idNilai}`);
                        if (row) {
                            row.style.transition = '0.3s';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            setTimeout(() => row.remove(), 300);
                        }
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });

    /**
     * --- LOGIKA TOGGLE PASSWORD ---
     */
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

    /**
     * --- LOGIKA SWEETALERT HAPUS GURU ---
     */
    document.addEventListener('click', function(event) {
        const btnHapus = event.target.closest('.btn-hapus');
        if (btnHapus) {
            const id = btnHapus.getAttribute('data-id');
            const nama = btnHapus.getAttribute('data-nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data guru " + nama + " akan dihapus permanen!",
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
</script>
