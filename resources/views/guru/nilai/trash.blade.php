@extends('layouts.app')

@section('title', 'Recycle Bin - Nilai')

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.evaluations.index') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">
                            <i class="bi bi-trash3 me-2"></i>Keranjang Sampah
                        </h5>
                        <p class="mb-0 text-white opacity-75 small">Data penilaian yang telah dihapus</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-semibold">NAMA PENILAIAN</th>
                                <th class="py-3 text-muted small fw-semibold">MATA PELAJARAN</th>
                                <th class="py-3 text-muted small fw-semibold">KELAS</th>
                                <th class="py-3 text-muted small fw-semibold">TGL DIHAPUS</th>
                                <th class="py-3 text-center text-muted small fw-semibold pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedEvaluations as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $item->nama_penilaian }}</div>
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary mt-1">
                                            {{ $item->jenis }}
                                        </span>
                                    </td>
                                    <td>{{ $item->subject->nama_mapel ?? '-' }}</td>
                                    <td>{{ $item->classroom->tingkat ?? '-' }}-{{ $item->classroom->paralel ?? '-' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $item->deleted_at->diffForHumans() }}</small>
                                        <br>
                                        <small class="text-muted" style="font-size: 0.65rem;">
                                            {{ $item->deleted_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form action="{{ route('guru.evaluations.restore', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-outline-success btn-sm btn-restore"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama_penilaian }}">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('guru.evaluations.force-delete', $item->id) }}"
                                                method="POST" class="d-inline" id="form-force-delete-{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm btn-force-delete"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama_penilaian }}">
                                                    <i class="bi bi-x-circle me-1"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert untuk Restore
            document.querySelectorAll('.btn-restore').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Pulihkan Data?',
                        text: `Penilaian "${nama}" akan dikembalikan.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Pulihkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // SweetAlert untuk Force Delete (Hapus Permanen)
            document.querySelectorAll('.btn-force-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const form = document.getElementById(`form-force-delete-${id}`);

                    Swal.fire({
                        title: 'Hapus Permanen?',
                        html: `Penilaian <strong>"${nama}"</strong> akan dihapus secara permanen.<br>Data tidak dapat dikembalikan!`,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus Permanen!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
