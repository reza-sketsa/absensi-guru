@extends('layouts.app')

@section('title', 'Edit Absensi - ' . $schedule->subject->nama_mapel)

@section('content')
    <div class="container py-4">

        {{-- Alert error --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert"
                style="border-left: 4px solid #dc2626; background-color: #fef2f2;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    <div>
                        <strong class="small">Gagal menyimpan data:</strong>
                        <ul class="mb-0 mt-1 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-white">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-door-open me-1"></i> Kelas
                            {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                    </div>
                    <div class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Absensi
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white opacity-50">
                        <i class="bi bi-calendar me-1"></i> Tanggal:
                        {{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}
                    </small>
                </div>
            </div>
        </div>

        <h6 class="fw-semibold text-primary mb-3">
            <i class="bi bi-people me-2"></i>Daftar Siswa
        </h6>

        <form action="{{ route('guru.absensi.update', $schedule->id) }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="tanggal" value="{{ $attendance->tanggal }}">

            @forelse ($students as $index => $student)
                @php $currentStatus = $statusMap[$student->id] ?? 'Hadir'; @endphp
                <div class="card border-0 shadow rounded-3 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">{{ $student->nama }}</div>
                                <small class="text-muted">NIS: {{ $student->nis }}</small>
                            </div>

                            <input type="hidden" name="absensi[{{ $index }}][student_id]"
                                value="{{ $student->id }}">

                            {{-- Status Absensi - Desktop: teks lengkap, Mobile: singkatan --}}
                            <div class="btn-group" role="group">
                                @foreach (['Hadir' => 'success', 'Izin' => 'primary', 'Sakit' => 'warning', 'Alpa' => 'danger'] as $status => $color)
                                    <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                        id="{{ strtolower($status) }}-{{ $student->id }}" value="{{ $status }}"
                                        {{ $currentStatus === $status ? 'checked' : '' }}>
                                    <label class="btn btn-outline-{{ $color }} px-2 px-sm-3"
                                        for="{{ strtolower($status) }}-{{ $student->id }}">
                                        <span class="d-none d-sm-inline">{{ $status }}</span>
                                        <span class="d-sm-inline d-md-none">{{ substr($status, 0, 1) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow rounded-3">
                    <div class="card-body py-5 text-center">
                        <i class="bi bi-people display-6 d-block mb-2 opacity-50 text-muted"></i>
                        <p class="text-muted small mb-0">Belum ada data siswa di kelas ini.</p>
                    </div>
                </div>
            @endforelse

            <div class="d-flex flex-column gap-2 mt-4">
                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                    <i class="bi bi-check2-all me-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('guru.absensi') }}" class="btn btn-outline-secondary py-2">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
