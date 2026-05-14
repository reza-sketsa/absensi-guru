@extends('layouts.app')

@section('title', 'Rekap Kelas ' . $classroom->tingkat . '-' . $classroom->paralel)

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.dashboard', ['filter' => $filter]) }}"
                        class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Rekap Kelas {{ $classroom->tingkat }}-{{ $classroom->paralel }}
                        </h5>
                        <p class="mb-0 text-white opacity-75 small">
                            Periode: {{ ucfirst($filter) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Siswa dengan Ketidakhadiran Tertinggi --}}
        <div class="card border-0 shadow rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-semibold mb-0 text-primary">
                    <i class="bi bi-exclamation-triangle me-2"></i>Siswa dengan Ketidakhadiran Tertinggi
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-semibold">NAMA SISWA</th>
                                <th class="py-3 text-center text-muted small fw-semibold">ALPA</th>
                                <th class="py-3 text-center text-muted small fw-semibold">TOTAL (A+S+I)</th>
                                <th class="py-3 text-center text-muted small fw-semibold pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $tidakHadir = $rekapSiswa
                                    ->filter(fn($s) => $s->alpa + $s->sakit + $s->izin > 0)
                                    ->sortByDesc('alpa')
                                    ->take(5);
                            @endphp
                            @forelse($tidakHadir as $siswa)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $siswa->nama }}</div>
                                        <small class="text-muted">NIS: {{ $siswa->nis }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1">
                                            {{ $siswa->alpa }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted small fw-semibold">
                                        {{ $siswa->alpa + $siswa->sakit + $siswa->izin }} Hari
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('guru.siswa.detail', $siswa->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                        Tidak ada data ketidakhadiran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Rekap Seluruh Siswa --}}
        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-people me-2"></i>Rekap Seluruh Siswa
        </h6>

        @forelse($rekapSiswa as $siswa)
            @php
                $total = $siswa->hadir + $siswa->izin + $siswa->sakit + $siswa->alpa;
                $persen = $total > 0 ? round(($siswa->hadir / $total) * 100) : 0;
                $color = $persen >= 80 ? 'success' : ($persen >= 50 ? 'warning' : 'danger');
            @endphp
            <div class="card border-0 shadow rounded-3 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="fw-semibold text-dark">{{ $siswa->nama }}</div>
                            <small class="text-muted">NIS: {{ $siswa->nis }}</small>
                        </div>
                        <div class="text-end text-sm-start">
                            <div class="fw-bold text-{{ $color }} mb-1">{{ $persen }}% Hadir</div>
                            <div class="d-flex flex-wrap gap-1 justify-content-sm-end">
                                <span class="badge rounded-pill bg-success-subtle text-success">
                                    H: {{ $siswa->hadir }}
                                </span>
                                <span class="badge rounded-pill bg-primary-subtle text-primary">
                                    I: {{ $siswa->izin }}
                                </span>
                                <span class="badge rounded-pill bg-warning-subtle text-warning">
                                    S: {{ $siswa->sakit }}
                                </span>
                                <span class="badge rounded-pill bg-danger-subtle text-danger">
                                    A: {{ $siswa->alpa }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow rounded-3">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-50 text-muted"></i>
                    <p class="text-muted small mb-0">Belum ada data siswa.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
