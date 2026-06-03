@extends('layouts.app')
@section('title', 'Detail Siswa - ' . $student->nama)

@section('content')
    <div class="container py-4 pb-5 mb-4" data-student-id="{{ $student->id }}">

        {{-- Header --}}
        <div class="no-hover card border-0 rounded-3 mb-3 bg-gradient-header">
            <div class="card-body px-4 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('guru.kelas.show', $student->classroom_id) }}"
                            class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Detail Siswa</h5>
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

                    {{-- Summary stat absensi --}}
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

                    {{-- Rekap nilai per mapel (jika ada) --}}
                    @if ($nilaiPerMapel->isNotEmpty())
                        <hr class="my-3">
                        <div class="text-start">
                            <div class="text-muted small fw-semibold mb-2">
                                <i class="bi bi-bar-chart me-1"></i>Rata-rata Nilai
                            </div>
                            @foreach ($nilaiPerMapel as $mapelNama => $stat)
                                @php
                                    $rata = $stat['rata'];
                                    $nilaiColor =
                                        $rata >= 80
                                            ? 'success'
                                            : ($rata >= 70
                                                ? 'primary'
                                                : ($rata >= 60
                                                    ? 'warning'
                                                    : 'danger'));
                                @endphp
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-truncate me-2" style="max-width:130px;"
                                            title="{{ $mapelNama }}">
                                            {{ $mapelNama }}
                                        </small>
                                        <small class="fw-bold text-{{ $nilaiColor }}">{{ $rata }}</small>
                                    </div>
                                    <div class="progress" style="height:5px;">
                                        <div class="progress-bar bg-{{ $nilaiColor }}"
                                            style="width:{{ min($rata, 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>

            {{-- ② KOLOM KANAN: Tab Absensi & Nilai --}}
            <div class="col-md-8">

                {{-- Tab navigation --}}
                <ul class="nav nav-pills mb-3 gap-2" id="detailTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-absensi" data-bs-toggle="pill"
                            data-bs-target="#panel-absensi" type="button" role="tab">
                            <i class="bi bi-calendar-check me-1"></i>Absensi
                            <span class="badge bg-primary ms-1">{{ $totalSemua }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-nilai" data-bs-toggle="pill" data-bs-target="#panel-nilai"
                            type="button" role="tab">
                            <i class="bi bi-journal-text me-1"></i>Nilai
                            <span class="badge bg-secondary ms-1">{{ $evaluationDetails->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="detailTabContent">

                    {{-- ── TAB ABSENSI ── --}}
                    <div class="tab-pane fade show active" id="panel-absensi" role="tabpanel">
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
                                            @if ($val && ($summary[$val] ?? 0) > 0)
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
                                                            <i
                                                                class="bi {{ $cfg['icon'] }} me-1"></i>{{ $status }}
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
                    </div>{{-- end tab absensi --}}

                    {{-- ── TAB NILAI ── --}}
                    <div class="tab-pane fade" id="panel-nilai" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="bi bi-journal-text text-success me-2"></i>Riwayat Nilai
                                    </h6>
                                    @if ($selectedYear)
                                        <span class="badge rounded-pill bg-success-subtle text-success">
                                            {{ $selectedYear->tahun }} - {{ $selectedYear->semester }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body p-0">
                                @if ($evaluationDetails->isNotEmpty())
                                    {{-- Group by mata pelajaran --}}
                                    @foreach ($evaluationDetails->groupBy(fn($d) => $d->evaluation?->subject?->nama_mapel ?? 'Lainnya') as $mapelNama => $details)
                                        @php
                                            $rataMapel = round($details->avg('nilai'), 1);
                                            $mapelColor =
                                                $rataMapel >= 80
                                                    ? 'success'
                                                    : ($rataMapel >= 70
                                                        ? 'primary'
                                                        : ($rataMapel >= 60
                                                            ? 'warning'
                                                            : 'danger'));
                                        @endphp

                                        {{-- Sub-header per mapel --}}
                                        <div
                                            class="d-flex align-items-center justify-content-between px-4 py-2 bg-light border-bottom">
                                            <span class="fw-semibold small text-dark">
                                                <i class="bi bi-book me-1 text-muted"></i>{{ $mapelNama }}
                                            </span>
                                            <span
                                                class="badge bg-{{ $mapelColor }}-subtle text-{{ $mapelColor }} px-2 py-1">
                                                Rata-rata: {{ $rataMapel }}
                                            </span>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead>
                                                    <tr class="bg-white border-bottom">
                                                        <th class="ps-4 py-2 border-0 text-muted small fw-bold">TANGGAL
                                                        </th>
                                                        <th class="py-2 border-0 text-muted small fw-bold">NAMA PENILAIAN
                                                        </th>
                                                        <th class="py-2 border-0 text-muted small fw-bold">JENIS</th>
                                                        <th class="py-2 pe-4 border-0 text-muted small fw-bold text-end">
                                                            NILAI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($details->sortByDesc(fn($d) => $d->evaluation?->tanggal) as $detail)
                                                        @php
                                                            $nilai = $detail->nilai;
                                                            $nilaiColor =
                                                                $nilai >= 80
                                                                    ? 'success'
                                                                    : ($nilai >= 70
                                                                        ? 'primary'
                                                                        : ($nilai >= 60
                                                                            ? 'warning'
                                                                            : 'danger'));
                                                            $eval = $detail->evaluation;
                                                        @endphp
                                                        <tr>
                                                            <td class="ps-4 text-nowrap">
                                                                <div class="fw-semibold small">
                                                                    {{ $eval?->tanggal ? \Carbon\Carbon::parse($eval->tanggal)->translatedFormat('d F Y') : '-' }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fw-semibold small text-dark">
                                                                    {{ $eval?->nama_penilaian ?? '-' }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if ($eval?->jenis)
                                                                    <span
                                                                        class="badge rounded-pill bg-secondary-subtle text-secondary px-2 py-1 small">
                                                                        {{ $eval->jenis }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted small">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="pe-4 text-end">
                                                                <span
                                                                    class="badge bg-{{ $nilaiColor }}-subtle text-{{ $nilaiColor }} px-3 py-2 fw-bold">
                                                                    {{ $nilai }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 text-muted small">
                                        <i class="bi bi-journal-x display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data nilai pada tahun ajaran ini.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>{{-- end tab nilai --}}

                </div>{{-- end tab-content --}}
            </div>{{-- end col kanan --}}
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
