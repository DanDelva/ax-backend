@extends('layouts.app')
@section('title', $attendance ? 'Editar Asistencia' : 'Nueva Asistencia')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $attendance ? '✏️ Editar Asistencia' : '➕ Nueva Asistencia' }}</h3>
        <a href="{{ route('panel.attendances.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $attendance ? route('panel.attendances.update', $attendance) : route('panel.attendances.store') }}">
        @csrf
        @if($attendance) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Estudiante</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Seleccionar Estudiante --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('user_id', $attendance->user_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} (ID: {{ $st->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Materia</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Seleccionar Materia --</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ old('subject_id', $attendance->subject_id ?? '') == $sub->id ? 'selected' : '' }}>{{ $sub->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $attendance->date ?? date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Estatus</label>
                <select name="status" class="form-control" required>
                    <option value="presente" {{ old('status', $attendance->status ?? '') == 'presente' ? 'selected' : '' }}>Presente</option>
                    <option value="falta" {{ old('status', $attendance->status ?? '') == 'falta' ? 'selected' : '' }}>Falta</option>
                    <option value="retardo" {{ old('status', $attendance->status ?? '') == 'retardo' ? 'selected' : '' }}>Retardo</option>
                    <option value="justificado" {{ old('status', $attendance->status ?? '') == 'justificado' ? 'selected' : '' }}>Justificado</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $attendance ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.attendances.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
