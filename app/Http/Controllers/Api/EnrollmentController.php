<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Resources\EnrollmentResource;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'specialty']);

        // Si es estudiante, solo ve sus inscripciones
        if ($request->user()->isAlumno()) {
            $query->where('user_id', $request->user()->id);
        }

        return EnrollmentResource::collection($query->get());
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'group_name' => 'required|string|max:255'
        ]);

        $enrollment = Enrollment::create($validated);
        return new EnrollmentResource($enrollment->load(['user', 'specialty']));
    }

    public function show(Request $request, Enrollment $enrollment)
    {
        if ($request->user()->isAlumno() && $enrollment->user_id !== $request->user()->id) {
            abort(403, 'No autorizado para ver esta inscripción');
        }

        return new EnrollmentResource($enrollment->load(['user', 'specialty']));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'specialty_id' => 'sometimes|required|exists:specialties,id',
            'group_name' => 'sometimes|required|string|max:255'
        ]);

        $enrollment->update($validated);
        return new EnrollmentResource($enrollment->load(['user', 'specialty']));
    }

    public function destroy(Request $request, Enrollment $enrollment)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $enrollment->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
