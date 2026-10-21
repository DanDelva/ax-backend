@extends('layouts.app')
@section('title', $subject ? 'Editar Materia' : 'Nueva Materia')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $subject ? '✏️ Editar Materia' : '➕ Nueva Materia' }}</h3>
        <a href="{{ route('panel.subjects.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $subject ? route('panel.subjects.update', $subject) : route('panel.subjects.store') }}">
        @csrf
        @if($subject) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Título de la Materia</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $subject->title ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Especialidad</label>
                <select name="specialty_id" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($specialties as $sp)
                        <option value="{{ $sp->id }}" {{ old('specialty_id', $subject->specialty_id ?? '') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Profesor</label>
                <select name="teacher_id" class="form-control">
                    <option value="">-- Sin asignar --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('teacher_id', $subject->teacher_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->name }} (ID: {{ $t->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Contenido del Manual</label>
                <textarea name="content_manual" class="form-control" required>{{ old('content_manual', $subject->content_manual ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Lista de Materiales</label>
                <textarea name="material_list" class="form-control" required>{{ old('material_list', $subject->material_list ?? '') }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $subject ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.subjects.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
