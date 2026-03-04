@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h5 class="fw-bold mb-1">Input Penilaian Siswa</h5>
            <p class="text-muted small">Silahkan pilih jadwal kelas untuk menginput nilai.</p>
        </div>

        <div class="row">
            @forelse($schedules as $item)
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm p-3">
                        <span class="badge bg-light text-primary align-self-start mb-2" style="font-size: 0.7rem;">Mata
                            Pelajaran</span>
                        <h4 class="fw-bold mb-1">{{ $item->subject->nama }}</h4>
                        <p class="text-muted small mb-3">Kelas:
                            {{ $item->classroom->tingkat }}-{{ $item->classroom->paralel }}</p>

                        <hr class="mt-0 mb-3 opacity-10">

                        <a href="{{ route('evaluations.create', $item->id) }}" class="btn btn-primary w-100 py-2 shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i> Mulai Input Nilai
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada jadwal mengajar yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
