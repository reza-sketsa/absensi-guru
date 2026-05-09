@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Header & Filter --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Kehadiran Siswa</h3>
                <p class="text-muted small mb-0">Laporan periode: <span
                        class="badge bg-primary-subtle text-primary fw-semibold">{{ ucfirst($filter) }}</span></p>
            </div>

            <div class="btn-group shadow-sm flex-wrap">
                <a href="?filter=today" class="btn btn-sm btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Hari
                    Ini</a>
                <a href="?filter=weekly"
                    class="btn btn-sm btn-outline-primary {{ $filter == 'weekly' ? 'active' : '' }}">Mingguan</a>
                <a href="?filter=monthly"
                    class="btn btn-sm btn-outline-primary {{ $filter == 'monthly' ? 'active' : '' }}">Bulanan</a>
                <a href="?filter=semester"
                    class="btn btn-sm btn-outline-primary {{ $filter == 'semester' ? 'active' : '' }}">Semester</a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Statistik Utama --}}
            <div class="col-lg-7">
                <div class="row g-3">
                    {{-- Hadir --}}
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted d-block mb-1 fw-medium">Hadir</small>
                                        <h2 class="fw-bold text-success mb-0">{{ $hadir ?? 0 }}</h2>
                                    </div>
                                    <div class="bg-success-subtle p-2 rounded-3">
                                        <i class="bi bi-people text-success fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Izin --}}
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted d-block mb-1 fw-medium">Izin</small>
                                        <h2 class="fw-bold text-info mb-0">{{ $izin ?? 0 }}</h2>
                                    </div>
                                    <div class="bg-info-subtle p-2 rounded-3">
                                        <i class="bi bi-envelope-paper text-info fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Sakit --}}
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted d-block mb-1 fw-medium">Sakit</small>
                                        <h2 class="fw-bold text-warning mb-0">{{ $sakit ?? 0 }}</h2>
                                    </div>
                                    <div class="bg-warning-subtle p-2 rounded-3">
                                        <i class="bi bi-bandaid text-warning fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Alpha --}}
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted d-block mb-1 fw-medium">Alpa</small>
                                        <h2 class="fw-bold text-danger mb-0">{{ $alpa ?? 0 }}</h2>
                                    </div>
                                    <div class="bg-danger-subtle p-2 rounded-3">
                                        <i class="bi bi-x-circle text-danger fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                        <h6 class="fw-bold mb-4 text-center">Proporsi Kehadiran</h6>
                        <div style="position: relative; height: 200px; width: 100%;">
                            <canvas id="schoolAttendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Rekap --}}
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Rekap Kehadiran Per Kelas</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light text-muted small fw-bold">
                        <tr>
                            <th class="text-start ps-4 py-3">NAMA KELAS</th>
                            <th>HADIR</th>
                            <th>IZIN</th>
                            <th>SAKIT</th>
                            <th>ALPA</th>
                            <th class="pe-4">PERSENTASE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapKelas as $rk)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark">{{ $rk->nama_kelas }}</td>
                                <td><span class="badge bg-success-subtle text-success">{{ $rk->hadir }}</span></td>
                                <td>{{ $rk->izin }}</td>
                                <td>{{ $rk->sakit }}</td>
                                <td><span class="badge bg-danger-subtle text-danger">{{ $rk->alpa }}</span></td>
                                <td class="pe-4">
                                    @php
                                        $total = $rk->hadir + $rk->izin + $rk->sakit + $rk->alpa;
                                        $persen = $total > 0 ? round(($rk->hadir / $total) * 100) : 0;
                                        $color =
                                            $persen >= 80 ? 'bg-success' : ($persen >= 50 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; max-width: 100px;">
                                            <div class="progress-bar {{ $color }}"
                                                style="width: {{ $persen }}%"></div>
                                        </div>
                                        <small class="fw-bold">{{ $persen }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-muted">Data kelas belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden mt-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Jadwal Hari Ini yang Belum Diabsen
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small fw-bold">
                        <tr>
                            <th class="ps-4 py-3">GURU</th>
                            <th class="py-3">MAPEL / KELAS</th>
                            <th class="py-3">JAM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalBelumAbsen as $j)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $j->nama_guru }}</td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $j->nama_mapel }}</div>
                                    <div class="text-muted small">{{ $j->tingkat }}-{{ $j->paralel }}</div>
                                </td>
                                <td class="text-nowrap small">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($j->jam_habis)->format('H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Semua jadwal hari ini sudah diabsen.
                                </td>
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
        // Logika Chart
        const ctx = document.getElementById('schoolAttendanceChart');
        const total = {{ ($hadir ?? 0) + ($izin ?? 0) + ($sakit ?? 0) + ($alpa ?? 0) }};

        if (ctx && total > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                    datasets: [{
                        data: [
                            {{ $hadir ?? 0 }},
                            {{ $izin ?? 0 }},
                            {{ $sakit ?? 0 }},
                            {{ $alpa ?? 0 }}
                        ],
                        backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        } else if (ctx) {
            ctx.parentElement.innerHTML = '<p class="text-muted text-center">Belum ada data kehadiran.</p>';
        }
    </script>
@endpush
