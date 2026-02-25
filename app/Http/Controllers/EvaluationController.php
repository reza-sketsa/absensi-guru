<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function create(Student $student)
    {
        $student->load('classroom');

        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('nilai.input', compact(['student', 'subjects', 'teachers']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'subject_id' => 'required|exists:subjects,id',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'nama_penilaian' => 'required|string|max:30',
            'tanggal' => 'required|date',
            'penilaian' => 'required|array',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai' => 'required|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada tahun ajaran aktif!'
            ], 400);
        }

        return DB::transaction(function () use ($request, $activeYear) {

            $evaluation = Evaluation::where('subject_id', $request->subject_id)
                ->where('teacher_id', Auth::id())
                ->where('jenis', $request->jenis)
                ->where('nama_penilaian', $request->nama_penilaian)
                ->where('academic_year_id', $activeYear->id)
                ->first();

            if (!$evaluation) {
                $evaluation = Evaluation::create([
                    'subject_id'     => $request->subject_id,
                    'teacher_id'     => Auth::id(),
                    'jenis'          => $request->jenis,
                    'nama_penilaian' => $request->nama_penilaian,
                    'academic_year_id' => $activeYear->id,
                    'schedule_id'    => $request->schedule_id,
                    'tanggal'        => $request->tanggal,
                ]);
            }

            foreach ($request->penilaian as $item) {
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

            return redirect()->route('students.data')
                ->with('success', 'Nilai berhasil diproses!');
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
    public function update(Request $request, Evaluation $evaluation)
    {
        $validator = Validator::make($request->all(), [
            'nama_penilaian' => 'required|string|max:30',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'tanggal' => 'required|date',
            'penilaian' => 'required|array',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

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

        return view('nilai.trash', compact('trashedScores'));
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
