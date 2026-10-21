@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-ax-50 text-ax-500 rounded-xl">👥</span>
                Lista de Usuarios
            </h3>
        </div>
        <a href="{{ route('panel.users.create') }}" class="inline-flex items-center gap-2 bg-ax-500 hover:bg-ax-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5">
            + Nuevo Usuario
        </a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('panel.users.index') }}" class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[250px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar por Nombre, Email o ID</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej: Juan, AL01, 15..." class="w-full pl-10 bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Rol</label>
            <select name="role" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <option value="">Todos los roles</option>
                <option value="estudiante" {{ request('role') == 'estudiante' ? 'selected' : '' }}>Estudiantes</option>
                <option value="docente" {{ request('role') == 'docente' ? 'selected' : '' }}>Docentes</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition-colors">
                Buscar
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('panel.users.index') }}" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Usuario</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Contacto</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Rol</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-xs font-semibold text-slate-400">ID: {{ $user->matricula ?? $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-slate-700">{{ $user->email }}</div>
                        <div class="text-xs font-semibold text-slate-500 mt-0.5">📞 {{ $user->phone ?? 'Sin teléfono' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-50 text-violet-600 border border-violet-100">Administrador</span>
                        @elseif($user->role === 'docente')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">Docente</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Estudiante</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('panel.users.edit', $user) }}" class="p-2 text-ax-500 hover:bg-ax-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('panel.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este usuario definitivamente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-slate-500 bg-slate-50/50">
                        <div class="text-4xl mb-3 opacity-50">👥</div>
                        <p class="font-medium">No se encontraron usuarios con esos criterios.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
