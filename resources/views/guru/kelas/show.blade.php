@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-label small"><a href="{{ route('guru.kelas.index') }}"
                        class="text-decoration-none">Daftar Kelas</a></li>
                <li class="breadcrumb-item active small" aria-current="page"> / Detail Kelas</li>
            </ol>
        </nav>

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
