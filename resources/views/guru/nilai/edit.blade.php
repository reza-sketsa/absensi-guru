@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.evaluations.show', $evaluation->id) }}"
                        class="btn btn-outline-light border-0 btn-sm">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">Edit: {{ $evaluation->nama_penilaian }}</h5>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas: {{ $evaluation->classroom->tingkat }}-{{ $evaluation->classroom->paralel }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('guru.evaluations.update', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nama Penilaian</label>
                            <input type="text" name="nama_penilaian" class="form-control"
                                value="{{ $evaluation->nama_penilaian }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jenis</label>
                            <select name="jenis" class="form-select">
                                <option value="UH" {{ $evaluation->jenis == 'UH' ? 'selected' : '' }}>Ulangan Harian
                                </option>
                                <option value="UTS" {{ $evaluation->jenis == 'UTS' ? 'selected' : '' }}>UTS</option>
                                <option value="UAS" {{ $evaluation->jenis == 'UAS' ? 'selected' : '' }}>UAS</option>
                                <option value="Tugas" {{ $evaluation->jenis == 'Tugas' ? 'selected' : '' }}>Tugas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ $evaluation->tanggal->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Siswa</th>
                                    <th style="text-align: center;" width="150">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->nama }}</td>
                                        <td>
                                            {{-- ID Siswa sebagai key agar kita tahu nilai ini milik siapa --}}
                                            <input type="hidden" name="penilaian[{{ $student->id }}][student_id]"
                                                value="{{ $student->id }}">

                                            <input style="text-align: center;" type="number"
                                                name="penilaian[{{ $student->id }}][nilai]" class="form-control"
                                                value="{{ old('penilaian.' . $student->id . '.nilai', $student->nilai_saat_ini) }}"
                                                min="0" max="100" placeholder="-">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
