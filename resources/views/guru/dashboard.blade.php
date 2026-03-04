@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <h6 class="fw-bold mb-3 mb-md-0">Statistik Kehadiran Siswa</h6>

                    {{-- Filter Navigation --}}
                    <div class="btn-group btn-group-sm shadow-sm">
                        <a href="?filter=all"
                            class="btn btn-outline-primary {{ ($filter ?? 'all') == 'all' ? 'active' : '' }}">Semua</a>
                        <a href="?filter=weekly"
                            class="btn btn-outline-primary {{ ($filter ?? '') == 'weekly' ? 'active' : '' }}">Mingguan</a>
                        <a href="?filter=monthly"
                            class="btn btn-outline-primary {{ ($filter ?? '') == 'monthly' ? 'active' : '' }}">Bulanan</a>
                        <a href="?filter=semester"
                            class="btn btn-outline-primary {{ ($filter ?? '') == 'semester' ? 'active' : '' }}">Semester</a>
                    </div>
                </div>

                <div style="position: relative; height: 250px;">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Siswa dengan Ketidakhadiran Tertinggi</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th class="text-center">Alpa</th>
                                        <th class="text-center">Total (A+S+I)</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowAttendanceStudents as $item)
                                        <tr>
                                            <td class="fw-bold">
                                                {{ $item->student->nama }}
                                                <div class="small text-muted fw-normal">
                                                    {{ $item->student->classroom->nama_kelas ?? '' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $item->total_alpa }}</span>
                                            </td>
                                            <td class="text-center text-muted small">{{ $item->total_tidak_hadir }} Hari
                                            </td>
                                            <td>
                                                <a href="{{ route('guru.siswa.detail', $item->student_id) }}"
                                                    class="btn btn-sm btn-outline-primary shadow-sm">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data
                                                ketidakhadiran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat atau Info Tambahan bisa diletakkan di sini --}}
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Ambil data dari PHP
            const chartData = @json($stats);

            document.addEventListener('DOMContentLoaded', function() {
                const chartCanvas = document.getElementById('absensiChart');

                if (chartCanvas && chartData) {
                    new Chart(chartCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                            datasets: [{
                                data: [
                                    chartData.hadir || 0,
                                    chartData.izin || 0,
                                    chartData.sakit || 0,
                                    chartData.alpa || 0
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
                }
            });
        </script>

        {{-- Panggil script global lainnya seperti hapus nilai jika perlu --}}
        @include('components.scripts')
    @endpush
@endsection
