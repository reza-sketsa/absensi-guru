@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm bg-primary text-white mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-light border-0 btn-sm">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                        <div>
                            <h4 class="fw-bold mb-1">Siswa Kelas {{ $kelas->tingkat }}-{{ $kelas->paralel }}</h4>
                            <p class="mb-0 opacity-75 small">Total: {{ count($students) }} siswa terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                        <i class="bi bi-file-earmark-excel me-1"></i>Import CSV
                    </button>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                        <i class="bi bi-person-plus-fill me-1"></i>Tambah Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
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
                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalEditSiswa{{ $s->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.kelas.students.destroy', [$kelas->id, $s->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus siswa {{ $s->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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

    @foreach ($students as $s)
        <div class="modal fade" id="modalEditSiswa{{ $s->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.kelas.students.update', [$kelas->id, $s->id]) }}" method="POST"
                    class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis" class="form-control" value="{{ $s->nis }}"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jk" class="form-select" required>
                                    <option value="L" {{ $s->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $s->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $s->nama }}"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agama</label>
                                <select name="agama" class="form-select" required>
                                    @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                        <option value="{{ $agm }}" {{ $s->agama == $agm ? 'selected' : '' }}>
                                            {{ $agm }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($s->tgl_lahir)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required>{{ $s->alamat }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telp Siswa</label>
                                <input type="text" name="no_telp" class="form-control" value="{{ $s->no_telp }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telp Ortu</label>
                                <input type="text" name="no_telp_ortu" class="form-control"
                                    value="{{ $s->no_telp_ortu }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

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
