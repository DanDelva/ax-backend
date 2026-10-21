@extends('layouts.app')
@section('title', $grade ? 'Editar Calificación' : 'Nueva Calificación')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $grade ? '✏️ Editar Calificación' : '➕ Nueva Calificación' }}</h3>
        <a href="{{ route('panel.grades.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $grade ? route('panel.grades.update', $grade) : route('panel.grades.store') }}">
        @csrf
        @if($grade) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Estudiante</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Seleccionar Estudiante --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('user_id', $grade->user_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }} (ID: {{ $st->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Materia</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Seleccionar Materia --</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ old('subject_id', $grade->subject_id ?? '') == $sub->id ? 'selected' : '' }}>{{ $sub->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Calificación (0-10)</label>
                <input type="number" name="score" class="form-control" step="0.01" min="0" max="10" value="{{ old('score', $grade->score ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select name="type" class="form-control" required>
                    <option value="teorica" {{ old('type', $grade->type ?? '') == 'teorica' ? 'selected' : '' }}>Teórica</option>
                    <option value="practica" {{ old('type', $grade->type ?? '') == 'practica' ? 'selected' : '' }}>Práctica</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $grade ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.grades.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
