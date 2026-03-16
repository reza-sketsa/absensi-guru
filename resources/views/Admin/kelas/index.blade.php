@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manajemen Kelas</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Tingkat</th>
                                <th>Paralel (Nama Kelas)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>Kelas {{ $item->tingkat }}</td>
                                    <td><strong>{{ $item->paralel }}</strong></td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.kelas.students.index', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-people-fill me-1"></i> Kelola Siswa
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus kelas ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahKelas" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Pilih Tingkat Kelas --</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                        </select>
                        @error('tingkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paralel / Nama Kelas</label>
                        <select name="paralel" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Abjad Kelas --</option>
                            @foreach (range('A', 'H') as $char)
                                <option value="{{ $char }}">{{ $char }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilihan tersedia dari A sampai H sesuai ketentuan sistem.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wali Kelas</label>
                        <select name="walas_id" class="form-select @error('walas_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->nama_guru }}</option>
                            @endforeach
                        </select>
                        @error('walas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
