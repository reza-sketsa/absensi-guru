<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    public function index()
    {
        $classes = DB::table('classrooms')
            ->leftJoin('teachers', 'classrooms.walas_id', '=', 'teachers.id')
            ->select('classrooms.*', 'teachers.nama_guru')
            ->whereNull('classrooms.deleted_at')
            ->orderBy('tingkat', 'asc')
            ->orderBy('paralel', 'asc')
            ->get();

        $teachers = DB::table('teachers')->whereNull('deleted_at')->get();

        return view('admin.kelas.index', compact('classes', 'teachers'));
    }

    public function store(ClassroomRequest $request)
    {
        $data = $request->validated();

        DB::table('classrooms')->insert([
            'tingkat'    => $data['tingkat'],
            'paralel'    => $data['paralel'],
            'walas_id'   => $data['walas_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kelas ' . $data['tingkat'] . '-' . $data['paralel'] . ' berhasil dibuat.');
    }

    public function import(Request $request, $kelas_id)
    {
        $request->validate([
            'file_siswa' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file_siswa');
        $handle = fopen($file->getRealPath(), "r");
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) < 7) continue;

                Student::create([
                    'nama'          => $data[0],
                    'nis'           => $data[1],
                    'jk'            => $data[2],
                    'agama'         => $data[3],
                    'tgl_lahir'     => $data[4],
                    'alamat'        => $data[5],
                    'no_telp'       => $data[6] ?? null,
                    'no_telp_ortu'  => $data[7] ?? null,
                    'classroom_id'  => $kelas_id,
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: Pastikan NIS unik dan format benar.');
        } finally {
            fclose($handle);
        }
    }

    public function update(ClassroomRequest $request, $id)
    {
        $data = $request->validated();

        DB::table('classrooms')
            ->where('id', $id)
            ->update([
                'tingkat' => $data['tingkat'],
                'paralel' => $data['paralel'],
                'walas_id' => $data['walas_id'],
                'updated_at' => now(),
            ]);
        return redirect()->back()->with('succes', 'Kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $hasStudents = DB::table('students')
            ->where('classroom_id', $id)
            ->exists();

        $hasSchedules = DB::table('schedule')
            ->where('classroom_id', $id)
            ->exists();

        if ($hasStudents || $hasSchedules) {
            return redirect()->back()->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa atau jadwal aktif');
        }

        DB::table('classrooms')
            ->where('id', $id)
            ->delete();
        return redirect()->back()->with('succes', 'Kelas berhasil dihapus');
    }
}
