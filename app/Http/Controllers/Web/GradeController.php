<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Grade::with(['user', 'subject']);

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isDocente()) {
            $mySubjectIds = Subject::where('teacher_id', $user->id)->pluck('id');
            $query->whereIn('subject_id', $mySubjectIds);
        }

        if (request()->filled('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }
        if (request()->filled('student_id') && !$user->isAlumno()) {
            $query->where('user_id', request('student_id'));
        }
        if (request()->filled('type')) {
            $query->where('type', request('type'));
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

        $grades = $query->get();

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

        return view('grades.index', compact('grades', 'students', 'subjects'));
    }

    public function create()
    {
        if (Auth::user()->isAlumno()) abort(403);

        $user = Auth::user();

        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
            $specialtyIds = $subjects->pluck('specialty_id')->unique();
            $studentIds = Enrollment::whereIn('specialty_id', $specialtyIds)->pluck('user_id')->unique();
            $students = User::whereIn('id', $studentIds)->where('role', 'estudiante')->orderBy('name')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
            $students = User::where('role', 'estudiante')->orderBy('name')->get();
        }

        return view('grades.form', ['grade' => null, 'students' => $students, 'subjects' => $subjects]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:10',
            'type' => 'required|in:teorica,practica',
        ]);
        Grade::create($validated);
        return redirect()->route('panel.grades.index')->with('success', 'Calificación registrada.');
    }

    public function edit(Grade $grade)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $user = Auth::user();

        if ($user->isDocente()) {
            $subjects = Subject::where('teacher_id', $user->id)->orderBy('title')->get();
            $specialtyIds = $subjects->pluck('specialty_id')->unique();
            $studentIds = Enrollment::whereIn('specialty_id', $specialtyIds)->pluck('user_id')->unique();
            $students = User::whereIn('id', $studentIds)->where('role', 'estudiante')->orderBy('name')->get();
        } else {
            $subjects = Subject::orderBy('title')->get();
            $students = User::where('role', 'estudiante')->orderBy('name')->get();
        }

        return view('grades.form', compact('grade', 'students', 'subjects'));
    }

    public function update(Request $request, Grade $grade)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:10',
            'type' => 'required|in:teorica,practica',
        ]);
        $grade->update($validated);
        return redirect()->route('panel.grades.index')->with('success', 'Calificación actualizada.');
    }

    public function destroy(Grade $grade)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $grade->delete();
        return redirect()->route('panel.grades.index')->with('success', 'Calificación eliminada.');
    }
}
