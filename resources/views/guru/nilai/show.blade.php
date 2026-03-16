@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Tampilkan Alert jika data sedang di tempat sampah --}}
        @if ($evaluation->trashed())
            <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0">
                <span><i class="bi bi-exclamation-triangle-fill"></i> Data penilaian ini telah
                    <strong>dihapus</strong>.</span>
                <form action="{{ route('guru.evaluations.restore', $evaluation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Pulihkan Sekarang</button>
                </form>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="fw-bold mb-0">Detail Nilai: {{ $evaluation->nama_penilaian }}</h4>
                <p class="text-muted mb-0 small">
                    {{ $evaluation->subject->nama_mapel ?? 'Mapel' }} -
                    @if ($evaluation->classroom)
                        {{ $evaluation->classroom->tingkat }}-{{ $evaluation->classroom->paralel }}
                    @else
                        {{ $evaluation->schedule->classroom->tingkat ?? '' }}-{{ $evaluation->schedule->classroom->paralel ?? '' }}
                    @endif
                    ({{ $evaluation->tanggal->format('d-m-Y') }})
                </p>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluation->details()->withTrashed()->get() as $index => $detail)
                            <tr class="{{ $detail->trashed() ? 'table-light text-muted' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $detail->student->nama }}
                                    @if ($detail->trashed())
                                        <span class="badge bg-secondary ms-1">Terhapus</span>
                                    @endif
                                </td>
                                <td><strong>{{ $detail->nilai }}</strong></td>
                                <td class="text-center">
                                    @if ($detail->trashed())
                                        {{-- Tombol Restore khusus untuk Nilai Siswa ini --}}
                                        <form action="{{ route('guru.evaluations.detail.restore', $detail->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-success p-0">
                                                <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                            </button>
                                        </form>
                                    @else
                                        {{-- Tombol Hapus Individu --}}
                                        <form action="{{ route('guru.evaluations.detail.destroy', $detail->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus nilai untuk siswa {{ $detail->student->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{-- Tombol Kembali di sisi kiri --}}
                    <a href="{{ route('guru.penilaian.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

                    {{-- Kelompok tombol aksi di sisi kanan --}}
                    @if (!$evaluation->trashed())
                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.evaluations.edit', $evaluation->id) }}"
                                class="btn btn-warning text-white">
                                <i class="bi bi-pencil"></i> Edit Nilai
                            </a>

                            <form action="{{ route('guru.evaluations.destroy', $evaluation->id) }}" method="POST"
                                onsubmit="return confirm('Hapus penilaian ini? (Data nilai satu kelas akan ikut tersembunyi)')"
                                class="m-0"> {{-- Tambahkan class m-0 agar margin form tidak merusak alignment --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
