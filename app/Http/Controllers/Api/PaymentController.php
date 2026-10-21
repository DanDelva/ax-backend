<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isDocente()) {
            abort(403, 'Los docentes no tienen acceso a pagos');
        }

        $query = Payment::with(['user']);

        if ($user->isAlumno()) {
            $query->where('user_id', $user->id);
        }

        return PaymentResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            abort(403, 'Sólo el administrador puede registrar pagos');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'concept' => 'required|string|max:255',
            'status' => 'required|in:pendiente,validado,rechazado',
            'payment_date' => 'required|date',
            'proof_path' => 'nullable|string' // Idealmente recibiría un archivo, simplificado para la API estandar.
        ]);

        $payment = Payment::create($validated);
        return new PaymentResource($payment->load(['user']));
    }

    public function show(Request $request, Payment $payment)
    {
        $user = $request->user();

        if ($user->isDocente()) {
            abort(403, 'Los docentes no tienen acceso a pagos');
        }

        if ($user->isAlumno() && $payment->user_id !== $user->id) {
            abort(403, 'No autorizado para ver este pago');
        }

        return new PaymentResource($payment->load(['user']));
    }

    public function update(Request $request, Payment $payment)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            abort(403, 'Solo el administrador puede actualizar pagos');
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'concept' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pendiente,validado,rechazado',
            'payment_date' => 'sometimes|required|date',
            'proof_path' => 'nullable|string'
        ]);

        $payment->update($validated);
        return new PaymentResource($payment->load(['user']));
    }

    public function destroy(Request $request, Payment $payment)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            abort(403, 'Solo el administrador puede eliminar pagos');
        }

        $payment->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
