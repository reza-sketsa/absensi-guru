@extends('layouts.app')

@section('title', 'Riwayat Absensi - ' . $schedule->subject->nama_mapel)

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.absensi') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->jam_habis)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-calendar-week me-2"></i>Riwayat Absensi
        </h6>

        @forelse($histories as $history)
            <a href="{{ route('guru.absensi.history.detail', [$schedule->id, $history->id]) }}"
                class="card border-0 shadow rounded-3 mb-3 text-decoration-none d-block">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="fw-semibold text-dark">
                                {{ \Carbon\Carbon::parse($history->tanggal)->translatedFormat('d F Y') }}
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($history->tanggal)->translatedFormat('l') }}
                            </small>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                <span class="d-none d-sm-inline">Hadir: </span>{{ $history->h }}
                            </span>
                            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                                <i class="bi bi-envelope me-1"></i>
                                <span class="d-none d-sm-inline">Izin: </span>{{ $history->i }}
                            </span>
                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">
                                <i class="bi bi-heart me-1"></i>
                                <span class="d-none d-sm-inline">Sakit: </span>{{ $history->s }}
                            </span>
                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i>
                                <span class="d-none d-sm-inline">Alpa: </span>{{ $history->a }}
                            </span>
                            <i class="bi bi-chevron-right text-muted d-none d-md-block"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="card border-0 shadow rounded-3">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-calendar-x display-6 d-block mb-2 opacity-50 text-muted"></i>
                    <p class="text-muted small mb-0">Belum ada riwayat absensi untuk jadwal ini.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
