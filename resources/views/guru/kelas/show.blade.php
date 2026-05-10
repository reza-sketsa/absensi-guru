@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline-light border-0 btn-sm">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">Kelas {{ $classroom->tingkat }}-{{ $classroom->paralel }}</h5>
                        <p class="mb-0 opacity-75 small">{{ $classroom->students->count() }} Siswa terdaftar</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <input type="text" id="searchSiswaGuru" class="form-control" placeholder="Cari nama siswa...">
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classroom->students as $index => $student)
                                <tr>
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $student->nama }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('guru.siswa.detail', $student->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-person-badge"></i> Profil Absensi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('components.scripts')
    @endpush
@endsection
