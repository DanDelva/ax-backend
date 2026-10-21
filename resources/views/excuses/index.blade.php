@extends('layouts.app')
@section('title', 'Justificantes')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📄 {{ Auth::user()->isAlumno() ? 'Mis Justificantes' : 'Lista de Justificantes' }}</h3>
        @if(Auth::user()->isAdmin() || Auth::user()->isAlumno())
            <a href="{{ route('panel.excuses.create') }}" class="btn btn-primary">+ {{ Auth::user()->isAlumno() ? 'Enviar Justificante' : 'Nuevo Justificante' }}</a>
        @endif
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    @if(!Auth::user()->isAlumno())<th>Estudiante</th>@endif
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    @if(!Auth::user()->isAlumno())<th>Acciones</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($excuses as $e)
                <tr>
                    <td>{{ $e->id }}</td>
                    @if(!Auth::user()->isAlumno())<td><strong>{{ $e->user->name ?? '—' }}</strong></td>@endif
                    <td>{{ Str::limit($e->reason, 40) }}</td>
                    <td>{{ $e->date }}</td>
                    <td><span class="badge badge-{{ $e->status }}">{{ ucfirst($e->status) }}</span></td>
                    @if(!Auth::user()->isAlumno())
                    <td class="action-btns">
                        <a href="{{ route('panel.excuses.edit', $e) }}" class="btn btn-sm btn-outline">✏️ {{ Auth::user()->isAdmin() ? 'Editar' : 'Revisar' }}</a>
                        @if(Auth::user()->isAdmin())
                        <form action="{{ route('panel.excuses.destroy', $e) }}" method="POST" class="delete-form" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>No hay justificantes.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
