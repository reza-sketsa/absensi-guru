@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $schedule->subject->nama_mapel }}</h2>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-door-open me-1"></i> Kelas:
                            {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-white text-primary px-3 py-2 rounded-pill">
                            <i class="bi bi-pencil me-1"></i> Edit Absensi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-people me-2"></i>Daftar Siswa</h5>

        <form action="{{ route('guru.absensi.update', $schedule->id) }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="tanggal" value="{{ $attendance->tanggal }}">

            @forelse ($students as $index => $student)
                @php $currentStatus = $statusMap[$student->id] ?? 'Hadir'; @endphp
                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">{{ $student->nama }}</div>
                            <div class="text-muted small">NIS: {{ $student->nis }}</div>
                        </div>

                        <input type="hidden" name="absensi[{{ $index }}][student_id]" value="{{ $student->id }}">

                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                id="h-{{ $student->id }}" value="Hadir"
                                {{ $currentStatus === 'Hadir' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success px-3" for="h-{{ $student->id }}">H</label>

                            <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                id="i-{{ $student->id }}" value="Izin"
                                {{ $currentStatus === 'Izin' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary px-3" for="i-{{ $student->id }}">I</label>

                            <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                id="s-{{ $student->id }}" value="Sakit"
                                {{ $currentStatus === 'Sakit' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning px-3" for="s-{{ $student->id }}">S</label>

                            <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                id="a-{{ $student->id }}" value="Alpa"
                                {{ $currentStatus === 'Alpa' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger px-3" for="a-{{ $student->id }}">A</label>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    Belum ada data siswa di kelas ini.
                </div>
            @endforelse

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 shadow-sm py-3 fw-bold text-white">
                    <i class="bi bi-check2-all me-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('guru.absensi') }}" class="btn btn-link w-100 text-muted mt-2">Batal</a>
            </div>
        </form>
    </div>
@endsection
