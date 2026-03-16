@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Edit Penilaian: {{ $evaluation->nama_penilaian }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluation.update', $evaluation->id) }}" method="POST">
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

                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Siswa</th>
                                <th width="150">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evaluation->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->student->nama }}</td>
                                    <td>
                                        {{-- Gunakan ID detail sebagai key agar update lebih presisi --}}
                                        <input type="number" name="penilaian[{{ $detail->id }}][nilai]"
                                            class="form-control" value="{{ $detail->nilai }}" min="0" max="100"
                                            required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('evaluation.show', $evaluation->id) }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
