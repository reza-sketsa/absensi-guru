<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jam Digital
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

        // Chart Dashboard Guru
        const absensiChartCanvas = document.getElementById('absensiChart');
        if (absensiChartCanvas) {
            const chartData = JSON.parse(absensiChartCanvas.dataset.stats);
            const total = chartData.hadir + chartData.izin + chartData.sakit + chartData.alpa;

            if (total > 0) {
                new Chart(absensiChartCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                        datasets: [{
                            data: [chartData.hadir, chartData.izin, chartData.sakit, chartData
                                .alpa
                            ],
                            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                            borderWidth: 2,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            } else {
                absensiChartCanvas.parentElement.innerHTML =
                    '<p class="text-muted text-center py-5">Belum ada data kehadiran untuk periode ini.</p>';
            }
        }

        // SweetAlert Hapus Guru
        document.addEventListener('click', function(event) {
            const btnHapus = event.target.closest('.btn-hapus');
            if (btnHapus) {
                const id = btnHapus.getAttribute('data-id');
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
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
    });

    const searchGuru = document.getElementById('searchGuru');
    if (searchGuru) {
        searchGuru.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(function(row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }

    // Search Siswa
    const searchSiswa = document.getElementById('searchSiswa');
    if (searchSiswa) {
        searchSiswa.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(function(row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }

    const searchSiswaGuru = document.getElementById('searchSiswaGuru');
    if (searchSiswaGuru) {
        searchSiswaGuru.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(function(row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }

    // =====================
    // IMPORT SISWA
    // =====================
    const formImport = document.querySelector('#modalImportSiswa form');
    if (formImport) {
        formImport.addEventListener('submit', function() {
            const btn = formImport.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengimport...';
        });
    }
</script>
