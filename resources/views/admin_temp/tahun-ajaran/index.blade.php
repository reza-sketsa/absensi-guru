@extends('layouts.app')
@section('title', 'Tahun Akademik')

@section('content')
    <div class="container py-4">

        {{-- Header - PAKAI CLASS bg-gradient-header --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Tahun Akademik</h5>
                        <p class="mb-0 text-white opacity-75 small">Kelola periode aktif pengajaran</p>
                    </div>
                    <button class="btn btn-light fw-semibold flex-shrink-0" data-bs-toggle="modal"
                        data-bs-target="#addYearModal">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Tahun
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-semibold">TAHUN AJARAN</th>
                                <th class="py-3 text-muted small fw-semibold">SEMESTER</th>
                                <th class="py-3 text-center text-muted small fw-semibold">STATUS</th>
                                <th class="py-3 text-center text-muted small fw-semibold pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($years as $year)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">{{ $year->tahun }}</td>
                                    <td class="text-muted">{{ $year->semester }}</td>
                                    <td class="text-center">
                                        @if ($year->is_active)
                                            <span class="badge rounded-pill bg-success-subtle text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        @if (!$year->is_active)
                                            <form action="{{ route('admin.tahun-ajaran.activate', $year->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">
                                        <i class="bi bi-calendar-x display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data tahun akademik.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Tambah - DIPERCANTIK --}}
    <div class="modal fade" id="addYearModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST"
                class="modal-content border-0 rounded-4 shadow-lg">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0 text-primary">Tambah Tahun Akademik</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Tahun Ajaran</label>
                            <input type="text" name="tahun" class="form-control" placeholder="Contoh: 2025/2026"
                                required>
                            <div class="form-text small text-muted">Format: YYYY/YYYY</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    @include('components.scripts')
@endpush
