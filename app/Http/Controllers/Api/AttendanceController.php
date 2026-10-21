<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Attendance::with(['user', 'subject']);

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isDocente()) {
            $query->whereHas('subject', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        return AttendanceResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden pasar lista');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:presente,falta,retardo,justificado'
        ]);

        if ($user->isDocente()) {
            $subject = Subject::findOrFail($validated['subject_id']);
            if ($subject->teacher_id !== $user->id) {
                abort(403, 'No tienes asignada esta materia para pasar lista');
            }
        }

        $attendance = Attendance::create($validated);
        return new AttendanceResource($attendance->load(['user', 'subject']));
    }

    public function show(Request $request, Attendance $attendance)
    {
        $user = $request->user();

        if ($user->isAlumno() && $attendance->user_id !== $user->id) {
            abort(403, 'No autorizado para ver esta asistencia');
        }

        if ($user->isDocente() && $attendance->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia');
        }

        return new AttendanceResource($attendance->load(['user', 'subject']));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden modificar asistencias');
        }

        if ($user->isDocente() && $attendance->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia para modificar lista');
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|in:presente,falta,retardo,justificado',
            'date' => 'sometimes|required|date'
        ]);

        $attendance->update($validated);
        return new AttendanceResource($attendance->load(['user', 'subject']));
    }

    public function destroy(Request $request, Attendance $attendance)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden eliminar registros de lista');
        }

        if ($user->isDocente() && $attendance->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia');
        }

        $attendance->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
