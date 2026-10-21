<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isDocente()) {
            $subjects = Subject::with(['specialty', 'teacher'])->where('teacher_id', $user->id)->get();
        } else {
            $subjects = Subject::with(['specialty', 'teacher'])->get();
        }

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        $teachers = User::where('role', 'docente')->orderBy('name')->get();
        return view('subjects.form', ['subject' => null, 'specialties' => $specialties, 'teachers' => $teachers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'specialty_id' => 'required|exists:specialties,id',
            'teacher_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'content_manual' => 'required|string',
            'material_list' => 'required|string',
        ]);
        Subject::create($validated);
        return redirect()->route('panel.subjects.index')->with('success', 'Materia creada.');
    }

    public function edit(Subject $subject)
    {
        $specialties = Specialty::all();
        $teachers = User::where('role', 'docente')->orderBy('name')->get();
        return view('subjects.form', compact('subject', 'specialties', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'specialty_id' => 'required|exists:specialties,id',
            'teacher_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'content_manual' => 'required|string',
            'material_list' => 'required|string',
        ]);
        $subject->update($validated);
        return redirect()->route('panel.subjects.index')->with('success', 'Materia actualizada.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('panel.subjects.index')->with('success', 'Materia eliminada.');
    }
}
