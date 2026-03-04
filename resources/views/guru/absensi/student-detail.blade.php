@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('guru.dashboard') }}" class="btn btn-sm btn-light shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="row">
            {{-- Profil Siswa --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; fontSize: 2rem;">
                            {{ substr($student->nama, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="fw-bold">{{ $student->nama }}</h5>
                    <p class="text-muted">{{ $student->classroom->nama_kelas ?? 'Tanpa Kelas' }}</p>
                    <hr>
                    <div class="row g-2">
                        @foreach ($summary as $status => $count)
                            <div class="col-6 text-start">
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
                        <h6 class="fw-bold mb-4">Riwayat Absensi Lengkap</h6>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
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
                                            <td class="small text-muted">{{ $history->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">Belum ada riwayat absensi.</td>
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
