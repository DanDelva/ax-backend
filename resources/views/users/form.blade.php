@extends('layouts.app')
@section('title', $user ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>{{ $user ? '✏️ Editar Usuario' : '➕ Nuevo Usuario' }}</h3>
        <a href="{{ route('panel.users.index') }}" class="btn btn-outline">← Volver</a>
    </div>
    <form method="POST" action="{{ $user ? route('panel.users.update', $user) : route('panel.users.store') }}">
        @csrf
        @if($user) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Contraseña {{ $user ? '(dejar vacío para no cambiar)' : '' }}</label>
                <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }}>
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="role" class="form-control" required>
                    <option value="estudiante" {{ old('role', $user->role ?? '') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="docente" {{ old('role', $user->role ?? '') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 {{ $user ? 'Actualizar' : 'Crear' }}</button>
            <a href="{{ route('panel.users.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
