<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{

    private function getTeacherId()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silahkan login kembali.');
        }

        $teacher = $user->teacher ?: Teacher::where('user_id', $user->id)->first();

        return $teacher ? $teacher->id : abort(403, 'User tidak terhubung ke data Guru.');
    }

    public function index(Request $request)
    {
        $query = Evaluation::with(['details.student', 'subject']);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return response()->json([
            'status' => true,
            'data' => $query->latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($schedule_id = null)
    {
        if (!$schedule_id) {
            return redirect()->route('guru.penilaian.index')->with('error', 'Silahkan pilih kelas terlebih dahulu.');
        }

        $schedule = \App\Models\Schedule::with(['subject', 'classroom'])->findOrFail($schedule_id);

        $students = Student::where('classroom_id', $schedule->classroom_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('guru.nilai.input', compact('schedule', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluationRequest $request)
    {

        $teacherId = $this->getTeacherId();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif!');
        }

        return DB::transaction(function () use ($request, $activeYear, $teacherId) {
            $evaluation = Evaluation::updateOrCreate(
                [
                    'subject_id'       => $request->subject_id,
                    'teacher_id'       => $teacherId,
                    'jenis'            => $request->jenis,
                    'nama_penilaian'   => $request->nama_penilaian,
                    'academic_year_id' => $activeYear->id,
                ],
                [
                    'schedule_id'      => $request->schedule_id,
                    'tanggal'          => $request->tanggal,
                ]
            );

            foreach ($request->penilaian as $item) {
                if (isset($item['nilai']) && $item['nilai'] !== '') {
                    \App\Models\EvaluationDetail::updateOrCreate(
                        [
                            'evaluation_id' => $evaluation->id,
                            'student_id'    => $item['student_id'],
                        ],
                        [
                            'nilai'         => $item['nilai'],
                        ]
                    );
                }
            }
            return back()->with('success', 'Nilai berhasil disimpan!');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluationRequest $request, Evaluation $evaluation)
    {
        return DB::transaction(function () use ($request, $evaluation) {
            $evaluation->update($request->only(['nama_penilaian', 'jenis', 'tanggal']));

            // OPTIMASI: Update nilai lebih aman dengan delete & insert atau upsert
            $evaluation->details()->delete();

            $details = collect($request->penilaian)->map(function ($item) use ($evaluation) {
                return [
                    'evaluation_id' => $evaluation->id,
                    'student_id' => $item['student_id'],
                    'nilai' => $item['nilai'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $evaluation->details()->insert($details);

            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil diupdate',
                'data' => $evaluation->load('details.student')
            ], 200);
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        return DB::transaction(function () use ($evaluation) {

            $evaluation->details()->delete();
            $evaluation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil dihapus',
                'data' => $evaluation->load('details.student'),
            ]);
        });
    }

    public function destroyDetailNilai($id)
    {
        try {
            $detail = \App\Models\EvaluationDetail::find($id);

            if (!$detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Detail nilai tidak ditemukan'
                ], 404);
            }

            $detail->delete();

            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil dihapus',
                'Data' => [
                    'id' => $id,
                    'student_id' => $detail->student_id,
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server saat menghapus data. '
            ], 500);
        }
    }

    public function purgeOldScores()
    {

        $oldData = \App\Models\EvaluationDetail::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30));

        $count = $oldData->count();
        $oldData->forceDelete();

        return response()->json([
            'message' => "Berhasil membersihkan $count data sampah lama."
        ]);
    }

    public function trash()
    {
        $trashedScores = \App\Models\EvaluationDetail::onlyTrashed()
            ->with(['student', 'evaluation'])
            ->latest('deleted_at')
            ->get();

        $trashCount = \App\Models\EvaluationDetail::onlyTrashed()->count();

        return view('guru.nilai.trash', compact('trashedScores'));
    }

    public function restore($id)
    {
        $detail = \App\Models\EvaluationDetail::onlyTrashed()->findOrFail($id);
        $detail->restore();

        return back()->with('Success', 'Nilai berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $detail = \App\Models\EvaluationDetail::onlyTrashed()->findOrFail($id);
        $detail->forceDelete();

        return back()->with('Success', 'Nilai berhasil dihapus permanen!');
    }
}
