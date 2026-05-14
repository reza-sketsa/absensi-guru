<div class="modal fade" id="modalDetail{{ $student->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-gradient-header text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-file-text-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-primary">Laporan Lengkap: {{ $student->nama }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">

                {{-- Profil Siswa --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-2 px-3">
                        <h6 class="fw-semibold mb-0 text-primary">
                            <i class="bi bi-person-circle me-2"></i>Profil Siswa
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">NIS</div>
                                <div class="fw-semibold">{{ $student->nis }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">Agama</div>
                                <div class="fw-semibold">{{ $student->agama ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">No. Telepon</div>
                                <div class="fw-semibold">{{ $student->no_telp ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">No. Telepon Ortu</div>
                                <div class="fw-semibold">{{ $student->no_telp_ortu ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small mb-1">Alamat</div>
                                <div class="fw-semibold">{{ $student->alamat ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Nilai --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-2 px-3">
                        <h6 class="fw-semibold mb-0 text-primary">
                            <i class="bi bi-journal-check me-2"></i>Daftar Nilai
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3 py-2 text-muted small fw-semibold">Mata Pelajaran</th>
                                        <th class="py-2 text-muted small fw-semibold">Tanggal</th>
                                        <th class="py-2 text-muted small fw-semibold">Nilai</th>
                                        <th class="py-2 text-muted small fw-semibold">Predikat</th>
                                        <th class="py-2 text-center text-muted small fw-semibold pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->evaluations as $detail)
                                        <tr id="row-nilai-{{ $detail->id }}">
                                            <td class="ps-3 fw-semibold">
                                                {{ $detail->evaluation->nama_penilaian ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($detail->evaluation->tanggal)->translatedFormat('d M Y') }}
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $detail->nilai }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill {{ $detail->nilai >= 75 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                                    {{ $detail->nilai >= 75 ? 'Tuntas' : 'Remedial' }}
                                                </span>
                                            </td>
                                            <td class="text-center pe-3">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-hapus-nilai"
                                                    data-id="{{ $detail->id }}" title="Hapus Nilai">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted small">
                                                <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                                Belum ada data nilai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kehadiran --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-2 px-3">
                        <h6 class="fw-semibold mb-0 text-primary">
                            <i class="bi bi-calendar-check me-2"></i>Kehadiran
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3 text-center">
                            <div class="col-3">
                                <div class="border rounded-3 p-2 bg-success-subtle">
                                    <div class="fw-bold fs-4 text-success">0</div>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded-3 p-2 bg-info-subtle">
                                    <div class="fw-bold fs-4 text-info">0</div>
                                    <small class="text-muted">Sakit</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded-3 p-2 bg-warning-subtle">
                                    <div class="fw-bold fs-4 text-warning">0</div>
                                    <small class="text-muted">Izin</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded-3 p-2 bg-danger-subtle">
                                    <div class="fw-bold fs-4 text-danger">0</div>
                                    <small class="text-muted">Alpa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Data
                </a>
            </div>

        </div>
    </div>
</div>
