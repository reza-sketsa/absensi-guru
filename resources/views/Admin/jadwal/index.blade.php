@extends('layouts.app')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Jadwal Pelajaran</h4>
                <p class="mb-0 opacity-75 small">Pilih kelas untuk melihat jadwal</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach ($classrooms as $tingkat => $kelasList)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light fw-bold text-muted small py-2 px-3">
                            Kelas {{ $tingkat }}
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($kelasList as $k)
                                <a href="{{ route('admin.jadwal.show', $k->id) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">{{ $k->tingkat }} {{ $k->paralel }}</span>
                                    <i class="bi bi-chevron-right small text-muted"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
