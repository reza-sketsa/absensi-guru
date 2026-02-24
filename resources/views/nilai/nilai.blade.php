@extends('welcome')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">Manajemen Data Siswa</h4>
                <p class="text-primary mb-0">
                    <i class="bi bi-person-badge me-1"></i>
                    Wali Kelas: {{ $students->first()->classroom->teacher->nama_guru ?? 'Belum Ditentukan' }}
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3" style="width: 30%">Nama Siswa</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th class="text-center">Input Nilai</th>
                                <th class="text-center">Absensi</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold">{{ $student->nama }}</div>
                                        <small
                                            class="text-muted text-uppercase">{{ $student->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $student->nis }}</span></td>
                                    <td>{{ $student->classroom->tingkat ?? '-' }} {{ $student->classroom->paralel ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($student->evaluations->count() > 0)
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                {{ $student->evaluations->count() }} Mapel
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning">Kosong</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <small class="text-danger fw-bold">0 Alpa</small>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group shadow-sm">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalDetail{{ $student->id }}">
                                                <i class="bi bi-person-lines-fill"></i>
                                            </button>
                                            <a href="{{ route('evaluation.create', $student->id) }}"
                                                class="btn btn-sm btn-outline-success" title="Input Nilai">
                                                <i class="bi bi-journal-check"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted">Data siswa tidak ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($students as $student)
        @include('components.modal-detail-siswa', ['student' => $student])
    @endforeach

@endsection
