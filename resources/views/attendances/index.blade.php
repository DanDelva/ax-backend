@extends('layouts.app')
@section('title', 'Asistencias')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-emerald-50 text-emerald-500 rounded-xl">✅</span>
                {{ Auth::user()->isAlumno() ? 'Mis Asistencias' : 'Lista de Asistencias' }}
            </h3>
        </div>
        @if(!Auth::user()->isAlumno())
            <a href="{{ route('panel.attendances.quick') }}" class="inline-flex items-center gap-2 bg-ax-500 hover:bg-ax-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5">
                ⚡ Pase Rápido
            </a>
        @endif
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('panel.attendances.index') }}" class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end">

        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Fecha</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
        </div>
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Materia</label>
            <select name="subject_id" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <option value="" disabled {{ !request('subject_id') ? 'selected' : '' }}>-- Seleccione una materia --</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->title }}</option>
                @endforeach
            </select>
        </div>

        @if(!Auth::user()->isAlumno())
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Alumno</label>
            <select name="student_id" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <option value="">Todos los alumnos</option>
                @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ request('student_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="flex gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['date', 'subject_id', 'student_id']))
                <a href="{{ route('panel.attendances.index') }}" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    @if(Auth::user()->isDocente() && !request('subject_id'))
        <div class="py-12 text-center text-slate-500 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
            <div class="text-4xl mb-3 opacity-50">📚</div>
            <p class="font-medium">Seleccione una materia y presione "Filtrar" para ver el historial de asistencias.</p>
        </div>
    @else
    <div class="overflow-x-auto rounded-2xl border border-slate-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Fecha</th>
                    @if(!Auth::user()->isAlumno())<th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estudiante</th>@endif
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Materia</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estatus</th>
                    @if(!Auth::user()->isAlumno())<th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500 text-right">Acciones</th>@endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($attendances as $a)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-4 px-6 font-bold text-slate-700">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                    @if(!Auth::user()->isAlumno())
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">
                                {{ isset($a->user) ? strtoupper(substr($a->user->name, 0, 1)) : '?' }}
                            </div>
                            <span class="text-sm font-semibold text-slate-800">{{ $a->user->name ?? '—' }}</span>
                        </div>
                    </td>
                    @endif
                    <td class="py-4 px-6 text-sm font-medium text-slate-600">{{ $a->subject->title ?? '—' }}</td>
                    <td class="py-4 px-6">
                        @if($a->status === 'presente')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Presente</span>
                        @elseif($a->status === 'falta')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">Falta</span>
                        @elseif($a->status === 'retardo')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">Retardo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">Justificado</span>
                        @endif
                    </td>
                    @if(!Auth::user()->isAlumno())
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('panel.attendances.edit', $a) }}" class="p-2 text-ax-500 hover:bg-ax-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @if(Auth::user()->isAdmin())
                            <form action="{{ route('panel.attendances.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar asistencia?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isAlumno() ? '3' : '5' }}" class="py-12 text-center text-slate-500 bg-slate-50/50">
                        <div class="text-4xl mb-3 opacity-50">✅</div>
                        <p class="font-medium">No hay asistencias registradas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
