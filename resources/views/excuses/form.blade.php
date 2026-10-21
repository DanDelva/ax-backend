@extends('layouts.app')
@section('title', $excuse ? 'Editar Justificante' : 'Nuevo Justificante')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $excuse ? '✏️ Editar Justificante' : '➕ Nuevo Justificante' }}</h3>
        <a href="{{ route('panel.excuses.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $excuse ? route('panel.excuses.update', $excuse) : route('panel.excuses.store') }}">
        @csrf
        @if($excuse) @method('PUT') @endif
        <div class="form-grid">
            @if(Auth::user()->isAlumno())
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="form-group">
                    <label>Estudiante</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>
            @else
                <div class="form-group">
                    <label>Estudiante</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Seleccionar Estudiante --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" {{ old('user_id', $excuse->user_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} (ID: {{ $st->id }})</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label>Motivo de la Falta</label>
                <input type="text" name="reason" class="form-control" value="{{ old('reason', $excuse->reason ?? '') }}" placeholder="Ej: Cita médica" required>
            </div>
            <div class="form-group">
                <label>Fecha de la Falta</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $excuse->date ?? date('Y-m-d')) }}" required>
            </div>
            @if(!Auth::user()->isAlumno())
                <div class="form-group">
                    <label>Estatus</label>
                    <select name="status" class="form-control" required>
                        <option value="pendiente" {{ old('status', $excuse->status ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobado" {{ old('status', $excuse->status ?? '') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ old('status', $excuse->status ?? '') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
            @else
                <input type="hidden" name="status" value="pendiente">
            @endif
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $excuse ? 'Actualizar' : 'Enviar Justificante' }}</button>
            <a href="{{ route('panel.excuses.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
