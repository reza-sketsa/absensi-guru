@extends('layouts.app')

@section('title', 'Daftar Kelas')

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <h5 class="fw-bold mb-1 text-white">Daftar Kelas</h5>
                <p class="mb-0 text-white opacity-75 small">{{ $classrooms->count() }} kelas yang Anda ampu</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($classrooms as $kelas)
                <div class="col-md-4">
                    <div class="card border-0 shadow rounded-3 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-door-open fs-5"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">Kelas {{ $kelas->tingkat }} {{ $kelas->paralel }}</h6>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-people me-1"></i>{{ $kelas->students_count }} Siswa
                                    </p>
                                </div>
                                <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body py-5 text-center">
                            <i class="bi bi-inbox display-6 d-block mb-2 opacity-50 text-muted"></i>
                            <p class="text-muted small mb-0">Anda belum memiliki jadwal mengajar atau kelas perwalian.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
