<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Excuse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExcuseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAlumno()) {
            $excuses = Excuse::with('user')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $excuses = Excuse::with('user')->orderBy('created_at', 'desc')->get();
        }

        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('excuses.index', compact('excuses', 'students'));
    }

    public function create()
    {
        if (Auth::user()->isDocente()) abort(403);
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('excuses.form', ['excuse' => null, 'students' => $students]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->isDocente()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:pendiente,aprobado,rechazado',
        ]);

        if (Auth::user()->isAlumno()) {
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'pendiente';
        }

        Excuse::create($validated);
        return redirect()->route('panel.excuses.index')->with('success', 'Justificante registrado.');
    }

    public function edit(Excuse $excuse)
    {
        if (Auth::user()->isAlumno()) abort(403);
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('excuses.form', compact('excuse', 'students'));
    }

    public function update(Request $request, Excuse $excuse)
    {
        if (Auth::user()->isAlumno()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:pendiente,aprobado,rechazado',
        ]);
        $excuse->update($validated);
        return redirect()->route('panel.excuses.index')->with('success', 'Justificante actualizado.');
    }

    public function destroy(Excuse $excuse)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $excuse->delete();
        return redirect()->route('panel.excuses.index')->with('success', 'Justificante eliminado.');
    }
}
