<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::withCount('subjects')->get();
        return view('specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('specialties.form', ['specialty' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Specialty::create($validated);
        return redirect()->route('panel.specialties.index')->with('success', 'Especialidad creada.');
    }

    public function edit(Specialty $specialty)
    {
        return view('specialties.form', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $specialty->update($validated);
        return redirect()->route('panel.specialties.index')->with('success', 'Especialidad actualizada.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('panel.specialties.index')->with('success', 'Especialidad eliminada.');
    }
}
