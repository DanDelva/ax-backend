<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Resources\GradeResource;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Grade::with(['user', 'subject']);

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isDocente()) {
            $query->whereHas('subject', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        return GradeResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden subir calificaciones');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:10',
            'type' => 'required|in:teorica,practica'
        ]);

        if ($user->isDocente()) {
            // Check if teacher owns the subject
            $subject = \App\Models\Subject::findOrFail($validated['subject_id']);
            if ($subject->teacher_id !== $user->id) {
                abort(403, 'No tienes asignada esta materia');
            }
        }

        $grade = Grade::create($validated);
        return new GradeResource($grade->load(['user', 'subject']));
    }

    public function show(Request $request, Grade $grade)
    {
        $user = $request->user();

        if ($user->isAlumno() && $grade->user_id !== $user->id) {
            abort(403, 'No autorizado para ver esta calificación');
        }

        if ($user->isDocente() && $grade->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia');
        }

        return new GradeResource($grade->load(['user', 'subject']));
    }

    public function update(Request $request, Grade $grade)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden modificar calificaciones');
        }

        if ($user->isDocente() && $grade->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia');
        }

        $validated = $request->validate([
            'score' => 'sometimes|required|numeric|min:0|max:10',
            'type' => 'sometimes|required|in:teorica,practica'
        ]);

        $grade->update($validated);
        return new GradeResource($grade->load(['user', 'subject']));
    }

    public function destroy(Request $request, Grade $grade)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden eliminar calificaciones');
        }

        if ($user->isDocente() && $grade->subject->teacher_id !== $user->id) {
            abort(403, 'No tienes asignada esta materia');
        }

        $grade->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
