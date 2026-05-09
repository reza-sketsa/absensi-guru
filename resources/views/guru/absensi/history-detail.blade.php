@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.absensi.history', $schedule->id) }}"
                        class="btn btn-outline-light border-0 btn-sm">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas: {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-people me-2"></i>Detail Kehadiran</h5>

        @forelse($attendance->details as $detail)
            @php
                $badge = match ($detail->status) {
                    'Hadir' => 'bg-success',
                    'Izin' => 'bg-primary',
                    'Sakit' => 'bg-warning',
                    'Alpa' => 'bg-danger',
                    default => 'bg-secondary',
                };
            @endphp
            <div class="card mb-2 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">{{ $detail->student->nama }}</div>
                        <div class="text-muted small">NIS: {{ $detail->student->nis }}</div>
                    </div>
                    <span class="badge {{ $badge }} px-3">{{ $detail->status }}</span>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center border-0 shadow-sm">
                Belum ada detail absensi.
            </div>
        @endforelse
    </div>
@endsection
