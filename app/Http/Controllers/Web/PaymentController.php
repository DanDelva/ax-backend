<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Payment::with('user');

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('matricula', 'like', "%{$search}%");
                })->orWhere('concept', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('payments.index', compact('payments', 'students'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('payments.form', ['payment' => null, 'students' => $students]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'concept' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'status' => 'required|in:pendiente,validado,rechazado',
        ]);

        Payment::create($validated);
        return redirect()->route('panel.payments.index')->with('success', 'Pago registrado.');
    }

    public function edit(Payment $payment)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $students = User::where('role', 'estudiante')->orderBy('name')->get();
        return view('payments.form', compact('payment', 'students'));
    }

    public function update(Request $request, Payment $payment)
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'concept' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'status' => 'required|in:pendiente,validado,rechazado',
        ]);
        $payment->update($validated);
        return redirect()->route('panel.payments.index')->with('success', 'Pago actualizado.');
    }

    public function destroy(Payment $payment)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $payment->delete();
        return redirect()->route('panel.payments.index')->with('success', 'Pago eliminado.');
    }
}
