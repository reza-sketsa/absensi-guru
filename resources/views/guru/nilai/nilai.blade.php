@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h5 class="fw-bold mb-1">Input Penilaian Siswa</h5>
            <p class="text-muted small">Silahkan pilih jadwal kelas untuk menginput atau melihat nilai.</p>
        </div>

        <div class="row">
            @forelse($schedules as $item)
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <span class="badge bg-light text-primary align-self-start mb-2" style="font-size: 0.7rem;">
                                Mata Pelajaran
                            </span>
                            <h4 class="fw-bold mb-1">{{ $item->subject->nama }}</h4>
                            <p class="text-muted small mb-3">
                                Kelas: {{ $item->classroom->tingkat }}-{{ $item->classroom->paralel }}
                            </p>

                            <a href="{{ route('guru.evaluations.create', $item->id) }}"
                                class="btn btn-primary w-100 py-2 shadow-sm mb-3">
                                <i class="bi bi-pencil-square me-2"></i> Mulai Input Nilai
                            </a>

                            <div class="mt-3">
                                <h6 class="fw-bold small text-muted mb-2"><i class="bi bi-clock-history me-1"></i>
                                    Riwayat
                                    Terakhir:</h6>
                                <div class="list-group list-group-flush">
                                    @if ($item->evaluations && $item->evaluations->count() > 0)
                                        @foreach ($item->evaluations as $eval)
                                            <a href="{{ route('guru.evaluations.show', $eval->id) }}"
                                                class="list-group-item list-group-item-action border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span
                                                        class="d-block small fw-bold text-dark">{{ $eval->nama_penilaian }}</span>
                                                    <small class="text-muted"
                                                        style="font-size: 0.7rem;">{{ $eval->tanggal }} |
                                                        {{ $eval->jenis }}</small>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted small"></i>
                                            </a>
                                        @endforeach
                                    @else
                                        <p class="text-muted small italic">Belum ada nilai yang diinput.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
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
