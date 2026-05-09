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
                            <div class="fw-bold {{ $color }}">{{ $persen }}%</div>
                            <div class="d-flex gap-1 mt-1">
                                <span class="badge bg-success-subtle text-success">H: {{ $siswa->hadir }}</span>
                                <span class="badge bg-primary-subtle text-primary">I: {{ $siswa->izin }}</span>
                                <span class="badge bg-warning-subtle text-warning">S: {{ $siswa->sakit }}</span>
                                <span class="badge bg-danger-subtle text-danger">A: {{ $siswa->alpa }}</span>
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
