<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Excuse;
use Illuminate\Http\Request;
use App\Http\Resources\ExcuseResource;

class ExcuseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Excuse::with(['user']);

        // Si es estudiante, solo ve sus propios justificantes
        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        }
        // Admin y docentes ven todos los justificantes para aprobarlos

        return ExcuseResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
            'file_path' => 'nullable|string'
        ]);

        if ($user->isAlumno()) {
            $validated['user_id'] = $user->id;
            $validated['status'] = 'pendiente';
        } else {
            $request->validate(['user_id' => 'required|exists:users,id']);
        }

        $excuse = Excuse::create($validated);
        return new ExcuseResource($excuse->load(['user']));
    }

    public function show(Request $request, Excuse $excuse)
    {
        $user = $request->user();

        if ($user->isAlumno() && $excuse->user_id !== $user->id) {
            abort(403, 'No autorizado para ver este justificante');
        }

        return new ExcuseResource($excuse->load(['user']));
    }

    public function update(Request $request, Excuse $excuse)
    {
        $user = $request->user();

        if ($user->isAlumno()) {
            abort(403, 'Los estudiantes no pueden modificar justificantes');
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|in:pendiente,aprobado,rechazado',
            'reason' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'file_path' => 'nullable|string'
        ]);

        $excuse->update($validated);
        return new ExcuseResource($excuse->load(['user']));
    }

    public function destroy(Request $request, Excuse $excuse)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            abort(403, 'Solo el administrador puede eliminar justificantes');
        }

        $excuse->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
