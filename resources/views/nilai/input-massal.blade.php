@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends('welcome')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Input Nilai Massal - Kelas {{ $classroom->nama_kelas }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluation.store') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Mata Pelajaran</label>
                            <select name="subject_id" class="form-select" required>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Jenis Penilaian</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Tugas">Tugas</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label>Nama Penilaian</label>
                            <input type="text" name="nama_penilaian" class="form-control" placeholder="Contoh: UH 1"
                                required>
                        </div>
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Siswa</th>
                                <th width="200">Nilai (0-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $student->nama }}
                                        <input type="hidden" name="penilaian[{{ $index }}][student_id]"
                                            value="{{ $student->id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="penilaian[{{ $index }}][nilai]"
                                            class="form-control @error("penilaian.$index.nilai") is-invalid @enderror"
                                            min="0" max="100" step="0.01">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-5">Simpan Semua Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
