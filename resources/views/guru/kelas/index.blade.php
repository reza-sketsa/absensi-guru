@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h5 class="fw-bold mb-4">Daftar Kelas Anda</h5>
        <div class="row">
            @forelse($classrooms as $kelas)
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">Kelas {{ $kelas->tingkat }} {{ $kelas->paralel }}</h6>
                                    <p class="text-muted small mb-0">{{ $kelas->students_count }} Siswa terdaftar</p>
                                </div>
                                <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="btn btn-primary btn-sm">
                                    Lihat Siswa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Anda belum memiliki jadwal mengajar atau kelas perwalian.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
