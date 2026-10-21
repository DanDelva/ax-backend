<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function timetable()
    {
        $user = Auth::user();
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $hours = range(7, 20);

        $query = Schedule::with('subject.teacher');

        if ($user->isDocente()) {
            // Docente: solo horarios de las materias que imparte
            $subjectIds = Subject::where('teacher_id', $user->id)->pluck('id');
            $query->whereIn('subject_id', $subjectIds);
        } elseif ($user->isAlumno()) {
            // Estudiante: solo horarios de materias de sus especialidades inscritas
            $specialtyIds = $user->enrollments()->pluck('specialty_id');
            $subjectIds = Subject::whereIn('specialty_id', $specialtyIds)->pluck('id');
            $query->whereIn('subject_id', $subjectIds);
        }

        $schedules = $query->get();

        return view('schedules.timetable', compact('schedules', 'days', 'hours'));
    }

    public function index()
    {
        $query = Schedule::with('subject.teacher');

        // Filtros (Ver horario como si fuera X usuario, o por materia)
        if (request()->filled('role_filter')) {
            $userId = request('user_id');
            if (request('role_filter') === 'docente' && $userId) {
                $subjectIds = Subject::where('teacher_id', $userId)->pluck('id');
                $query->whereIn('subject_id', $subjectIds);
            } elseif (request('role_filter') === 'estudiante' && $userId) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $specialtyIds = $user->enrollments()->pluck('specialty_id');
                    $subjectIds = Subject::whereIn('specialty_id', $specialtyIds)->pluck('id');
                    $query->whereIn('subject_id', $subjectIds);
                }
            }
        }
        
        if (request()->filled('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $subjects = Subject::with('teacher')->orderBy('title')->get();
        $docentes = \App\Models\User::where('role', 'docente')->get();
        $estudiantes = \App\Models\User::where('role', 'estudiante')->get();

        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $hours = range(7, 20);
        $viewType = request('view', 'grid'); // default to grid

        return view('schedules.index', compact('schedules', 'subjects', 'docentes', 'estudiantes', 'days', 'hours', 'viewType'));
    }

    public function create()
    {
        $subjects = Subject::with('teacher')->orderBy('title')->get();
        return view('schedules.form', ['schedule' => null, 'subjects' => $subjects]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'classroom' => 'required|string|max:50',
        ]);
        Schedule::create($validated);
        return redirect()->route('panel.schedules.index')->with('success', 'Horario creado.');
    }

    public function edit(Schedule $schedule)
    {
        $subjects = Subject::with('teacher')->orderBy('title')->get();
        return view('schedules.form', compact('schedule', 'subjects'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        // Soporte para actualización rápida por AJAX (Drag & Drop)
        if ($request->wantsJson() || $request->ajax()) {
            $schedule->update($request->only(['day_of_week', 'start_time', 'end_time']));
            return response()->json(['success' => true, 'message' => 'Horario actualizado.']);
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'classroom' => 'required|string|max:50',
        ]);
        $schedule->update($validated);
        return redirect()->route('panel.schedules.index')->with('success', 'Horario actualizado.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('panel.schedules.index')->with('success', 'Horario eliminado.');
    }
}
