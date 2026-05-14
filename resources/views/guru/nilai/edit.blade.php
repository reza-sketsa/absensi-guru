@extends('layouts.app')

@section('title', 'Edit Penilaian - ' . $evaluation->nama_penilaian)

@section('content')
    <div class="container py-4">

        {{-- Header Gradient --}}
        <div class="card border-0 rounded-4 mb-4 bg-gradient-header shadow-md">
            <div class="card-body px-4 py-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.evaluations.show', $evaluation->id) }}"
                        class="btn btn-outline-light border-0 btn-sm p-1 lh-1">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Edit: {{ $evaluation->nama_penilaian }}</h5>
                        <p class="mb-0 text-white opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas {{ $evaluation->classroom->tingkat }}-{{ $evaluation->classroom->paralel }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('guru.evaluations.update', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Nama Penilaian</label>
                            <input type="text" name="nama_penilaian" class="form-control"
                                value="{{ $evaluation->nama_penilaian }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Jenis</label>
                            <select name="jenis" class="form-select">
                                <option value="UH" {{ $evaluation->jenis == 'UH' ? 'selected' : '' }}>Ulangan Harian
                                </option>
                                <option value="UTS" {{ $evaluation->jenis == 'UTS' ? 'selected' : '' }}>UTS</option>
                                <option value="UAS" {{ $evaluation->jenis == 'UAS' ? 'selected' : '' }}>UAS</option>
                                <option value="Tugas" {{ $evaluation->jenis == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ $evaluation->tanggal->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-3 text-muted small fw-semibold" width="60">NO</th>
                                    <th class="py-3 text-muted small fw-semibold">NAMA SISWA</th>
                                    <th class="py-3 text-center text-muted small fw-semibold pe-3" width="150">NILAI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $student)
                                    <tr>
                                        <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $student->nama }}</div>
                                            <small class="text-muted">NIS: {{ $student->nis }}</small>
                                        </td>
                                        <td>
                                            <input type="hidden" name="penilaian[{{ $student->id }}][student_id]"
                                                value="{{ $student->id }}">
                                            <input type="number" name="penilaian[{{ $student->id }}][nilai]"
                                                class="form-control text-center"
                                                value="{{ old('penilaian.' . $student->id . '.nilai', $student->nilai_saat_ini) }}"
                                                min="0" max="100" step="1" placeholder="Nilai"
                                                style="width: 100px; margin: 0 auto;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('guru.evaluations.show', $evaluation->id) }}"
                            class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
