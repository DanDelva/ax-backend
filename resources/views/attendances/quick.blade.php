@extends('layouts.app')
@section('title', 'Pase de Lista Rápido')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-emerald-50 text-emerald-500 rounded-xl">⚡</span>
                Pase de Lista Rápido
            </h3>
        </div>
        <a href="{{ route('panel.attendances.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-5 rounded-xl transition-all">
            ← Volver a Asistencias
        </a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('panel.attendances.quick') }}" class="mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end" id="quick-filter-form">
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Seleccionar Materia</label>
            <select name="subject_id" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none" onchange="document.getElementById('quick-filter-form').submit();" required>
                <option value="">-- Elija una materia --</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Fecha</label>
            <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none" onchange="document.getElementById('quick-filter-form').submit();" required>
        </div>
    </form>

    @if(request('subject_id'))
        @if(count($students) > 0)
            <form method="POST" action="{{ route('panel.attendances.quick.store') }}">
                @csrf
                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                <input type="hidden" name="date" value="{{ request('date', date('Y-m-d')) }}">

                <div class="overflow-x-auto rounded-2xl border border-slate-100 mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estudiante</th>
                                <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-center text-emerald-500">Presente</th>
                                <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-center text-rose-500">Falta</th>
                                <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-center text-amber-500">Retardo</th>
                                <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-center text-sky-500">Justificado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($students as $st)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-ax-100 text-ax-600 flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($st->name, 0, 1)) }}
                                        </div>
                                        <div class="font-medium text-slate-800">{{ $st->name }} <span class="text-xs text-slate-400 block">{{ $st->matricula ?? 'Sin matrícula' }}</span></div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <input type="radio" name="attendances[{{ $st->id }}]" value="presente" class="w-5 h-5 text-emerald-500 focus:ring-emerald-500" checked>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <input type="radio" name="attendances[{{ $st->id }}]" value="falta" class="w-5 h-5 text-rose-500 focus:ring-rose-500">
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <input type="radio" name="attendances[{{ $st->id }}]" value="retardo" class="w-5 h-5 text-amber-500 focus:ring-amber-500">
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <input type="radio" name="attendances[{{ $st->id }}]" value="justificado" class="w-5 h-5 text-sky-500 focus:ring-sky-500">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-ax-500 hover:bg-ax-600 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Guardar Lista
                    </button>
                </div>
            </form>
        @else
            <div class="py-12 text-center text-slate-500 bg-slate-50/50 rounded-2xl">
                <div class="text-4xl mb-3 opacity-50">👥</div>
                <p class="font-medium">No hay alumnos inscritos en esta materia/especialidad.</p>
            </div>
        @endif
    @else
        <div class="py-12 text-center text-slate-500 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
            <div class="text-4xl mb-3 opacity-50">📚</div>
            <p class="font-medium">Seleccione una materia y fecha para comenzar el pase de lista.</p>
        </div>
    @endif
</div>
@endsection
