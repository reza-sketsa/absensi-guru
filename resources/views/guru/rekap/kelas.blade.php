@extends('layouts.app')
@section('title', 'Rekap Kelas ' . $classroom->tingkat . '-' . $classroom->paralel)

@section('content')
    <div class="container py-4 pb-5 mb-4">

        {{-- Header --}}
        <div class="card border-0 rounded-3 mb-3" style="background: linear-gradient(135deg, #0d6efd, #3d8bfd);">
            <div class="card-body px-4 py-3">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('guru.dashboard', ['filter' => $filter]) }}"
                            class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">
                                Rekap Kelas {{ $classroom->tingkat }}-{{ $classroom->paralel }}
                            </h5>
                            <p class="mb-0 text-white opacity-75 small">
                                {{ $rekapSiswa->count() }} siswa &mdash; Periode {{ ucfirst($filter) }}
                            </p>
                        </div>
                    </div>

                    {{-- Stat ringkas di header --}}
                    @php
                        $totalH = $rekapSiswa->sum('hadir');
                        $totalA = $rekapSiswa->sum('alpa');
                        $totalI = $rekapSiswa->sum('izin');
                        $totalS = $rekapSiswa->sum('sakit');
                        $grandTotal = $totalH + $totalA + $totalI + $totalS;
                        $persenKelas = $grandTotal > 0 ? round(($totalH / $grandTotal) * 100) : 0;
                    @endphp
                    <div class="text-end flex-shrink-0 d-none d-md-block">
                        <div class="fw-bold text-white fs-4 lh-1">{{ $persenKelas }}%</div>
                        <small class="text-white opacity-75">kehadiran kelas</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Persentase kelas di mobile --}}
        <div class="d-md-none mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body py-3 px-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-medium">Kehadiran Kelas</small>
                    @php
                        $pc = $persenKelas;
                        $cc = $pc >= 80 ? 'success' : ($pc >= 50 ? 'warning' : 'danger');
                    @endphp
                    <span class="fw-bold text-{{ $cc }}">{{ $persenKelas }}%</span>
                </div>
            </div>
        </div>

        {{-- Rekap Seluruh Siswa --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people text-primary me-2"></i>Rekap Seluruh Siswa
                </h6>
            </div>

            {{-- DESKTOP: Tabel --}}
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 text-muted small fw-bold">NAMA / NIS</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">H</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">I</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">S</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">A</th>
                                <th class="py-3 border-0 text-muted small fw-bold pe-4">KEHADIRAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapSiswa as $siswa)
                                @php
                                    $total = $siswa->hadir + $siswa->izin + $siswa->sakit + $siswa->alpa;
                                    $persen = $total > 0 ? round(($siswa->hadir / $total) * 100) : 0;
                                    $color = $persen >= 80 ? 'success' : ($persen >= 50 ? 'warning' : 'danger');
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $siswa->nama }}</div>
                                        <small class="text-muted">{{ $siswa->nis }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success-subtle text-success">
                                            {{ $siswa->hadir }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary-subtle text-primary">
                                            {{ $siswa->izin }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-warning-subtle text-warning">
                                            {{ $siswa->sakit }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger-subtle text-danger">
                                            {{ $siswa->alpa }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:5px;">
                                                <div class="progress-bar bg-{{ $color }}"
                                                    style="width:{{ $persen }}%"></div>
                                            </div>
                                            <small class="fw-bold text-{{ $color }} text-nowrap">
                                                {{ $persen }}%
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE: Card list --}}
            <div class="d-md-none">
                @forelse($rekapSiswa as $siswa)
                    @php
                        $total = $siswa->hadir + $siswa->izin + $siswa->sakit + $siswa->alpa;
                        $persen = $total > 0 ? round(($siswa->hadir / $total) * 100) : 0;
                        $color = $persen >= 80 ? 'success' : ($persen >= 50 ? 'warning' : 'danger');
                    @endphp
                    <div class="{{ !$loop->last ? 'border-bottom' : '' }} px-4 py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-semibold text-dark small">{{ $siswa->nama }}</div>
                                <small class="text-muted">{{ $siswa->nis }}</small>
                            </div>
                            <span class="fw-bold text-{{ $color }} small">{{ $persen }}%</span>
                        </div>
                        <div class="progress mb-2" style="height:4px;">
                            <div class="progress-bar bg-{{ $color }}" style="width:{{ $persen }}%"></div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge rounded-pill bg-success-subtle text-success">H: {{ $siswa->hadir }}</span>
                            <span class="badge rounded-pill bg-primary-subtle text-primary">I: {{ $siswa->izin }}</span>
                            <span class="badge rounded-pill bg-warning-subtle text-warning">S: {{ $siswa->sakit }}</span>
                            <span class="badge rounded-pill bg-danger-subtle text-danger">A: {{ $siswa->alpa }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">
                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                        Belum ada data siswa.
                    </div>
                @endforelse
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
