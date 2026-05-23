@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')

@section('content')
    <div class="container py-4">

        {{-- Header - PAKAI CLASS bg-gradient-header --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow">
            <div class="card-body px-4 py-4">
                <h5 class="fw-bold mb-1 text-white">Jadwal Pelajaran</h5>
                <p class="mb-0 text-white opacity-75 small">Pilih kelas untuk melihat jadwal</p>
            </div>
        </div>

        {{-- List Kelas per Tingkat --}}
        @foreach ($classrooms as $tingkat => $kelasList)
            <div class="card border-0 shadow rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <span class="text-muted small fw-semibold">TINGKAT {{ $tingkat }}</span>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($kelasList as $k)
                        <a href="{{ route('admin.jadwal.show', $k->id) }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:40px;height:40px;font-size:16px;font-weight:700;">
                                    {{ $k->paralel }}
                                </div>
                                <span class="fw-semibold">Kelas {{ $k->tingkat }} {{ $k->paralel }}</span>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    @include('components.scripts')
@endpush
