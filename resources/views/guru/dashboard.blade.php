@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3">
            <h6 class="fw-bold text-muted">Halo, {{ Auth::user()->teacher->nama_guru ?? Auth::user()->name }} 👋</h6>
        </div>

        {{-- Filter --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-0">Dashboard</h5>
                @if (!$isActiveYear)
                    <span class="badge bg-warning text-dark mt-1">
                        <i class="bi bi-eye me-1"></i>Mode Baca — {{ $selectedYear->tahun }} {{ $selectedYear->semester }}
                    </span>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{-- Dropdown TA --}}
                <select class="form-select form-select-sm w-auto shadow-sm"
                    onchange="window.location.href='?filter={{ $filter }}&academic_year_id='+this.value">
                    @foreach ($allYears as $y)
                        <option value="{{ $y->id }}"
                            {{ $selectedYear && $selectedYear->id == $y->id ? 'selected' : '' }}>
                            {{ $y->tahun }} - {{ $y->semester }}
                            {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter periode --}}
                <div class="btn-group btn-group-sm shadow-sm">
                    <a href="?filter=today&academic_year_id={{ $selectedYear?->id }}"
                        class="btn btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Hari ini</a>
                    <a href="?filter=weekly&academic_year_id={{ $selectedYear?->id }}"
                        class="btn btn-outline-primary {{ $filter == 'weekly' ? 'active' : '' }}">Mingguan</a>
                    <a href="?filter=monthly&academic_year_id={{ $selectedYear?->id }}"
                        class="btn btn-outline-primary {{ $filter == 'monthly' ? 'active' : '' }}">Bulanan</a>
                    <a href="?filter=semester&academic_year_id={{ $selectedYear?->id }}"
                        class="btn btn-outline-primary {{ $filter == 'semester' ? 'active' : '' }}">Semester</a>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Statistik Kehadiran Siswa</h6>
                <div style="position: relative; height: 250px;">
                    <canvas id="absensiChart" data-stats='@json($stats)'></canvas>
                </div>
            </div>
        </div>

        {{-- Rekap Per Kelas --}}
        <h6 class="fw-bold mb-3">Rekap Per Kelas</h6>
        @forelse($rekapKelas as $rk)
            @php
                $total = $rk->hadir + $rk->izin + $rk->sakit + $rk->alpa;
                $persen = $total > 0 ? round(($rk->hadir / $total) * 100) : 0;
                $color = $persen >= 80 ? 'bg-success' : ($persen >= 50 ? 'bg-warning' : 'bg-danger');
            @endphp
            <a href="{{ route('guru.rekap.kelas', [$rk->classroom_id, 'filter' => $filter]) }}"
                class="card mb-2 border-0 shadow-sm text-decoration-none text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">{{ $rk->nama_kelas }}</span>
                        <small class="text-muted">{{ $persen }}% hadir</small>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar {{ $color }}" style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success-subtle text-success">H: {{ $rk->hadir }}</span>
                        <span class="badge bg-primary-subtle text-primary">I: {{ $rk->izin }}</span>
                        <span class="badge bg-warning-subtle text-warning">S: {{ $rk->sakit }}</span>
                        <span class="badge bg-danger-subtle text-danger">A: {{ $rk->alpa }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-warning text-center border-0 shadow-sm">
                Belum ada data absensi untuk periode ini.
            </div>
        @endforelse
    </div>

    @push('scripts')
        @include('components.scripts')
    @endpush
@endsection
