<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;


class StudentController extends Controller
{
    public function index($kelas_id)
    {
        $kelas    = Classroom::findOrFail($kelas_id);
        $students = Student::where('classroom_id', $kelas_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.kelas.students', compact('kelas', 'students'));
    }

    public function store(Request $request, $kelas_id)
    {
        $request->validate([
            'nama'     => 'required|string',
            'nis'      => 'required|unique:students,nis',
            'jk'       => 'required|in:L,P',
            'agama'    => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'tgl_lahir' => 'required|date',
            'alamat'   => 'required|string',
            'no_telp'  => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
        ]);

        Student::create([
            'nama'         => $request->nama,
            'nis'          => $request->nis,
            'jk'           => $request->jk,
            'agama'        => $request->agama,
            'tgl_lahir'    => $request->tgl_lahir,
            'alamat'       => $request->alamat,
            'no_telp'      => $request->no_telp,
            'no_telp_ortu' => $request->no_telp_ortu,
            'classroom_id' => $kelas_id,
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'         => 'required|string',
            'nis'          => 'required|unique:students,nis,' . $id,
            'jk'           => 'required|in:L,P',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'tgl_lahir'    => 'required|date',
            'alamat'       => 'required|string',
            'no_telp'      => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
        ]);

        Student::findOrFail($id)->update([
            'nama'         => $request->nama,
            'nis'          => $request->nis,
            'jk'           => $request->jk,
            'agama'        => $request->agama,
            'tgl_lahir'    => $request->tgl_lahir,
            'alamat'       => $request->alamat,
            'no_telp'      => $request->no_telp,
            'no_telp_ortu' => $request->no_telp_ortu,
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        if ($student->attendances()->exists() || $student->evaluations()->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena masih memiliki data absensi atau nilai.');
        }

        $student->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }
}
