@extends('layouts.app')
@section('title', 'Especialidades')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>🎓 Lista de Especialidades</h3>
        <a href="{{ route('panel.specialties.create') }}" class="btn btn-primary">+ Nueva Especialidad</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Materias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ Str::limit($s->description, 60) }}</td>
                    <td><span class="badge badge-docente">{{ $s->subjects_count }}</span></td>
                    <td class="action-btns">
                        <a href="{{ route('panel.specialties.edit', $s) }}" class="btn btn-sm btn-outline">✏️ Editar</a>
                        <form action="{{ route('panel.specialties.destroy', $s) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>No hay especialidades.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
