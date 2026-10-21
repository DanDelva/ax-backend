@extends('layouts.app')
@section('title', $specialty ? 'Editar Especialidad' : 'Nueva Especialidad')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $specialty ? '✏️ Editar Especialidad' : '➕ Nueva Especialidad' }}</h3>
        <a href="{{ route('panel.specialties.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $specialty ? route('panel.specialties.update', $specialty) : route('panel.specialties.store') }}">
        @csrf
        @if($specialty) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $specialty->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="description" class="form-control">{{ old('description', $specialty->description ?? '') }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $specialty ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.specialties.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
