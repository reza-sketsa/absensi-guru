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
            <h5 class="fw-bold mb-0">Dashboard</h5>
            <div class="btn-group btn-group-sm shadow-sm flex-wrap">
                <a href="?filter=today" class="btn btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Hari
                    ini</a>
                <a href="?filter=weekly"
                    class="btn btn-outline-primary {{ $filter == 'weekly' ? 'active' : '' }}">Mingguan</a>
                <a href="?filter=monthly"
                    class="btn btn-outline-primary {{ $filter == 'monthly' ? 'active' : '' }}">Bulanan</a>
                <a href="?filter=semester"
                    class="btn btn-outline-primary {{ $filter == 'semester' ? 'active' : '' }}">Semester</a>
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

        {{-- Siswa Ketidakhadiran Tertinggi --}}
        <div class="card border-0 shadow-sm mt-4">
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
                                            {{ $item->student->classroom->nama_kelas ?? '' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $item->total_alpa }}</span>
                                    </td>
                                    <td class="text-center text-muted small">{{ $item->total_tidak_hadir }} Hari</td>
                                    <td>
                                        <a href="{{ route('guru.siswa.detail', $item->student_id) }}"
                                            class="btn btn-sm btn-outline-primary shadow-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data ketidakhadiran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('components.scripts')
    @endpush
@endsection
