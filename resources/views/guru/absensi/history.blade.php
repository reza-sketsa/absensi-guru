@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.absensi') }}" class="btn btn-outline-light border-0 btn-sm me-3">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas: {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->jam_habis)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Absensi</h5>

        @forelse($histories as $history)
            <a href="{{ route('guru.absensi.history.detail', [$schedule->id, $history->id]) }}"
                class="card mb-2 border-0 shadow-sm text-decoration-none text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($history->tanggal)->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-muted small">
                                {{ \Carbon\Carbon::parse($history->tanggal)->translatedFormat('l') }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-success-subtle text-success px-2">H: {{ $history->h }}</span>
                            <span class="badge bg-primary-subtle text-primary px-2">I: {{ $history->i }}</span>
                            <span class="badge bg-warning-subtle text-warning px-2">S: {{ $history->s }}</span>
                            <span class="badge bg-danger-subtle text-danger px-2">A: {{ $history->a }}</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-warning text-center border-0 shadow-sm">
                Belum ada riwayat absensi untuk jadwal ini.
            </div>
        @endforelse
    </div>
@endsection
