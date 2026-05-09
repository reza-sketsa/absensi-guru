@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="mb-4 d-flex align-items-center">
            <a href="{{ route('guru.absensi') }}" class="btn btn-light border shadow-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold text-primary mb-0">{{ $schedule->subject->nama_mapel }}</h4>
                <p class="text-muted mb-0">Kelas: {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}</p>
            </div>
        </div>

        <form action="{{ route('guru.absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            @foreach ($students as $index => $student)
                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $student->nama }}</div>
                            <div class="text-muted small">NIS: {{ $student->nis }}</div>
                        </div>

                        <input type="hidden" name="absensi[{{ $index }}][student_id]" value="{{ $student->id }}">

                        <div class="btn-group btn-group-sm" role="group">
                            @foreach (['Hadir' => 'success', 'Izin' => 'primary', 'Sakit' => 'warning', 'Alpa' => 'danger'] as $status => $color)
                                <input type="radio" class="btn-check" name="absensi[{{ $index }}][status]"
                                    id="{{ strtolower($status[0]) }}-{{ $student->id }}" value="{{ $status }}"
                                    {{ $status == 'Hadir' ? 'checked' : '' }}>
                                <label class="btn btn-outline-{{ $color }}"
                                    for="{{ strtolower($status[0]) }}-{{ $student->id }}">
                                    {{ $status[0] }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary w-100 mt-4 shadow-sm py-2 fw-bold">
                <i class="bi bi-check2-all me-2"></i>SIMPAN ABSENSI
            </button>
        </form>
    </div>
@endsection
