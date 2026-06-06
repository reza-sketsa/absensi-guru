@extends('layouts.app')

@section('title', 'Preview Kenaikan Kelas')

@section('content')
    <div class="container py-4 pb-5 mb-4">

        {{-- Header --}}
        <div class="card border-0 rounded-3 mb-4 bg-gradient-header">
            <div class="card-body px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.kelas.promote') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Preview Kenaikan Kelas</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            Periksa kembali sebelum dieksekusi &mdash; proses ini tidak bisa dibatalkan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Warning banner --}}
        <div class="alert alert-warning rounded-3 d-flex gap-2 align-items-start mb-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
            <div>
                <div class="fw-semibold">Perhatikan sebelum eksekusi</div>
                <small>Setelah dieksekusi, <strong>classroom_id</strong> siswa akan berubah dan riwayat perpindahan akan
                    tercatat permanen. Proses ini <strong>tidak dapat dibatalkan</strong>.</small>
            </div>
        </div>

        {{-- Summary cards --}}
        @php
            $totalSiswa = collect($preview)->sum(fn($p) => $p['students']->count());
            $totalLulus = collect($preview)->where('lulus', true)->sum(fn($p) => $p['students']->count());
            $totalPindah = collect($preview)
                ->where('lulus', false)
                ->where('skip', false)
                ->sum(fn($p) => $p['students']->count());
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <div class="fw-bold fs-4 text-primary">{{ $totalSiswa }}</div>
                    <div class="text-muted small">Total Siswa Diproses</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <div class="fw-bold fs-4 text-success">{{ $totalPindah }}</div>
                    <div class="text-muted small">Naik Kelas</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <div class="fw-bold fs-4 text-warning">{{ $totalLulus }}</div>
                    <div class="text-muted small">Lulus</div>
                </div>
            </div>
        </div>

        {{-- Detail per kelas --}}
        @foreach ($preview as $item)
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold">
                                Kelas {{ $item['from']->tingkat }}-{{ $item['from']->paralel }}
                            </span>
                            <i class="bi bi-arrow-right text-muted"></i>
                            @if ($item['lulus'])
                                <span class="badge bg-warning-subtle text-warning px-2 py-1">
                                    <i class="bi bi-mortarboard me-1"></i>Lulus
                                </span>
                            @elseif ($item['to'])
                                <span class="badge bg-success-subtle text-success px-2 py-1">
                                    Kelas {{ $item['to']->tingkat }}-{{ $item['to']->paralel }}
                                </span>
                            @endif
                        </div>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $item['students']->count() }} siswa
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <tbody>
                                @foreach ($item['students'] as $student)
                                    <tr>
                                        <td class="ps-4 py-2 small fw-semibold">{{ $student->nama }}</td>
                                        <td class="py-2 small text-muted">{{ $student->nis }}</td>
                                        <td class="py-2 pe-4 text-end">
                                            @if ($item['lulus'])
                                                <span class="badge bg-warning-subtle text-warning small">Lulus</span>
                                            @elseif ($item['to'])
                                                <span class="badge bg-success-subtle text-success small">
                                                    → {{ $item['to']->tingkat }}-{{ $item['to']->paralel }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Form eksekusi — kirim ulang mapping yang sama --}}
        <form action="{{ route('admin.kelas.promote.execute') }}" method="POST">
            @csrf
            @foreach ($preview as $item)
                <input type="hidden" name="mapping[{{ $item['from']->id }}]"
                    value="{{ $item['lulus'] ? 'lulus' : $item['to']->id }}">
            @endforeach

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.kelas.promote') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali & Ubah
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-circle me-1"></i>Eksekusi Kenaikan Kelas
                </button>
            </div>
        </form>

    </div>
@endsection
