@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Dashboard Guru</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            Halo, {{ Auth::user()->teacher->nama_guru ?? Auth::user()->name }}
                        </p>
                    </div>

                    {{-- FILTER & DROPDOWN --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Dropdown TA --}}
                        <select class="form-select form-select-sm w-auto"
                            onchange="window.location.href='?filter={{ $filter }}&academic_year_id='+this.value">
                            @foreach ($allYears as $y)
                                <option value="{{ $y->id }}"
                                    {{ $selectedYear && $selectedYear->id == $y->id ? 'selected' : '' }}
                                    style="color: #000;">
                                    {{ $y->tahun }} - {{ $y->semester }}
                                    {{ $y->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter periode --}}
                        <div class="btn-group">
                            @foreach (['today' => 'Hari ini', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'semester' => 'Semester'] as $key => $label)
                                <a href="?filter={{ $key }}&academic_year_id={{ $selectedYear?->id }}"
                                    class="btn btn-sm {{ $filter == $key ? 'btn-light' : 'btn-outline-light' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Badge Mode Baca (tetap di dalam header, di bawah) --}}
                @if (!$isActiveYear)
                    <div class="mt-3">
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="bi bi-eye me-1"></i>Mode Baca — {{ $selectedYear->tahun }}
                            {{ $selectedYear->semester }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Alert success --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert"
                style="border-left: 4px solid #22c55e; background-color: #f0fdf4;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <div class="small">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Chart --}}
        <div class="card border-0 shadow rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0 text-primary">
                    <i class="bi bi-graph-up me-2"></i>Statistik Kehadiran Siswa
                </h6>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 280px;">
                    {{-- Chart akan diinisialisasi oleh components/scripts.blade.php --}}
                    <canvas id="absensiChart" data-stats='@json($stats)'></canvas>
                </div>
            </div>
        </div>

        {{-- Rekap Per Kelas --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold text-primary mb-0">
                <i class="bi bi-building me-2"></i>Rekap Per Kelas
            </h6>
            <small class="text-muted">Klik kelas untuk detail</small>
        </div>

        @forelse($rekapKelas as $rk)
            @php
                $total = $rk->hadir + $rk->izin + $rk->sakit + $rk->alpa;
                $persen = $total > 0 ? round(($rk->hadir / $total) * 100) : 0;
                $color = $persen >= 80 ? 'success' : ($persen >= 50 ? 'warning' : 'danger');
            @endphp
            <a href="{{ route('guru.rekap.kelas', [$rk->classroom_id, 'filter' => $filter]) }}"
                class="card border-0 shadow rounded-3 mb-3 text-decoration-none d-block">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-dark">{{ $rk->nama_kelas }}</span>
                        <small class="text-muted">{{ $persen }}% hadir</small>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill bg-success-subtle text-success">
                            <i class="bi bi-check-circle me-1"></i>H: {{ $rk->hadir }}
                        </span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary">
                            <i class="bi bi-envelope me-1"></i>I: {{ $rk->izin }}
                        </span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning">
                            <i class="bi bi-heart me-1"></i>S: {{ $rk->sakit }}
                        </span>
                        <span class="badge rounded-pill bg-danger-subtle text-danger">
                            <i class="bi bi-x-circle me-1"></i>A: {{ $rk->alpa }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="card border-0 shadow rounded-3">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-50 text-muted"></i>
                    <p class="text-muted small mb-0">Belum ada data absensi untuk periode ini.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
