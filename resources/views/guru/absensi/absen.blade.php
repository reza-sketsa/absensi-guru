@extends('layouts.app')

@section('content')
    <div class="container py-3">
        {{-- Welcome Card --}}
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <h2 class="fw-bold">Selamat Datang, Guru!</h2>
                <p class="mb-0">Silahkan pilih jadwal kelas untuk melakukan absensi siswa.</p>
            </div>
        </div>

        {{-- Jadwal Mengajar --}}
        <h5 class="fw-bold mb-3"><i class="bi bi-calendar3 me-2"></i>Jadwal Mengajar Hari Ini</h5>
        <div class="row mb-5">
            @forelse($schedules as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-light text-primary mb-2">Mata Pelajaran</span>
                            <h5 class="fw-bold">{{ $item->subject->nama_mapel }}</h5>
                            <p class="text-muted small">
                                Kelas: {{ $item->classroom->tingkat }}-{{ $item->classroom->paralel }}
                            </p>
                            <hr>
                            <a href="{{ route('guru.absensi.create', $item->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-check me-2"></i>Mulai Absen
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center border-0 shadow-sm">
                        Jadwal tidak ditemukan untuk hari ini.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Ringkasan Absensi Terakhir --}}
        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Ringkasan Absensi Terakhir</h5>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-4 py-3">TANGGAL</th>
                            <th class="py-3">MAPEL / KELAS</th>
                            <th class="text-center py-3">H</th>
                            <th class="text-center py-3">I</th>
                            <th class="text-center py-3">S</th>
                            <th class="text-center py-3">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_attendances as $ra)
                            <tr>
                                <td class="ps-4">{{ date('d/m/y', strtotime($ra->tanggal)) }}</td>
                                <td>
                                    <div class="fw-bold small">{{ $ra->schedule->subject->nama_mapel }}</div>
                                    <div class="text-muted small">
                                        {{ $ra->schedule->classroom->tingkat }}-{{ $ra->schedule->classroom->paralel }}
                                    </div>
                                </td>
                                <td class="text-center"><span
                                        class="badge bg-success bg-opacity-10 text-success">{{ $ra->h }}</span></td>
                                <td class="text-center"><span
                                        class="badge bg-primary bg-opacity-10 text-primary">{{ $ra->i }}</span></td>
                                <td class="text-center"><span
                                        class="badge bg-warning bg-opacity-10 text-warning">{{ $ra->s }}</span></td>
                                <td class="text-center"><span
                                        class="badge bg-danger bg-opacity-10 text-danger">{{ $ra->a }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
