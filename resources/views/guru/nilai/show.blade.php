@extends('layouts.app')

@section('title', 'Detail Penilaian - ' . $evaluation->nama_penilaian)

@section('content')
    <div class="container py-4">

        {{-- Alert Warning untuk data yang sudah dihapus --}}
        @if ($evaluation->trashed())
            <div class="alert alert-warning alert-dismissible fade show rounded-3 mb-4" role="alert"
                style="border-left: 4px solid #f59e0b; background-color: #fefce8;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <div class="small">Data penilaian ini telah <strong>dihapus</strong>.</div>
                    </div>
                    <form action="{{ route('guru.evaluations.restore', $evaluation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-arrow-repeat me-1"></i>Pulihkan
                        </button>
                    </form>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.evaluations.index') }}" class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">{{ $evaluation->nama_penilaian }}</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            @if ($evaluation->classroom)
                                {{ $evaluation->classroom->tingkat }}-{{ $evaluation->classroom->paralel }}
                            @elseif ($evaluation->schedule && $evaluation->schedule->classroom)
                                {{ $evaluation->schedule->classroom->tingkat }}-{{ $evaluation->schedule->classroom->paralel }}
                            @else
                                -
                            @endif
                            | {{ $evaluation->subject->nama_mapel ?? '-' }}
                        </p>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $evaluation->tanggal->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-semibold" width="60">NO</th>
                                <th class="py-3 text-muted small fw-semibold">NAMA SISWA</th>
                                <th class="py-3 text-center text-muted small fw-semibold pe-4" width="120">NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($evaluation->details as $index => $detail)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $detail->student->nama }}</div>
                                        <small class="text-muted">NIS: {{ $detail->student->nis }}</small>
                                    </td>
                                    <td class="text-center pe-4">
                                        <span
                                            class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 fs-6 fw-semibold">
                                            {{ $detail->nilai }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted small">
                                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada data nilai untuk penilaian ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (!$evaluation->trashed())
                <div class="card-footer bg-white border-0 pb-4 pt-2 px-4">
                    <div class="d-flex gap-3">
                        <a href="{{ route('guru.evaluations.edit', $evaluation->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit Nilai
                        </a>
                        <form action="{{ route('guru.evaluations.destroy', $evaluation->id) }}" method="POST"
                            class="d-inline" id="form-hapus-{{ $evaluation->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-hapus-penilaian"
                                data-id="{{ $evaluation->id }}" data-nama="{{ $evaluation->nama_penilaian }}">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert untuk Hapus Penilaian
            const btnHapus = document.querySelector('.btn-hapus-penilaian');
            if (btnHapus) {
                btnHapus.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    Swal.fire({
                        title: 'Hapus Penilaian?',
                        text: `"${nama}" akan dihapus permanen.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`form-hapus-${id}`).submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
