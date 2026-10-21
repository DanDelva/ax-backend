@extends('layouts.app')
@section('title', 'Inscripciones')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-indigo-50 text-indigo-500 rounded-xl">📝</span>
                Lista de Inscripciones
            </h3>
        </div>
        <a href="{{ route('panel.enrollments.create') }}" class="inline-flex items-center gap-2 bg-ax-500 hover:bg-ax-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5">
            + Nueva Inscripción
        </a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('panel.enrollments.index') }}" class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[250px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar (ID, Alumno, Especialidad, Grupo)</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej: AL01, Cosmetología, 1A..." class="w-full pl-10 bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition-colors">
                Buscar
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('panel.enrollments.index') }}" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">ID</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estudiante</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Especialidad</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Grupo</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($enrollments as $e)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-4 px-6 text-sm font-semibold text-slate-500">#{{ $e->id }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">
                                {{ isset($e->user) ? strtoupper(substr($e->user->name, 0, 1)) : '?' }}
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-slate-800">{{ $e->user->name ?? '—' }}</span>
                                <div class="text-[10px] text-slate-400 font-bold">{{ $e->user->matricula ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm font-bold text-slate-700">{{ $e->specialty->name ?? '—' }}</td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-50 text-violet-600 border border-violet-100">
                            {{ $e->group_name }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('panel.enrollments.edit', $e) }}" class="p-2 text-ax-500 hover:bg-ax-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('panel.enrollments.destroy', $e) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar inscripción?')">
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
                    <td colspan="5" class="py-12 text-center text-slate-500 bg-slate-50/50">
                        <div class="text-4xl mb-3 opacity-50">📝</div>
                        <p class="font-medium">No se encontraron inscripciones.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
