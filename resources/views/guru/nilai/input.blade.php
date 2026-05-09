@extends('layouts.app')

@section('content')
    <div class="container py-3 mb-5">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru.penilaian.index') }}" class="btn btn-outline-light border-0 btn-sm">
                        <i class="bi bi-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $schedule->subject->nama_mapel }}</h5>
                        <p class="mb-0 opacity-75 small">
                            <i class="bi bi-door-open me-1"></i>
                            Kelas: {{ $schedule->classroom->tingkat }}-{{ $schedule->classroom->paralel }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('guru.evaluations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    <input type="hidden" name="subject_id" value="{{ $schedule->subject_id }}">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="small fw-bold">Jenis Penilaian</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Tugas">Tugas</option>
                                <option value="UH">UH</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Nama Penilaian (Contoh: UH 1)</label>
                            <input type="text" name="nama_penilaian" class="form-control" required
                                placeholder="Misal: Tugas Aljabar">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Siswa</th>
                                    <th width="150">Nilai (0-100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold small">{{ $student->nama }}</td>
                                        <td>
                                            <input type="hidden" name="penilaian[{{ $student->id }}][student_id]"
                                                value="{{ $student->id }}">
                                            <input type="number" name="penilaian[{{ $student->id }}][nilai]"
                                                class="form-control form-control-sm text-center" min="0"
                                                max="100" step="0.01" placeholder="-">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Semua Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
