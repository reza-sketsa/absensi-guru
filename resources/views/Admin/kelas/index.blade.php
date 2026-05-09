@extends('layouts.app')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Manajemen Kelas</h4>
                        <p class="mb-0 opacity-75 small">Total: {{ count($classes) }} kelas terdaftar</p>
                    </div>
                    <button class="btn btn-light shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
                    </button>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Tingkat</th>
                                <th>Nama Kelas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark border">Kelas {{ $item->tingkat }}</span></td>
                                    <td><span class="fw-bold text-primary">{{ $item->paralel }}</span></td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.kelas.students.index', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Kelola Siswa">
                                                <i class="bi bi-people-fill"></i>
                                                <span class="d-none d-lg-inline ms-1">Siswa</span>
                                            </a>

                                            <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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

    {{-- Modal Tambah Kelas juga dibikin Fullscreen di HP biar gampang ngetik --}}
    <div class="modal fade" id="modalTambahKelas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down"> {{-- Fullscreen di layar kecil --}}
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Input Fields --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tingkat</label>
                        <select name="tingkat" class="form-select form-select-lg @error('tingkat') is-invalid @enderror"
                            required>
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
                        <label class="form-label small fw-bold">Paralel / Nama Kelas</label>
                        <select name="paralel" class="form-select form-select-lg" required>
                            <option value="" selected disabled>-- Pilih Abjad Kelas --</option>
                            @foreach (range('A', 'H') as $char)
                                <option value="{{ $char }}">{{ $char }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Wali Kelas</label>
                        <select name="walas_id" class="form-select form-select-lg @error('walas_id') is-invalid @enderror"
                            required>
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
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light w-25" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary flex-fill">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
