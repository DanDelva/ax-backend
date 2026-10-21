@extends('layouts.app')
@section('title', 'Materias')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📚 {{ Auth::user()->isDocente() ? 'Mis Materias' : 'Lista de Materias' }}</h3>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('panel.subjects.create') }}" class="btn btn-primary">+ Nueva Materia</a>
        @endif
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Especialidad</th>
                    <th>Profesor</th>
                    @if(Auth::user()->isAdmin())<th>Acciones</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td><strong>{{ $s->title }}</strong></td>
                    <td>{{ $s->specialty->name ?? '—' }}</td>
                    <td>{{ $s->teacher->name ?? 'Sin asignar' }}</td>
                    @if(Auth::user()->isAdmin())
                    <td class="action-btns">
                        <a href="{{ route('panel.subjects.edit', $s) }}" class="btn btn-sm btn-outline">✏️ Editar</a>
                        <form action="{{ route('panel.subjects.destroy', $s) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>No hay materias.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
