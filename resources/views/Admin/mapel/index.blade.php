@extends('layouts.app')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Mata Pelajaran</h4>
                <p class="mb-0 opacity-75 small">Total: {{ $subjects->count() }} mata pelajaran terdaftar</p>
            </div>
        </div>

        {{-- g-4 memberikan jarak antar kolom, flex-column-reverse agar form tambah di atas saat di HP (Opsional) --}}
        <div class="row g-4">
            {{-- Bagian Tabel Mapel --}}
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th class="ps-4" style="width: 80px;">No</th>
                                        <th>Nama Mata Pelajaran</th>
                                        <th class="text-center px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($subjects as $index => $s)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $s->nama_mapel }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-light text-primary me-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditMapel{{ $s->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('admin.mapel.destroy', $s->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger"
                                                        onclick="return confirm('Hapus mapel ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-5 text-center text-muted">
                                                <i class="bi bi-book fs-1 d-block mb-2"></i>
                                                Belum ada data mata pelajaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Form Tambah Mapel --}}
            <div class="col-lg-4 order-1 order-lg-2">
                <div class="card border-0 shadow-sm sticky-lg-top" style="top: 1rem;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Mapel Baru
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <form action="{{ route('admin.mapel.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Mapel</label>
                                <input type="text" name="nama_mapel"
                                    class="form-control form-control-lg @error('nama_mapel') is-invalid @enderror"
                                    placeholder="Contoh: IPA" value="{{ old('nama_mapel') }}" required>

                                @error('nama_mapel')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 shadow-sm py-2">
                                <i class="bi bi-save me-1"></i> Simpan Mapel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($subjects as $s)
        <div class="modal fade" id="modalEditMapel{{ $s->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.mapel.update', $s->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Edit Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label small fw-bold">Nama Mapel</label>
                        <input type="text" name="nama_mapel" class="form-control form-control-lg"
                            value="{{ $s->nama_mapel }}" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light w-25" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary flex-fill">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
