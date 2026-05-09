@extends('layouts.app')

@section('title', 'Recycle Bin - Nilai')

@section('content')
    <div class="container py-3 mb-5">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('guru.evaluations.index') }}" class="btn btn-outline-secondary border-0 btn-sm me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0"><i class="bi bi-trash3 text-danger me-2"></i>Keranjang Sampah Nilai</h5>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Penilaian</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Tgl Dihapus</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedEvaluations as $item)
                                <tr>
                                    <td class="ps-3">
                                        {{ $item->nama_penilaian }}
                                        <span class="badge bg-secondary">{{ $item->jenis }}</span>
                                    </td>
                                    <td>{{ $item->subject->nama_mapel ?? '-' }}</td>
                                    <td>{{ $item->classroom->tingkat ?? '-' }}-{{ $item->classroom->paralel ?? '-' }}</td>
                                    <td>{{ $item->deleted_at->diffForHumans() }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form action="{{ route('guru.evaluations.restore', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('guru.evaluations.force-delete', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Data akan dihapus permanen, tidak bisa dikembalikan!')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tidak ada data di keranjang sampah.
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
