<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
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
        $teacherId = $this->getTeacherId();

        $schedules = \App\Models\Schedule::with(['subject', 'classroom', 'evaluations' => function ($q) {
            $q->withTrashed()->orderBy('tanggal', 'desc')->take(10);
        }])
            ->where('teacher_id', $teacherId)
            ->get();

        return view('guru.nilai.nilai', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($schedule_id)
    {
        if (!$schedule_id) {
            return redirect()->route('guru.evaluations.index')->with('error', 'Silahkan pilih kelas terlebih dahulu.');
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
            return back()->with('error', 'Tahun ajaran aktif belum diatur oleh admin.');
        }


        $schedule = \App\Models\Schedule::findOrFail($request->schedule_id);

        return DB::transaction(function () use ($request, $activeYear, $teacherId, $schedule) {
            $evaluation = Evaluation::updateOrCreate(
                [
                    'schedule_id'      => $request->schedule_id,
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
            return redirect()->route('guru.evaluations.index')
                ->with('success', 'Nilai siswa berhasil di input.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluation = Evaluation::withTrashed()
            ->with(['details.student', 'subject', 'classroom'])
            ->findOrFail($id);

        return view('guru.nilai.show', compact('evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluation = Evaluation::with(['details.student', 'subject', 'classroom'])->findOrFail($id);

        return view('guru.nilai.edit', compact('evaluation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penilaian' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penilaian.*.nilai' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $id) {
            $evaluation = Evaluation::findOrFail($id);

            $evaluation->update([
                'nama_penilaian' => $request->nama_penilaian,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
            ]);

            foreach ($request->penilaian as $detailId => $data) {
                EvaluationDetail::where('id', $detailId)
                    ->where('evaluation_id', $evaluation->id)
                    ->update(['nilai' => $data['nilai']]);
            }
        });

        return redirect()->route('guru.evaluations.show', $id)
            ->with('success', 'Data penilaian berhasil diperbarui!');
    }

    public function destroyDetailNilai($id)
    {
        try {
            $detail = EvaluationDetail::findOrFail($id);
            $evaluationId = $detail->evaluation_id;

            $detail->delete();

            return redirect()->route('evaluation.show', $evaluationId)
                ->with('success', 'Nilai siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }

        return redirect()->route('guru.evaluations.show', $evaluationId) // Tambahkan 'guru.'
            ->with('success', 'Nilai siswa berhasil dihapus.');
    }

    public function destroy($id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);
            $evaluation->details()->delete();
            $evaluation->delete();

            return redirect()->route('guru.penilaian.index')
                ->with('success', 'Data penilaian berhasil dipindahkan ke tempat sampah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return redirect()->route('guru.penilaian.index') // Sesuaikan dengan name di web.php
            ->with('success', 'Data penilaian berhasil dipindahkan...');
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
        try {
            $evaluation = Evaluation::withTrashed()->findOrFail($id);

            $evaluation->restore();

            $evaluation->details()->restore();

            return back()->with('success', 'Data penilaian berhasil dikembalikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan data: ' . $e->getMessage());
        }
    }

    public function restoreDetailNilai($id)
    {
        $detail = EvaluationDetail::withTrashed()->findOrFail($id);
        $detail->restore();

        return back()->with('success', 'Nilai siswa berhasil dikembalikan!');
    }
}
