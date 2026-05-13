@extends('layouts.app')

@section('title', 'Manajemen Data Guru')

@section('content')
    <div class="container pb-5">
        {{-- Header: Stackable di Mobile --}}
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Data Guru</h4>
                        <p class="mb-0 opacity-75 small">Total: {{ $teachers->count() }} Guru terdaftar</p>
                    </div>
                    <a href="{{ route('admin.guru.create') }}" class="btn btn-light shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Guru
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <input type="text" id="searchGuru" class="form-control" placeholder="Cari nama atau NIP guru...">
        </div>

        <div class="card border-0 shadow-sm overflow-hidden"> {{-- Tambah overflow-hidden agar radius card terjaga --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-nowrap"> {{-- Mencegah header pecah di mobile --}}
                                <th class="ps-4 py-3 border-0 text-muted small fw-bold">NAMA / NIP</th>
                                <th class="py-3 border-0 text-muted small fw-bold">JK</th>
                                <th class="py-3 border-0 text-muted small fw-bold d-none d-md-table-cell">KONTAK</th>
                                <th class="py-3 border-0 text-center text-muted small fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $teacher->nama_guru }}</div>
                                        <small class="text-muted">{{ $teacher->nip ?? '-' }}</small>
                                    </td>
                                    <td class="text-nowrap"> {{-- Mencegah badge terpotong --}}
                                        <span
                                            class="badge {{ $teacher->jk == 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} border-0">
                                            {{ $teacher->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap small text-muted d-none d-md-table-cell">
                                        <i class="bi bi-telephone me-1"></i>{{ $teacher->no_telp }}
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button type="button" class="btn btn-sm btn-light border"
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $teacher->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <a href="{{ route('admin.guru.edit', $teacher->id) }}"
                                                class="btn btn-sm btn-light text-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form id="form-hapus-{{ $teacher->id }}"
                                                action="{{ route('admin.guru.destroy', $teacher->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus"
                                                    data-id="{{ $teacher->id }}" data-nama="{{ $teacher->nama_guru }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Detail (Tetap sama seperti kodemu) --}}
                                <div class="modal fade" id="detailModal{{ $teacher->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="fw-bold">Detail Guru</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card border-0 bg-light">
                                                    <div class="card-body">
                                                        <div class="text-center mb-3">
                                                            <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                                style="width: 60px; height: 60px; font-size: 24px;">
                                                                {{ strtoupper(substr($teacher->nama_guru, 0, 1)) }}
                                                            </div>
                                                            <h5 class="fw-bold mb-0">{{ $teacher->nama_guru }}</h5>
                                                            <p class="text-muted small">NIP: {{ $teacher->nip ?? '-' }}</p>
                                                        </div>
                                                        <hr class="opacity-10">
                                                        <div class="row g-3 text-start">
                                                            <div class="col-6">
                                                                <small class="text-muted d-block small">Agama</small>
                                                                <span class="fw-semibold">{{ $teacher->agama }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block small">Jenis
                                                                    Kelamin</small>
                                                                <span
                                                                    class="fw-semibold">{{ $teacher->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block small">Tanggal
                                                                    Lahir</small>
                                                                <span
                                                                    class="fw-semibold text-nowrap">{{ $teacher->tgl_lahir }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block small">No. Telepon</small>
                                                                <span class="fw-semibold">{{ $teacher->no_telp }}</span>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="text-muted d-block small">Alamat</small>
                                                                <span class="fw-semibold">{{ $teacher->alamat }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('components.scripts')
    @endpush
@endsection
