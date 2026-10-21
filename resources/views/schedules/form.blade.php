@extends('layouts.app')
@section('title', $schedule ? 'Editar Horario' : 'Nuevo Horario')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                {{ $schedule ? '✏️ Editar Horario' : '➕ Nuevo Horario' }}
            </h3>
            <p class="text-slate-500 mt-1 font-medium">Completa la información del bloque de clases.</p>
        </div>
        <a href="{{ route('panel.schedules.index') }}" class="text-slate-500 hover:text-slate-700 font-bold bg-white border border-slate-200 px-4 py-2 rounded-xl transition-all hover:bg-slate-50 shadow-sm">
            ← Volver
        </a>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100">
        <form method="POST" action="{{ $schedule ? route('panel.schedules.update', $schedule) : route('panel.schedules.store') }}">
            @csrf
            @if($schedule) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Materia --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Materia y Profesor</label>
                    <div class="relative">
                        <select name="subject_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-3.5 appearance-none font-medium cursor-pointer transition-colors" required>
                            <option value="">-- Seleccionar Materia --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ old('subject_id', $schedule->subject_id ?? '') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->title }} (Prof: {{ $sub->teacher->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Día --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Día de la Semana</label>
                    <div class="relative">
                        <select name="day_of_week" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-3.5 appearance-none font-medium cursor-pointer transition-colors" required>
                            @php $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado']; @endphp
                            @foreach($dias as $d)
                                <option value="{{ $d }}" {{ old('day_of_week', $schedule->day_of_week ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Salón --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Salón / Aula</label>
                    <input type="text" name="classroom" value="{{ old('classroom', $schedule->classroom ?? '') }}" placeholder="Ej: Laboratorio 3" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-3.5 font-medium transition-colors" required>
                </div>

                {{-- Horas --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hora de Inicio</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $schedule ? substr($schedule->start_time, 0, 5) : '08:00') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-3.5 font-medium cursor-pointer transition-colors" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hora de Fin</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $schedule ? substr($schedule->end_time, 0, 5) : '10:00') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-3.5 font-medium cursor-pointer transition-colors" required>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-3">
                <button type="submit" class="bg-ax-500 hover:bg-ax-600 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5">
                    💾 {{ $schedule ? 'Guardar Cambios' : 'Crear Horario' }}
                </button>
                <a href="{{ route('panel.schedules.index') }}" class="text-slate-600 hover:text-slate-900 font-bold px-6 py-3 rounded-xl transition-colors hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
