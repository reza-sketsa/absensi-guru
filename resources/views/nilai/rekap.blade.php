@extends('welcome')

@section('title', 'Rekap Nilai Siswa')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0">Rekap Nilai: {{ $student->nama }}</h4>
            <p class="text-muted mb-0">Kelas: {{ $student->classroom->tingkat }} {{ $student->classroom->paralel }}</p>
        </div>
        <a href="{{ route('students.data') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <small>Rata-Rata Nilai</small>
                    <h2 class="mb-0 fw-bold">{{ $statistik['rata_rata'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Nama Penilaian</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->evaluations as $item)
                            @php
                                $badgeClass = $item->nilai < 75 ? 'bg-danger' : 'bg-success';
                            @endphp
                            <tr>
                                <td class="ps-3 fw-bold">
                                    {{ optional($item->evaluation)->nama_penilaian ?? '-' }}
                                </td>
                                <td>
                                    {{ optional(optional($item->evaluation)->subject)->nama_mapel ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $item->nilai }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('nilai.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    Belum ada data nilai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
