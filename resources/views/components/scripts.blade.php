<script>
    // Console log ini untuk memastikan file ini berhasil di-load
    console.log("Script Penilaian Loaded...");

    document.addEventListener('click', function(event) {
        // Cari element tombol
        const btn = event.target.closest('.btn-hapus-nilai');

        if (btn) {
            // Stop link jika itu tag <a>
            event.preventDefault();

            const idNilai = btn.getAttribute('data-id');
            console.log("Tombol diklik, ID:", idNilai);

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
                    .then(response => {
                        if (!response.ok) throw new Error('Server returned error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.status) {
                            const row = document.getElementById(`row-nilai-${idNilai}`);
                            if (row) {
                                row.style.opacity = '0';
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
        }
    });
</script>

@stack('scripts')
