<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string',
            'nis'       => 'required|max:10|unique:students,nis',
            'jk'        => 'required|in:L,P',
            'agama'     => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'tgl_lahir' => 'required|date',
            'alamat'    => 'required|string',
            'no_telp'      => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        Student::create(array_merge($validator->validated(), ['classroom_id' => $kelas_id]));

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $kelas_id, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'         => 'required|string',
            'nis'          => 'required|max:10|unique:students,nis,' . $id,
            'jk'           => 'required|in:L,P',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'tgl_lahir'    => 'required|date',
            'alamat'       => 'required|string',
            'no_telp'      => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        Student::findOrFail($id)->update($validator->validated());

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($kelas_id, $id)
    {
        $student = Student::findOrFail($id);

        if ($student->attendances()->exists() || $student->evaluations()->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena masih memiliki data absensi atau nilai.');
        }

        $student->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request, $kelas_id)
    {
        $request->validate([
            'file_siswa' => 'required|file|mimes:csv,txt'
        ]);
        $file = $request->file('file_siswa');

        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($rows); // baris pertama = header

        $berhasil = 0;
        $gagal    = 0;

        foreach ($rows as $row) {
            if (count($row) < count($header)) continue;

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'nama'      => 'required|string',
                'nis'       => 'required|max:10|unique:students,nis',
                'jk'        => 'required|in:L,P',
                'agama'     => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
                'tgl_lahir' => 'required|date',
                'alamat'    => 'required|string',
                'no_telp'      => 'nullable|string',
                'no_telp_ortu' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $gagal++;
                continue;
            }

            Student::create(array_merge($validator->validated(), ['classroom_id' => $kelas_id]));
            $berhasil++;
        }

        return redirect()->back()->with('success', "Import selesai: {$berhasil} berhasil, {$gagal} dilewati.");
    }
}
