<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $query = Enrollment::with(['user', 'specialty']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('matricula', 'like', "%{$search}%");
                })->orWhereHas('specialty', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhere('group_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->get();
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        $specialties = Specialty::all();
        return view('enrollments.index', compact('enrollments', 'students', 'specialties'));
    }

    public function create()
    {
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        $specialties = Specialty::all();
        return view('enrollments.form', ['enrollment' => null, 'students' => $students, 'specialties' => $specialties]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'group_name' => 'required|string|max:255',
        ]);
        Enrollment::create($validated);
        return redirect()->route('panel.enrollments.index')->with('success', 'Inscripción registrada.');
    }

    public function edit(Enrollment $enrollment)
    {
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        $specialties = Specialty::all();
        return view('enrollments.form', compact('enrollment', 'students', 'specialties'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'group_name' => 'required|string|max:255',
        ]);
        $enrollment->update($validated);
        return redirect()->route('panel.enrollments.index')->with('success', 'Inscripción actualizada.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('panel.enrollments.index')->with('success', 'Inscripción eliminada.');
    }
}
