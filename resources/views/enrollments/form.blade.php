@extends('layouts.app')
@section('title', $enrollment ? 'Editar Inscripción' : 'Nueva Inscripción')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $enrollment ? '✏️ Editar Inscripción' : '➕ Nueva Inscripción' }}</h3>
        <a href="{{ route('panel.enrollments.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $enrollment ? route('panel.enrollments.update', $enrollment) : route('panel.enrollments.store') }}">
        @csrf
        @if($enrollment) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Estudiante</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Seleccionar Estudiante --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('user_id', $enrollment->user_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} (ID: {{ $st->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Especialidad</label>
                <select name="specialty_id" class="form-control" required>
                    <option value="">-- Seleccionar Especialidad --</option>
                    @foreach($specialties as $sp)
                        <option value="{{ $sp->id }}" {{ old('specialty_id', $enrollment->specialty_id ?? '') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nombre del Grupo</label>
                <input type="text" name="group_name" class="form-control" value="{{ old('group_name', $enrollment->group_name ?? '') }}" placeholder="Ej: Generación 1 A" required>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $enrollment ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.enrollments.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
