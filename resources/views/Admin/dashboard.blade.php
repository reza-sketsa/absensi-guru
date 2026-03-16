@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Kehadiran Siswa Satu Sekolah</h3>
                <p class="text-muted mb-0">Laporan periode: <strong>{{ ucfirst($filter) }}</strong></p>
            </div>

            <div class="btn-group shadow-sm mt-3 mt-md-0">
                <a href="?filter=today" class="btn btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Hari Ini</a>
                <a href="?filter=weekly"
                    class="btn btn-outline-primary {{ $filter == 'weekly' ? 'active' : '' }}">Mingguan</a>
                <a href="?filter=monthly"
                    class="btn btn-outline-primary {{ $filter == 'monthly' ? 'active' : '' }}">Bulanan</a>
                <a href="?filter=semester"
                    class="btn btn-outline-primary {{ $filter == 'semester' ? 'active' : '' }}">Semester</a>
            </div>
        </div>

        <div class="text-end d-none d-md-block">
            <h4 id="jam" class="fw-bold mb-0 text-primary"></h4>
            <span class="badge bg-light text-dark border">{{ date('l, d M Y') }}</span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-7">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Hadir</small>
                                    <h2 class="fw-bold text-success mb-0">{{ $hadir ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-people text-success fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Izin</small>
                                    <h2 class="fw-bold text-info mb-0">{{ $izin ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-envelope-paper text-info fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Sakit</small>
                                    <h2 class="fw-bold text-warning mb-0">{{ $sakit ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-bandaid text-warning fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block mb-1">Total Alpha</small>
                                    <h2 class="fw-bold text-danger mb-0">{{ $alpha ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-x-circle text-danger fs-1 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <h6 class="fw-bold mb-3 text-center">Proporsi Kehadiran Seluruh Siswa</h6>
                    <div style="position: relative; height: 220px; width: 100%;">
                        <canvas id="schoolAttendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Rekap Kehadiran Per Kelas</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="text-start ps-4">Nama Kelas</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpha</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapKelas as $rk)
                        <tr>
                            <td class="text-start ps-4 fw-bold">{{ $rk->nama_kelas }}</td>
                            <td><span class="text-success">{{ $rk->hadir }}</span></td>
                            <td>{{ $rk->izin }}</td>
                            <td>{{ $rk->sakit }}</td>
                            <td><span class="text-danger">{{ $rk->alpha }}</span></td>
                            <td>
                                @php
                                    $total = $rk->hadir + $rk->izin + $rk->sakit + $rk->alpha;
                                    $persen = $total > 0 ? round(($rk->hadir / $total) * 100) : 0;
                                @endphp
                                <div class="progress" style="height: 8px; width: 80px; margin: 0 auto;">
                                    <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                                </div>
                                <small class="text-muted">{{ $persen }}%</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-muted">Data kelas belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    @include('components.scripts')

    <script>
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
        setInterval(updateJam, 1000);
        updateJam();

        // Logika Chart (Mencegah Error jika data kosong)
        const ctx = document.getElementById('schoolAttendanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                    datasets: [{
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
    </script>
@endpush
