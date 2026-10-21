<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Resources\SpecialtyResource;

class SpecialtyController extends Controller
{
    public function index()
    {
        return SpecialtyResource::collection(Specialty::all());
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $specialty = Specialty::create($validated);
        return new SpecialtyResource($specialty);
    }

    public function show(Specialty $specialty)
    {
        return new SpecialtyResource($specialty);
    }

    public function update(Request $request, Specialty $specialty)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $specialty->update($validated);
        return new SpecialtyResource($specialty);
    }

    public function destroy(Request $request, Specialty $specialty)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $specialty->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
