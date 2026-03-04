@extends('welcome')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-4">Daftar Jadwal Mengajar</h4>

        <div class="row">
            {{-- Pastikan variabel ini dikirim dari TeacherController --}}
            @forelse($schedules as $item)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $item->subject->nama_mapel }}</h5>
                            <p class="text-muted">Kelas: {{ $item->classroom->tingkat }}-{{ $item->classroom->paralel }}</p>

                            {{-- Link ini yang akan membawa 'schedule_id' ke halaman absen --}}
                            <a href="{{ route('absensi.create', ['schedule_id' => $item->id]) }}"
                                class="btn btn-primary w-100">
                                Buka Absensi
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Belum ada jadwal mengajar untuk profil Anda.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
