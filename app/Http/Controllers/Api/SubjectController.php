<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    public function index()
    {
        return SubjectResource::collection(Subject::with(['specialty', 'teacher', 'schedules'])->get());
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'specialty_id' => 'required|exists:specialties,id',
            'title' => 'required|string|max:255',
            'content_manual' => 'required|string',
            'material_list' => 'required|string',
            'teacher_id' => 'nullable|exists:users,id'
        ]);

        $subject = Subject::create($validated);
        return new SubjectResource($subject->load(['specialty', 'teacher', 'schedules']));
    }

    public function show(Subject $subject)
    {
        return new SubjectResource($subject->load(['specialty', 'teacher', 'schedules']));
    }

    public function update(Request $request, Subject $subject)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'specialty_id' => 'sometimes|required|exists:specialties,id',
            'title' => 'sometimes|required|string|max:255',
            'content_manual' => 'sometimes|required|string',
            'material_list' => 'sometimes|required|string',
            'teacher_id' => 'nullable|exists:users,id'
        ]);

        $subject->update($validated);
        return new SubjectResource($subject->load(['specialty', 'teacher', 'schedules']));
    }

    public function destroy(Request $request, Subject $subject)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $subject->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
