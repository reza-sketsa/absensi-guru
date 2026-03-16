<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.guru.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nip' => 'required|unique:teachers,nip',
            'username' => 'required|unique:users,username',
            'nama_guru' => 'required',
            'password' => 'required|min:6',
            'jk' => 'required|in:L,P',
            'school_id' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Buat Akun User
                $user = User::create([
                    'name' => $request->nama_guru,
                    'username' => $request->username,
                    'password' => Hash::make($request->password), // Lebih aman pake Hash::make
                    'role' => 'Guru',
                ]);

                // 2. Buat Profil Teacher
                Teacher::create([
                    'user_id' => $user->id,
                    'nama_guru' => $request->nama_guru,
                    'nip' => $request->nip,
                    'jk' => $request->jk,
                    'agama' => $request->agama,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat' => $request->alamat,
                    'no_telp' => $request->no_telp,
                    'school_id' => $request->school_id ?? 1,
                ]);
            });

            return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil didaftarkan.');
        } catch (\Exception $e) {
            // Jika error, kembali ke form dengan pesan error
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('admin.guru.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user_id);

        $request->validate([
            'nip' => 'required|unique:teachers,nip,' . $id,
            'username' => 'required|unique:users,username,' . $user->id,
            'nama_guru' => 'required',
            'jk' => 'required|in:L,P',
            'password' => 'nullable|min:6', // Password boleh kosong kalau gak mau diubah
        ]);

        try {
            DB::transaction(function () use ($request, $teacher, $user) {
                // 1. Update Tabel Users
                $userData = [
                    'username' => $request->username,
                ];
                // Update password cuma kalau diisi
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);

                // 2. Update Tabel Teachers
                $teacher->update([
                    'nama_guru' => $request->nama_guru,
                    'nip'       => $request->nip,
                    'jk'        => $request->jk,
                    'agama'     => $request->agama,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat'    => $request->alamat,
                    'no_telp'   => $request->no_telp,
                ]);
            });

            return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);

            DB::transaction(function () use ($teacher, $user) {
                // Hapus data guru dulu
                $teacher->delete();
                // Baru hapus data user (akun login)
                $user->delete();
            });

            return redirect()->route('admin.guru.index')->with('success', 'Data Guru dan Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
