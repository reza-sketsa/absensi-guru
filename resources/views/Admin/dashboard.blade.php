@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Header & Filter --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard Admin</h3>
                <p class="text-muted small mb-0">
                    {{ $totalGuruAktif }} dari {{ $totalGuru }} guru aktif mengabsen
                    <span class="badge bg-primary-subtle text-primary fw-semibold ms-1">{{ ucfirst($filter) }}</span>
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{-- Dropdown TA --}}
                <select class="form-select form-select-sm w-auto shadow-sm"
                    onchange="window.location.href='?filter={{ $filter }}&academic_year_id='+this.value">
                    @foreach ($allYears as $y)
                        <option value="{{ $y->id }}" {{ $activeYear && $activeYear->id == $y->id ? 'selected' : '' }}>
                            {{ $y->tahun }} - {{ $y->semester }}
                            {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter periode --}}
                <div class="btn-group shadow-sm">
                    <a href="?filter=today&academic_year_id={{ $activeYear?->id }}"
                        class="btn btn-sm btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Hari Ini</a>
                    <a href="?filter=weekly&academic_year_id={{ $activeYear?->id }}"
                        class="btn btn-sm btn-outline-primary {{ $filter == 'weekly' ? 'active' : '' }}">Mingguan</a>
                    <a href="?filter=monthly&academic_year_id={{ $activeYear?->id }}"
                        class="btn btn-sm btn-outline-primary {{ $filter == 'monthly' ? 'active' : '' }}">Bulanan</a>
                    <a href="?filter=semester&academic_year_id={{ $activeYear?->id }}"
                        class="btn btn-sm btn-outline-primary {{ $filter == 'semester' ? 'active' : '' }}">Semester</a>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 fw-medium">Total Guru</small>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalGuru }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 fw-medium">Guru Aktif</small>
                        <h2 class="fw-bold text-success mb-0">{{ $totalGuruAktif }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 fw-medium">Belum Aktif</small>
                        <h2 class="fw-bold text-danger mb-0">{{ $totalGuru - $totalGuruAktif }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 fw-medium">Belum Absen Hari Ini</small>
                        <h2 class="fw-bold text-warning mb-0">{{ $jadwalBelumAbsen->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Trend Harian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Trend Guru Mengabsen per Hari</h6>
                <div style="position: relative; height: 250px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Keaktifan Guru --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-person-check text-primary me-2"></i>Keaktifan Guru Mengabsen
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small fw-bold">
                        <tr>
                            <th class="ps-4 py-3">NAMA GURU</th>
                            <th class="text-center py-3">TOTAL JADWAL</th>
                            <th class="text-center py-3">SUDAH ABSEN</th>
                            <th class="pe-4 py-3">KEAKTIFAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keaktifanGuru as $guru)
                            @php
                                $color =
                                    $guru->persentase >= 80
                                        ? 'bg-success'
                                        : ($guru->persentase >= 50
                                            ? 'bg-warning'
                                            : 'bg-danger');
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold">{{ $guru->nama_guru }}</td>
                                <td class="text-center text-muted">{{ $guru->total_jadwal }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $guru->total_absen > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ $guru->total_absen }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar {{ $color }}"
                                                style="width: {{ $guru->persentase }}%"></div>
                                        </div>
                                        <small class="fw-bold text-nowrap">{{ $guru->persentase }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Jadwal Belum Diabsen Hari Ini --}}
        <div class="card border-0 shadow-sm overflow-hidden">
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
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($chartLabels);
            const data = @json($chartData);
            const ctx = document.getElementById('trendChart');

            if (ctx && data.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Guru yang Absen',
                            data: data,
                            backgroundColor: 'rgba(13, 110, 253, 0.15)',
                            borderColor: 'rgba(13, 110, 253, 0.8)',
                            borderWidth: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else if (ctx) {
                ctx.parentElement.innerHTML =
                    '<p class="text-muted text-center py-5">Belum ada data untuk periode ini.</p>';
            }
        });
    </script>
@endpush
