@extends('layouts.app')
@section('title', $payment ? 'Editar Pago' : 'Nuevo Pago')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $payment ? '✏️ Editar Pago' : '➕ Nuevo Pago' }}</h3>
        <a href="{{ route('panel.payments.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $payment ? route('panel.payments.update', $payment) : route('panel.payments.store') }}">
        @csrf
        @if($payment) @method('PUT') @endif
        <div class="form-grid">
            @if(Auth::user()->isAlumno())
                {{-- Estudiante: su propio ID, sin dropdown --}}
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="form-group">
                    <label>Estudiante</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>
            @else
                {{-- Admin: dropdown de todos los estudiantes --}}
                <div class="form-group">
                    <label>Estudiante</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Seleccionar Estudiante --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" {{ old('user_id', $payment->user_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} (ID: {{ $st->id }})</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label>Monto ($)</label>
                <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $payment->amount ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Concepto</label>
                <select name="concept" class="form-control" required>
                    @php $concepts = ['Inscripción', 'Mensualidad', 'Material', 'Certificación']; @endphp
                    @foreach($concepts as $c)
                        <option value="{{ $c }}" {{ old('concept', $payment->concept ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Fecha de Pago</label>
                <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date ?? date('Y-m-d')) }}" required>
            </div>
            @if(!Auth::user()->isAlumno())
                <div class="form-group">
                    <label>Estatus</label>
                    <select name="status" class="form-control" required>
                        <option value="pendiente" {{ old('status', $payment->status ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="validado" {{ old('status', $payment->status ?? '') == 'validado' ? 'selected' : '' }}>Validado</option>
                        <option value="rechazado" {{ old('status', $payment->status ?? '') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
            @else
                <input type="hidden" name="status" value="pendiente">
            @endif
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $payment ? 'Actualizar' : 'Registrar Pago' }}</button>
            <a href="{{ route('panel.payments.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
