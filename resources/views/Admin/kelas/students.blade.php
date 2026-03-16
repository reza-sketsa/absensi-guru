@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary border-0 btn-sm me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>

            <div>
                <h3 class="fw-bold mb-0">Input Siswa</h3>
                <small class="text-muted">
                    Manajemen Siswa Kelas: <span
                        class="badge bg-primary-subtle text-primary fw-semibold">{{ $kelas->tingkat }}
                        {{ $kelas->paralel }}</span>
                </small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                <i class="bi bi-file-earmark-excel me-2"></i>Import CSV
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $s)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $s->nis }}</span></td>
                            <td class="fw-bold">{{ $s->nama }}</td>
                            <td>{{ $s->jk }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x d-block fs-1"></i> Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalTambahSiswa" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.kelas.students.store', $kelas->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select" required>
                                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                    <option value="{{ $agm }}">{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telp Siswa</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telp Ortu</label>
                            <input type="text" name="no_telp_ortu" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalImportSiswa" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.students.import', $kelas->id) }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Siswa via CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-2"></i> Gunakan format kolom:
                        <code>nama, nis, jk (L/P), agama, tgl_lahir (YYYY-MM-DD), alamat, no_telp, no_telp_ortu</code>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File CSV</label>
                        <input type="file" name="file_siswa" class="form-control" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
@endsection
