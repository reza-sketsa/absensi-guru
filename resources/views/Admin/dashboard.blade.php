@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="container py-4">

        {{-- Header - TAMBAHKAN CLASS bg-gradient-header --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-white" style="font-size: 1.25rem;">Dashboard Admin</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            {{ $totalGuruAktif }} dari {{ $totalGuru }} guru aktif mengabsen &mdash;
                            <span class="fw-semibold">{{ ucfirst($filter) }}</span>
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Dropdown TA --}}
                        <select class="form-select form-select-sm w-auto"
                            onchange="window.location.href='?filter={{ $filter }}&academic_year_id='+this.value">
                            @foreach ($allYears as $y)
                                <option value="{{ $y->id }}"
                                    {{ $activeYear && $activeYear->id == $y->id ? 'selected' : '' }} style="color: #000;">
                                    {{ $y->tahun }} - {{ $y->semester }}
                                    {{ $y->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Filter periode --}}
                        <div class="btn-group">
                            @foreach (['today' => 'Hari Ini', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'semester' => 'Semester'] as $key => $label)
                                <a href="?filter={{ $key }}&academic_year_id={{ $activeYear?->id }}"
                                    class="btn btn-sm {{ $filter == $key ? 'btn-light' : 'btn-outline-light' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stat Cards - GANTI shadow-sm jadi shadow, tambah class stat-number --}}
        <div class="row g-4 mb-4">
            @php
                $stats = [
                    ['label' => 'Total Guru', 'value' => $totalGuru, 'icon' => 'bi-people-fill', 'color' => 'primary'],
                    [
                        'label' => 'Guru Aktif',
                        'value' => $totalGuruAktif,
                        'icon' => 'bi-person-check-fill',
                        'color' => 'success',
                    ],
                    [
                        'label' => 'Belum Aktif',
                        'value' => $totalGuru - $totalGuruAktif,
                        'icon' => 'bi-person-dash-fill',
                        'color' => 'danger',
                    ],
                    [
                        'label' => 'Belum Absen Hari Ini',
                        'value' => $jadwalBelumAbsen->count(),
                        'icon' => 'bi-clock-fill',
                        'color' => 'warning',
                    ],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow rounded-3 h-100 transition-hover">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <small class="text-muted fw-medium text-uppercase small">{{ $stat['label'] }}</small>
                                <div class="bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }} rounded-3 d-flex align-items-center justify-content-center stat-icon"
                                    style="width:36px;height:36px;font-size:16px;">
                                    <i class="bi {{ $stat['icon'] }}"></i>
                                </div>
                            </div>
                            <h3 class="stat-number text-{{ $stat['color'] }} mb-0">{{ $stat['value'] }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chart - GANTI shadow-sm jadi shadow --}}
        <div class="card border-0 shadow rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-graph-up text-primary me-2"></i>Trend Guru Mengabsen per Hari
                </h6>
            </div>
            <div class="card-body">
                <div style="position:relative;height:280px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Keaktifan Guru - GANTI shadow-sm jadi shadow --}}
        <div class="card border-0 shadow rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-person-check text-primary me-2"></i>Keaktifan Guru Mengabsen
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-semibold">NAMA GURU</th>
                            <th class="py-3 text-center text-muted small fw-semibold">TOTAL JADWAL</th>
                            <th class="py-3 text-center text-muted small fw-semibold">SUDAH ABSEN</th>
                            <th class="py-3 text-muted small fw-semibold pe-4">KEAKTIFAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keaktifanGuru as $guru)
                            @php
                                $color =
                                    $guru->persentase >= 80
                                        ? 'success'
                                        : ($guru->persentase >= 50
                                            ? 'warning'
                                            : 'danger');
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $guru->nama_guru }}</td>
                                <td class="text-center text-muted">{{ $guru->total_jadwal }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $guru->total_absen > 0 ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                                        {{ $guru->total_absen }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:8px;">
                                            <div class="progress-bar bg-{{ $color }}"
                                                style="width:{{ $guru->persentase }}%"></div>
                                        </div>
                                        <small
                                            class="fw-semibold text-nowrap text-{{ $color }}">{{ $guru->persentase }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">
                                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Jadwal Belum Diabsen - GANTI shadow-sm jadi shadow --}}
        <div class="card border-0 shadow rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Jadwal Hari Ini yang Belum Diabsen
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-semibold">GURU</th>
                            <th class="py-3 text-muted small fw-semibold">MAPEL / KELAS</th>
                            <th class="py-3 text-muted small fw-semibold">JAM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalBelumAbsen as $j)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $j->nama_guru }}</td>
                                <td>
                                    <div class="fw-semibold text-primary">{{ $j->nama_mapel }}</div>
                                    <small class="text-muted">{{ $j->tingkat }}-{{ $j->paralel }}</small>
                                </td>
                                <td class="text-nowrap small text-muted">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} &ndash;
                                    {{ \Carbon\Carbon::parse($j->jam_habis)->format('H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small">
                                    <i class="bi bi-check-circle text-success me-1"></i>
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
        // Chart code tetap sama seperti aslinya
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($chartLabels);
            const data = @json($chartData);
            const ctx = document.getElementById('trendChart');

            if (ctx && data.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Guru yang Absen',
                            data,
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            borderColor: 'rgba(59, 130, 246, 0.8)',
                            borderWidth: 2,
                            borderRadius: 8,
                            barPercentage: 0.65,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f1f5f9',
                                bodyColor: '#cbd5e1',
                                padding: 8,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            } else if (ctx) {
                ctx.parentElement.innerHTML =
                    '<div class="text-center py-5"><i class="bi bi-bar-chart-steps display-1 text-muted opacity-25"></i><p class="text-muted mt-2">Belum ada data untuk periode ini.</p></div>';
            }
        });
    </script>
@endpush
