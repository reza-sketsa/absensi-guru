@extends('layouts.app')

@section('title', 'Penilaian Siswa')

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Input Penilaian Siswa</h5>
                        <p class="mb-0 text-white opacity-75 small">Pilih jadwal kelas untuk menginput atau melihat nilai.
                        </p>
                    </div>
                    <a href="{{ route('guru.evaluations.trash') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-trash3 me-1"></i> Sampah
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($schedules as $item)
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-3 h-100">
                        <div class="card-body p-4">
                            <span class="badge rounded-pill bg-primary-subtle text-primary mb-3 px-3 py-1">
                                <i class="bi bi-book me-1"></i> Mata Pelajaran
                            </span>
                            <h5 class="fw-bold mb-1">{{ $item->subject->nama_mapel ?? '-' }}</h5>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-door-open me-1"></i>
                                Kelas {{ $item->classroom->tingkat ?? '-' }}-{{ $item->classroom->paralel ?? '-' }}
                            </p>

                            <a href="{{ route('guru.evaluations.create', $item->id) }}"
                                class="btn btn-primary w-100 py-2 mb-4">
                                <i class="bi bi-pencil-square me-2"></i> Mulai Input Nilai
                            </a>

                            <div class="mt-2">
                                <h6 class="fw-semibold small text-muted mb-2">
                                    <i class="bi bi-clock-history me-1"></i> Riwayat Penilaian
                                </h6>
                                <div class="list-group list-group-flush">
                                    @if ($item->all_evaluations && $item->all_evaluations->count() > 0)
                                        @foreach ($item->all_evaluations as $eval)
                                            <a href="{{ route('guru.evaluations.show', $eval->id) }}"
                                                class="list-group-item list-group-item-action px-0 py-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span
                                                        class="d-block small fw-semibold text-dark">{{ $eval->nama_penilaian }}</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        {{ $eval->tanggal->format('d M Y') }} | {{ $eval->jenis }}
                                                    </small>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3">
                                            <i class="bi bi-inbox text-muted opacity-50"></i>
                                            <p class="text-muted small mb-0">Belum ada nilai yang diinput.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body py-5 text-center">
                            <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50 text-muted"></i>
                            <p class="text-muted small mb-0">Belum ada jadwal mengajar yang tersedia.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
