    @extends('layouts.app')

    @section('content')
        <div class="container py-4">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline-secondary border-0 btn-sm me-3">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h5 class="fw-bold mb-0">Detail Absensi Siswa</h5>

                {{-- Dropdown TA --}}
                <select class="form-select form-select-sm w-auto shadow-sm ms-auto"
                    onchange="window.location.href='{{ route('guru.siswa.detail', $student->id) }}?academic_year_id='+this.value">
                    @foreach ($allYears as $y)
                        <option value="{{ $y->id }}"
                            {{ $selectedYear && $selectedYear->id == $y->id ? 'selected' : '' }}>
                            {{ $y->tahun }} - {{ $y->semester }}
                            {{ $y->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                {{-- Profil Siswa --}}
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr($student->nama, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="fw-bold">{{ $student->nama }}</h5>
                        <p class="text-muted mb-0">{{ $student->classroom->nama_kelas ?? 'Tanpa Kelas' }}</p>
                        <hr>
                        <div class="row g-2 text-start">
                            <div class="col-6">
                                <small class="text-muted d-block">NIS</small>
                                <span class="fw-bold">{{ $student->nis }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Jenis Kelamin</small>
                                <span class="fw-bold">{{ $student->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Agama</small>
                                <span class="fw-bold">{{ $student->agama }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <span
                                    class="fw-bold">{{ \Carbon\Carbon::parse($student->tgl_lahir)->format('d M Y') }}</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">Alamat</small>
                                <span class="fw-bold">{{ $student->alamat }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">No. Telp</small>
                                <span class="fw-bold">{{ $student->no_telp ?? '-' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">No. Telp Ortu</small>
                                <span class="fw-bold">{{ $student->no_telp_ortu ?? '-' }}</span>
                            </div>
                            <hr>
                            @foreach ($summary as $status => $count)
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ $status }}</small>
                                    <span class="fw-bold">{{ $count }} Hari</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Riwayat Absensi --}}
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Riwayat Absensi Lengkap</h6>
                                @if ($selectedYear)
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $selectedYear->tahun }} - {{ $selectedYear->semester }}
                                    </span>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendanceHistory as $history)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($history->attendance->tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $history->status == 'Hadir'
                                                            ? 'bg-success'
                                                            : ($history->status == 'Alpa'
                                                                ? 'bg-danger'
                                                                : 'bg-warning text-dark') }}">
                                                        {{ $history->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-4">Belum ada riwayat absensi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
