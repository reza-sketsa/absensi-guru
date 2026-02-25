<div class="modal fade" id="modalDetail{{ $student->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laporan Lengkap: {{ $student->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6><i class="bi bi-person-circle"></i> Profil Siswa</h6>
                <table class="table table-sm mb-4">
                    <tr>
                        <td width="30%">NIS</td>
                        <td>: {{ $student->nis }}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>: {{ $student->agama }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $student->alamat }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td>: {{ $student->no_telp }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp Ortu</td>
                        <td>: {{ $student->no_telp_ortu }}</td>
                    </tr>
                </table>

                <hr>

                <h6><i class="bi bi-journal-check"></i> Daftar Nilai</h6>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal</th>
                            <th>Nilai</th>
                            <th>Predikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->evaluations as $detail)
                            <tr id="row-nilai-{{ $detail->id }}">
                                <td>{{ $detail->evaluation->nama_penilaian ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($detail->evaluation->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td>{{ $detail->nilai }}</td>
                                <td>
                                    <span class="badge {{ $detail->nilai >= 75 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $detail->nilai >= 75 ? 'Tuntas' : 'Remedial' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger p-0 btn-hapus-nilai"
                                        data-id="{{ $detail->id }}" title="Hapus Nilai">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada nilai</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <hr>

                <h6><i class="bi bi-calendar-check"></i> Kehadiran</h6>
                <div class="row text-center">
                    <div class="col">
                        <div class="border p-2 rounded bg-light"><strong>0</strong><br><small>Hadir</small></div>
                    </div>
                    <div class="col">
                        <div class="border p-2 rounded bg-light"><strong>0</strong><br><small>Sakit</small></div>
                    </div>
                    <div class="col">
                        <div class="border p-2 rounded bg-light"><strong>0</strong><br><small>Izin</small></div>
                    </div>
                    <div class="col">
                        <div class="border p-2 rounded bg-light"><strong>0</strong><br><small>Alpa</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
