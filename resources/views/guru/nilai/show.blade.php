@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        @if ($evaluation->trashed())
            <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0 mb-4">
                <span><i class="bi bi-exclamation-triangle-fill"></i> Data penilaian ini telah
                    <strong>dihapus</strong>.</span>
                <form action="{{ route('guru.evaluations.restore', $evaluation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Pulihkan Sekarang</button>
                </form>
            </div>
        @endif

        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.evaluations.index') }}" class="btn btn-outline-light border-0 btn-sm">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $evaluation->nama_penilaian }}</h5>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            @if ($evaluation->classroom)
                                {{ $evaluation->classroom->tingkat }}-{{ $evaluation->classroom->paralel }}
                            @else
                                {{ $evaluation->schedule->classroom->tingkat ?? '' }}-{{ $evaluation->schedule->classroom->paralel ?? '' }}
                            @endif
                            | {{ $evaluation->subject->nama_mapel ?? '-' }}
                        </p>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $evaluation->tanggal->format('d-m-Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evaluation->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->student->nama }}</td>
                                    <td><strong>{{ $detail->nilai }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (!$evaluation->trashed())
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('guru.evaluations.edit', $evaluation->id) }}"
                            class="btn btn-warning text-white flex-fill">
                            <i class="bi bi-pencil"></i> Edit Nilai
                        </a>
                        <form action="{{ route('guru.evaluations.destroy', $evaluation->id) }}" method="POST"
                            onsubmit="return confirm('Hapus penilaian ini?')" class="m-0 flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
