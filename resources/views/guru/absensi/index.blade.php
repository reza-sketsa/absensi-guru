@extends('layouts.app')

@section('title', 'Absensi Guru')

@section('content')
    <div class="container py-4">

        {{-- Welcome Card - Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <h5 class="fw-bold mb-1 text-white">Selamat Datang, Guru!</h5>
                <p class="mb-0 text-white opacity-75 small">Silakan pilih jadwal kelas untuk melakukan absensi siswa.</p>
            </div>
        </div>

        {{-- Filter Hari --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                <a href="?hari={{ $hari }}"
                    class="btn btn-sm rounded-pill {{ $selectedDay == $hari ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $hari }}
                </a>
            @endforeach
        </div>

        {{-- Jadwal Mengajar --}}
        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-calendar3 me-2"></i>Jadwal Mengajar Hari Ini
        </h6>

        <div class="row g-4 mb-5">
            @forelse($schedules as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow rounded-3 h-100">
                        <div class="card-body p-3">
                            <span class="badge rounded-pill bg-primary-subtle text-primary mb-2 px-3 py-1">
                                <i class="bi bi-book me-1"></i>Mata Pelajaran
                            </span>
                            <h6 class="fw-bold mb-1">{{ $item->subject->nama_mapel }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-door-open me-1"></i>
                                Kelas {{ $item->classroom->tingkat }}-{{ $item->classroom->paralel }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-calendar2 me-1"></i>{{ $item->hari }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($item->jam_habis)->format('H:i') }}
                            </p>
                            <hr class="my-2">
                            @if ($isToday)
                                @if ($item->sudah_absen)
                                    <a href="{{ route('guru.absensi.edit', $item->id) }}"
                                        class="btn btn-warning w-100 btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i>Edit Absen
                                    </a>
                                @else
                                    <a href="{{ route('guru.absensi.create', $item->id) }}"
                                        class="btn btn-primary w-100 btn-sm">
                                        <i class="bi bi-calendar-check me-1"></i>Mulai Absen
                                    </a>
                                @endif
                            @else
                                <button class="btn btn-secondary w-100 btn-sm" disabled>
                                    <i class="bi bi-calendar-x me-1"></i>Absen Tidak Tersedia
                                </button>
                            @endif
                            <a href="{{ route('guru.absensi.history', $item->id) }}"
                                class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="bi bi-clock-history me-1"></i>History Absensi
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body py-5 text-center">
                            <i class="bi bi-calendar-x display-6 d-block mb-2 opacity-50 text-muted"></i>
                            <p class="text-muted small mb-0">Jadwal tidak ditemukan untuk hari ini.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Ringkasan Absensi Terakhir --}}
        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-clock-history me-2"></i>Ringkasan Absensi Terakhir
        </h6>

        <div class="card border-0 shadow rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-semibold">TANGGAL</th>
                            <th class="py-3 text-muted small fw-semibold">MAPEL / KELAS</th>
                            <th class="text-center py-3 text-muted small fw-semibold">H</th>
                            <th class="text-center py-3 text-muted small fw-semibold">I</th>
                            <th class="text-center py-3 text-muted small fw-semibold">S</th>
                            <th class="text-center py-3 text-muted small fw-semibold pe-4">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_attendances as $ra)
                            <tr>
                                <td class="ps-4 small">{{ \Carbon\Carbon::parse($ra->tanggal)->format('d/m/y') }}</td>
                                <td>
                                    <div class="fw-semibold small">{{ $ra->schedule->subject->nama_mapel }}</div>
                                    <small
                                        class="text-muted">{{ $ra->schedule->classroom->tingkat }}-{{ $ra->schedule->classroom->paralel }}</small>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-success-subtle text-success px-2">{{ $ra->h }}</span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-primary-subtle text-primary px-2">{{ $ra->i }}</span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-warning-subtle text-warning px-2">{{ $ra->s }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <span
                                        class="badge rounded-pill bg-danger-subtle text-danger px-2">{{ $ra->a }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted small">
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
@endsection
