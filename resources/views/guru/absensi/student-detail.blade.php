@extends('layouts.app')

@section('title', 'Detail Absensi - ' . $student->nama)

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Detail Absensi Siswa</h5>
                            <p class="mb-0 text-white opacity-75 small">{{ $student->nama }}</p>
                        </div>
                    </div>

                    {{-- Dropdown TA --}}
                    <select class="form-select form-select-sm w-auto"
                        style="background-color: rgba(255,255,255,0.2); color:white; border-color: rgba(255,255,255,0.3);"
                        onchange="window.location.href='{{ route('guru.siswa.detail', $student->id) }}?academic_year_id='+this.value">
                        @foreach ($allYears as $y)
                            <option value="{{ $y->id }}"
                                {{ $selectedYear && $selectedYear->id == $y->id ? 'selected' : '' }} style="color: #000;">
                                {{ $y->tahun }} - {{ $y->semester }}
                                @if ($y->is_active)
                                    ⭐
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Profil Siswa --}}
            <div class="col-md-4">
                <div class="card border-0 shadow rounded-3 text-center p-4">
                    <div class="mb-3">
                        <div class="bg-gradient-header text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($student->nama, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $student->nama }}</h5>
                    <p class="text-muted small mb-3">{{ $student->classroom->nama_kelas ?? 'Tanpa Kelas' }}</p>
                    <hr class="my-3">
                    <div class="row g-3 text-start">
                        <div class="col-6">
                            <div class="text-muted small mb-1">NIS</div>
                            <div class="fw-semibold">{{ $student->nis }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Jenis Kelamin</div>
                            <div class="fw-semibold">{{ $student->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Agama</div>
                            <div class="fw-semibold">{{ $student->agama ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Tanggal Lahir</div>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($student->tgl_lahir)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small mb-1">Alamat</div>
                            <div class="fw-semibold">{{ $student->alamat ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">No. Telp</div>
                            <div class="fw-semibold">{{ $student->no_telp ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">No. Telp Ortu</div>
                            <div class="fw-semibold">{{ $student->no_telp_ortu ?? '-' }}</div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row g-2 text-center">
                        @foreach ($summary as $status => $count)
                            @php
                                $badgeClass = match ($status) {
                                    'Hadir' => 'bg-success-subtle text-success',
                                    'Izin' => 'bg-primary-subtle text-primary',
                                    'Sakit' => 'bg-warning-subtle text-warning',
                                    'Alpa' => 'bg-danger-subtle text-danger',
                                    default => 'bg-secondary-subtle text-secondary',
                                };
                                $icon = match ($status) {
                                    'Hadir' => 'bi-check-circle',
                                    'Izin' => 'bi-envelope',
                                    'Sakit' => 'bi-heart',
                                    'Alpa' => 'bi-x-circle',
                                    default => 'bi-question-circle',
                                };
                            @endphp
                            <div class="col-6">
                                <div class="border rounded-3 p-2 {{ $badgeClass }} bg-opacity-10">
                                    <i class="bi {{ $icon }}"></i>
                                    <div class="fw-bold">{{ $count }}</div>
                                    <small>{{ $status }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Riwayat Absensi --}}
            <div class="col-md-8">
                <div class="card border-0 shadow rounded-3">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-semibold mb-0 text-primary">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Absensi Lengkap
                            </h6>
                            @if ($selectedYear)
                                <span class="badge rounded-pill bg-primary-subtle text-primary">
                                    {{ $selectedYear->tahun }} - {{ $selectedYear->semester }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small fw-semibold">Tanggal</th>
                                        <th class="py-3 text-muted small fw-semibold pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendanceHistory as $history)
                                        @php
                                            $badgeClass = match ($history->status) {
                                                'Hadir' => 'bg-success-subtle text-success',
                                                'Izin' => 'bg-primary-subtle text-primary',
                                                'Sakit' => 'bg-warning-subtle text-warning',
                                                'Alpa' => 'bg-danger-subtle text-danger',
                                                default => 'bg-secondary-subtle text-secondary',
                                            };
                                            $icon = match ($history->status) {
                                                'Hadir' => 'bi-check-circle',
                                                'Izin' => 'bi-envelope',
                                                'Sakit' => 'bi-heart',
                                                'Alpa' => 'bi-x-circle',
                                                default => 'bi-question-circle',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                {{ \Carbon\Carbon::parse($history->attendance->tanggal)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="pe-4">
                                                <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2">
                                                    <i class="bi {{ $icon }} me-1"></i>{{ $history->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-5 text-muted small">
                                                <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                                Belum ada riwayat absensi.
                                            </td>
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
