<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    // RF2.1 y RF2.2 - Estado de Cuenta y Control de Pagos 
    public function getPaymentHistory(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }
}