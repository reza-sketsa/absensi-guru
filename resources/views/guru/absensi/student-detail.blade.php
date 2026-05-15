@extends('layouts.app')
@section('title', 'Detail Absensi - ' . $student->nama)

@section('content')
    <div class="container py-4 pb-5 mb-4">

        {{-- Header --}}
        <div class="no-hover card border-0 rounded-3 mb-3 bg-gradient-header">
            <div class="card-body px-4 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('guru.kelas.index') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Detail Absensi Siswa</h5>
                            <p class="mb-0 text-white opacity-75 small">{{ $student->nama }}</p>
                        </div>
                    </div>
                    <select class="form-select form-select-sm w-auto select-glass"
                        onchange="window.location.href='{{ route('guru.siswa.detail', $student->id) }}?academic_year_id='+this.value">
                        @foreach ($allYears as $y)
                            <option value="{{ $y->id }}"
                                {{ $selectedYear && $selectedYear->id == $y->id ? 'selected' : '' }} style="color:#000;">
                                {{ $y->tahun }} - {{ $y->semester }}
                                {{ $y->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ① KOLOM KIRI: Profil + Summary --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 text-center mb-3">

                    {{-- Avatar --}}
                    <div class="mb-3">
                        <div class="bg-gradient-header text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:72px;height:72px;font-size:1.75rem;">
                            {{ strtoupper(substr($student->nama, 0, 1)) }}
                        </div>
                    </div>

                    {{-- Nama + Badge status dominan --}}
                    <h6 class="fw-bold mb-1">{{ $student->nama }}</h6>
                    <p class="text-muted small mb-2">
                        {{ $student->classroom ? $student->classroom->tingkat . '-' . $student->classroom->paralel : 'Tanpa Kelas' }}
                    </p>
                    <span class="badge bg-{{ $statusDominanColor }}-subtle text-{{ $statusDominanColor }} px-3 py-2 mb-3">
                        {{ $statusDominanLabel }}
                    </span>

                    <hr class="my-3">

                    {{-- Info pribadi --}}
                    <div class="row g-2 text-start">
                        <div class="col-6">
                            <div class="text-muted small mb-1">NIS</div>
                            <div class="fw-semibold small">{{ $student->nis }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Jenis Kelamin</div>
                            <div class="fw-semibold small">
                                {{ $student->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Agama</div>
                            <div class="fw-semibold small">{{ $student->agama ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Tanggal Lahir</div>
                            <div class="fw-semibold small">
                                {{ $student->tgl_lahir ? \Carbon\Carbon::parse($student->tgl_lahir)->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small mb-1">Alamat</div>
                            <div class="fw-semibold small">{{ $student->alamat ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">No. Telp</div>
                            <div class="fw-semibold small">{{ $student->no_telp ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">No. Telp Ortu</div>
                            <div class="fw-semibold small">{{ $student->no_telp_ortu ?? '-' }}</div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Summary stat --}}
                    @php
                        $summaryConfig = [
                            'Hadir' => ['class' => 'success', 'icon' => 'bi-check-circle'],
                            'Izin' => ['class' => 'primary', 'icon' => 'bi-envelope'],
                            'Sakit' => ['class' => 'warning', 'icon' => 'bi-heart'],
                            'Alpa' => ['class' => 'danger', 'icon' => 'bi-x-circle'],
                        ];
                    @endphp
                    <div class="row g-2 mb-3">
                        @foreach ($summaryConfig as $status => $cfg)
                            <div class="col-6">
                                <div class="rounded-3 p-2 bg-{{ $cfg['class'] }}-subtle text-{{ $cfg['class'] }}">
                                    <i class="bi {{ $cfg['icon'] }} d-block mb-1"></i>
                                    <div class="fw-bold">{{ $summary[$status] }}</div>
                                    <div class="small">{{ $status }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Persentase kehadiran --}}
                    @if ($totalSemua > 0)
                        <div class="text-start">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted fw-medium">Kehadiran</small>
                                <small class="fw-bold text-{{ $persenColor }}">{{ $persenHadir }}%</small>
                            </div>
                            <div class="progress mb-2" style="height:6px;">
                                <div class="progress-bar bg-{{ $persenColor }}" style="width:{{ $persenHadir }}%"></div>
                            </div>
                            <div
                                class="alert alert-{{ $insightColor == 'primary' ? 'primary' : $insightColor }} py-2 px-3 mb-0 rounded-3 text-start">
                                <small class="fw-semibold">
                                    <i class="bi bi-lightbulb me-1"></i>{{ $insightText }}
                                </small>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ② KOLOM KANAN: Riwayat Absensi --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3" id="riwayat">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Absensi
                            </h6>
                            @if ($selectedYear)
                                <span class="badge rounded-pill bg-primary-subtle text-primary">
                                    {{ $selectedYear->tahun }} - {{ $selectedYear->semester }}
                                </span>
                            @endif
                        </div>

                        {{-- Filter status --}}
                        <div class="d-flex gap-2 overflow-auto pb-1">
                            @php
                                $filters = [
                                    null => 'Semua',
                                    'Hadir' => 'Hadir',
                                    'Izin' => 'Izin',
                                    'Sakit' => 'Sakit',
                                    'Alpa' => 'Alpa',
                                ];
                            @endphp
                            @foreach ($filters as $val => $label)
                                <a href="{{ route('guru.siswa.detail', $student->id) }}?academic_year_id={{ $selectedYear?->id }}&status={{ $val }}#riwayat"
                                    class="btn btn-sm flex-shrink-0 {{ $statusFilter === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    {{ $label }}
                                    @if ($val && $summary[$val] > 0)
                                        <span class="badge bg-white text-dark ms-1">{{ $summary[$val] }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 border-0 text-muted small fw-bold">TANGGAL</th>
                                        <th class="py-3 border-0 text-muted small fw-bold">MATA PELAJARAN</th>
                                        <th class="py-3 border-0 text-muted small fw-bold">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendanceHistory as $history)
                                        @php
                                            $status = $history->status;
                                            $cfg = $summaryConfig[$status] ?? [
                                                'class' => 'secondary',
                                                'icon' => 'bi-question-circle',
                                            ];
                                            $schedule = $history->attendance?->schedule;
                                            $subject = $schedule?->subject;
                                            $kelas = $schedule?->classroom;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 text-nowrap">
                                                <div class="fw-semibold small">
                                                    {{ $history->attendance?->tanggal
                                                        ? \Carbon\Carbon::parse($history->attendance->tanggal)->translatedFormat('d F Y')
                                                        : '-' }}
                                                </div>
                                                @if ($schedule)
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($schedule->jam_mulai)->format('H:i') }}
                                                        &ndash;
                                                        {{ \Carbon\Carbon::parse($schedule->jam_habis)->format('H:i') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold small text-dark">
                                                    {{ $subject?->nama_mapel ?? '-' }}
                                                </div>
                                                @if ($kelas)
                                                    <small class="text-muted">
                                                        Kelas {{ $kelas->tingkat }}-{{ $kelas->paralel }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill bg-{{ $cfg['class'] }}-subtle text-{{ $cfg['class'] }} px-3 py-2">
                                                    <i class="bi {{ $cfg['icon'] }} me-1"></i>{{ $status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted small">
                                                <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                                Belum ada riwayat absensi
                                                @if ($statusFilter)
                                                    dengan status "{{ $statusFilter }}"
                                                @endif
                                                pada tahun ajaran ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($attendanceHistory->hasPages())
                        <div class="card-footer bg-white border-top py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $attendanceHistory->firstItem() }}–{{ $attendanceHistory->lastItem() }}
                                    dari {{ $attendanceHistory->total() }} riwayat
                                </small>
                                {{ $attendanceHistory->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
