@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container py-3 py-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-light border-0 btn-sm">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>

                        <h4 class="fw-bold mb-1">{{ $classroom->tingkat }} {{ $classroom->paralel }}</h4>
                        <p class="mb-0 opacity-75 small">{{ count($schedules) }} jadwal — Hari {{ $hari }}</p>
                    </div>
                    <button class="btn btn-light btn-sm shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#modalTambahJadwal">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Jadwal
                    </button>
                </div>
            </div>
        </div>

        {{-- Filter Hari --}}
        <div class="d-flex gap-2 flex-wrap mb-3">

            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                <a href="{{ route('admin.jadwal.show', [$classroom->id, 'hari' => $h]) }}"
                    class="btn btn-sm {{ $hari == $h ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $h }}
                </a>
            @endforeach
        </div>

        {{-- Tabel Jadwal --}}
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Jam</th>
                            <th>Mapel & Guru</th>
                            <th class="text-center px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $j)
                            <tr>
                                <td class="text-nowrap">
                                    <div class="small fw-bold">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                                    </div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($j->jam_habis)->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $j->nama_mapel }}</div>
                                    <div class="text-muted small">{{ $j->nama_guru }}</div>
                                </td>
                                <td class="text-center px-4">
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal"
                                        data-bs-target="#modalEditJadwal{{ $j->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus jadwal ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                    Belum ada jadwal untuk hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambahJadwal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Hari</label>
                        <select name="hari" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Jam Selesai</label>
                            <input type="time" name="jam_habis" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mata Pelajaran</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Mapel --</option>
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Guru Pengajar</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Guru --</option>
                            @foreach ($teachers as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light w-25" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary flex-fill">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    @foreach ($schedules as $j)
        <div class="modal fade" id="modalEditJadwal{{ $j->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <form action="{{ route('admin.jadwal.update', $j->id) }}" method="POST" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Hari</label>
                            <select name="hari" class="form-select" required>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                    <option value="{{ $h }}" {{ $j->hari == $h ? 'selected' : '' }}>
                                        {{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" value="{{ $j->jam_mulai }}"
                                    required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Jam Selesai</label>
                                <input type="time" name="jam_habis" class="form-control" value="{{ $j->jam_habis }}"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mata Pelajaran</label>
                            <select name="subject_id" class="form-select" required>
                                @foreach ($subjects as $s)
                                    <option value="{{ $s->id }}" {{ $j->subject_id == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Guru Pengajar</label>
                            <select name="teacher_id" class="form-select" required>
                                @foreach ($teachers as $g)
                                    <option value="{{ $g->id }}" {{ $j->teacher_id == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama_guru }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light w-25" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary flex-fill">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @include('components.scripts')
@endsection
