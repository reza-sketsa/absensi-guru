@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="container py-4">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline-secondary border-0 btn-sm me-3">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h5 class="fw-bold mb-0">Detail Kelas</h5>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Daftar Siswa Kelas {{ $classroom->tingkat }} {{ $classroom->paralel }}</h6>
                </div>
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
    @endsection
