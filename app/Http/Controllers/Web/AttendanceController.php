<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Attendance::with(['user', 'subject']);

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isDocente()) {
            $mySubjectIds = Subject::where('teacher_id', $user->id)->pluck('id');
            $query->whereIn('subject_id', $mySubjectIds);
        }

        if (request()->filled('date')) {
            $query->whereDate('date', request('date'));
        }
        if (request()->filled('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }
        if (request()->filled('student_id') && !$user->isAlumno()) {
            $query->where('user_id', request('student_id'));
        }
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('matricula', 'like', "%{$search}%");
                })->orWhereHas('subject', function($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
            
            if (request()->filled('subject_id')) {
                $subject = Subject::find(request('subject_id'));
                $studentIds = Enrollment::where('specialty_id', $subject->specialty_id)->pluck('user_id')->unique();
            } else {
                $specialtyIds = $subjects->pluck('specialty_id')->unique();
                $studentIds = Enrollment::whereIn('specialty_id', $specialtyIds)->pluck('user_id')->unique();
            }
            
            $students = User::whereIn('id', $studentIds)->where('role', 'estudiante')->orderBy('name')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
            
            if (request()->filled('subject_id')) {
                $subject = Subject::find(request('subject_id'));
                $studentIds = Enrollment::where('specialty_id', $subject->specialty_id)->pluck('user_id')->unique();
                $students = User::whereIn('id', $studentIds)->where('role', 'estudiante')->orderBy('name')->get();
            } else {
                $students = User::where('role', 'estudiante')->orderBy('name')->get();
            }
        }

        return view('attendances.index', compact('attendances', 'students', 'subjects'));
    }

    public function create()
    {
        if (Auth::user()->isAlumno()) abort(403);

        $user = Auth::user();
        $students = User::where('role', 'estudiante')->orderBy('name')->get();

        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
        }

        return view('attendances.form', ['attendance' => null, 'students' => $students, 'subjects' => $subjects]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:presente,falta,retardo,justificado',
        ]);
        Attendance::create($validated);
        return redirect()->route('panel.attendances.index', [
            'subject_id' => $validated['subject_id'],
            'date' => $validated['date']
        ])->with('success', 'Asistencia registrada.');
    }

    public function edit(Attendance $attendance)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $user = Auth::user();
        $students = User::where('role', 'estudiante')->orderBy('name')->get();

        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
        }

        return view('attendances.form', compact('attendance', 'students', 'subjects'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:presente,falta,retardo,justificado',
        ]);
        $attendance->update($validated);
        return redirect()->route('panel.attendances.index', [
            'subject_id' => $validated['subject_id'],
            'date' => $validated['date']
        ])->with('success', 'Asistencia actualizada.');
    }

    public function destroy(Attendance $attendance)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $attendance->delete();
        return redirect()->route('panel.attendances.index')->with('success', 'Asistencia eliminada.');
    }

    public function quick(Request $request)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $user = Auth::user();
        
        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
        }

        $students = [];
        if ($request->filled('subject_id')) {
            $subject = Subject::findOrFail($request->subject_id);
            // Get all students enrolled in this subject's specialty
            // Depending on how groups work, we assume the specialty is what binds them.
            // If group_name is needed, we would add another filter.
            $enrollments = Enrollment::where('specialty_id', $subject->specialty_id)
                ->with('user')
                ->get();
            
            $students = $enrollments->pluck('user')->unique('id')->sortBy('name');
        }

        return view('attendances.quick', compact('subjects', 'students'));
    }

    public function quickStore(Request $request)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*' => 'in:presente,falta,retardo,justificado',
        ]);

        $subjectId = $request->subject_id;
        $date = $request->date;

        foreach ($request->attendances as $userId => $status) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $userId,
                    'subject_id' => $subjectId,
                    'date' => $date,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('panel.attendances.index', [
            'subject_id' => $subjectId,
            'date' => $date
        ])->with('success', 'Pase de lista rápido guardado correctamente.');
    }
}
