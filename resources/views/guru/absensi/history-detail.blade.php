@extends('layouts.app')

@section('title', 'Detail Absensi - ' . $schedule->subject->nama_mapel)

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.absensi.history', $schedule->id) }}"
                        class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-people me-2"></i>Detail Kehadiran
        </h6>

        @forelse($attendance->details as $detail)
            @php
                $badgeClass = match ($detail->status) {
                    'Hadir' => 'bg-success-subtle text-success',
                    'Izin' => 'bg-primary-subtle text-primary',
                    'Sakit' => 'bg-warning-subtle text-warning',
                    'Alpa' => 'bg-danger-subtle text-danger',
                    default => 'bg-secondary-subtle text-secondary',
                };
                $icon = match ($detail->status) {
                    'Hadir' => 'bi-check-circle',
                    'Izin' => 'bi-envelope',
                    'Sakit' => 'bi-heart',
                    'Alpa' => 'bi-x-circle',
                    default => 'bi-question-circle',
                };
            @endphp
            <div class="card border-0 shadow rounded-3 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold text-dark">{{ $detail->student->nama }}</div>
                            <small class="text-muted">NIS: {{ $detail->student->nis }}</small>
                        </div>
                        <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2">
                            <i class="bi {{ $icon }} me-1"></i>{{ $detail->status }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow rounded-3">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-50 text-muted"></i>
                    <p class="text-muted small mb-0">Belum ada detail absensi.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
