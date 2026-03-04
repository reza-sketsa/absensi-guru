<script>
    const chartData = @json($stats ?? null);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /**
     * --- LOGIKA HAPUS NILAI (PENILAIAN) ---
     * Menggunakan Event Delegation agar tombol di dalam tabel tetap responsif
     */
    console.log("Script Penilaian Active...");


    // Global listener untuk hapus nilai
    document.addEventListener('click', function(event) {
        const btn = event.target.closest('.btn-hapus-nilai');
        if (!btn) return;

        event.preventDefault();
        const idNilai = btn.getAttribute('data-id');

        if (confirm('Apakah Anda yakin ingin menghapus nilai ini?')) {
            fetch(`/evaluation-detail/${idNilai}`, {
                    method: 'DELETE',
                    headers: {
                        // Diambil dari meta tag di layouts/app.blade.php
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
                    alert('Terjadi kesalahan koneksi.');
                });
        }
    });
</script>
