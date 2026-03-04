@extends('layouts.app')

@section('title', 'Recycle Bin - Nilai')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-trash3 text-danger"></i> Keranjang Sampah Nilai</h4>
        <a href="{{ route('students.data') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Penilaian</th>
                        <th>Nilai Lama</th>
                        <th>Tgl Dihapus</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trashedScores as $item)
                        <tr>
                            <td>{{ $item->student->nama }}</td>
                            <td>{{ $item->evaluation->nama_penilaian }}</td>
                            <td><span class="badge bg-secondary">{{ $item->nilai }}</span></td>
                            <td>{{ $item->deleted_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <form action="{{ route('trash.restore', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success btn-sm" title="Kembalikan Data">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>

                                <form action="{{ route('trash.force-delete', $item->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Peringatan: Data akan dihapus selamanya!')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Hapus Permanen">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data di keranjang sampah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
