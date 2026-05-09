@extends('layouts.app')

@section('title', 'Setting Tahun Akademik')

@section('content')
    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Tahun Akademik</h3>
                <p class="text-muted small mb-0">Kelola periode aktif pengajaran</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addYearModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tahun
            </button>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 text-muted small fw-bold">TAHUN AJARAN</th>
                                <th class="py-3 border-0 text-muted small fw-bold">SEMESTER</th>
                                <th class="py-3 border-0 text-muted small fw-bold text-center">STATUS</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($years as $year)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $year->tahun }}</td>
                                    <td>{{ $year->semester }}</td>
                                    <td class="text-center">
                                        @if ($year->is_active)
                                            <span class="badge bg-success-subtle text-success border-0 px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border border-0">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        @if (!$year->is_active)
                                            <form action="{{ route('admin.tahun-ajaran.activate', $year->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-success disabled" disabled>Terpilih</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data tahun akademik.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="addYearModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="fw-bold">Tambah Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Tahun (Contoh: 2025/2026)</label>
                            <input type="text" name="tahun" class="form-control" placeholder="Tulis tahun ajaran..."
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
