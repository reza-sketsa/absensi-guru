@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">Input Nilai: {{ $schedule->subject->nama }}</h6>
                <p class="text-muted small">Kelas {{ $schedule->classroom->tingkat }} {{ $schedule->classroom->paralel }}</p>

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
                                            <input type="hidden" name="penilaian[{{ $index }}][student_id]"
                                                value="{{ $student->id }}">
                                            <input type="number" name="penilaian[{{ $index }}][nilai]"
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
