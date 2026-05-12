@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('guru.dashboard', ['filter' => $filter]) }}"
                class="btn btn-outline-secondary border-0 btn-sm me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>

            <div>
                <h5 class="fw-bold mb-0">Rekap Kelas {{ $classroom->tingkat }}-{{ $classroom->paralel }}</h5>
                <small class="text-muted">{{ ucfirst($filter) }}</small>
            </div>
        </div>

        {{-- Siswa Ketidakhadiran Tertinggi --}}
        <div class="card border-0 shadow-sm mb-4">
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
                            @php
                                $tidakHadir = $rekapSiswa
                                    ->filter(fn($s) => $s->alpa + $s->sakit + $s->izin > 0)
                                    ->sortByDesc('alpa')
                                    ->take(5);
                            @endphp
                            @forelse($tidakHadir as $siswa)
                                <tr>
                                    <td class="fw-bold">{{ $siswa->nama }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $siswa->alpa }}</span>
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $siswa->alpa + $siswa->sakit + $siswa->izin }} Hari
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.siswa.detail', $siswa->id) }}"
                                            class="btn btn-sm btn-outline-primary shadow-sm">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Tidak ada data ketidakhadiran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @forelse($rekapSiswa as $siswa)
            @php
                $total = $siswa->hadir + $siswa->izin + $siswa->sakit + $siswa->alpa;
                $persen = $total > 0 ? round(($siswa->hadir / $total) * 100) : 0;
                $color = $persen >= 80 ? 'text-success' : ($persen >= 50 ? 'text-warning' : 'text-danger');
            @endphp
            <div class="card mb-2 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $siswa->nama }}</div>
                            <div class="text-muted small">NIS: {{ $siswa->nis }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold {{ $color }}">{{ $persen }}%
                            </div>
                            <div class="d-flex gap-1 mt-1">
                                <span class="badge bg-success-subtle text-success">H:
                                    {{ $siswa->hadir }}</span>
                                <span class="badge bg-primary-subtle text-primary">I:
                                    {{ $siswa->izin }}</span>
                                <span class="badge bg-warning-subtle text-warning">S:
                                    {{ $siswa->sakit }}</span>
                                <span class="badge bg-danger-subtle text-danger">A:
                                    {{ $siswa->alpa }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center border-0 shadow-sm">
                Belum ada data siswa.
            </div>
        @endforelse
    </div>
@endsection
