@extends('layouts.app')
@section('title', 'Gestión de Horarios')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-violet-50 text-violet-500 rounded-xl">📅</span>
                Horarios Académicos
            </h3>
            <p class="text-slate-500 mt-1 font-medium text-sm">Gestiona y visualiza la disponibilidad de clases.</p>
        </div>
        <div class="flex gap-2 items-center">
            {{-- View Toggles --}}
            <div class="bg-slate-100 p-1 rounded-xl flex">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $viewType === 'list' ? 'bg-white text-violet-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    📄 Lista
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $viewType === 'grid' ? 'bg-white text-violet-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    🗓️ Cuadrícula
                </a>
            </div>
            
            @if(Auth::user()->isAdmin())
            <a href="{{ route('panel.schedules.create') }}" class="inline-flex items-center gap-2 bg-ax-500 hover:bg-ax-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5 ml-2">
                + Nuevo
            </a>
            @endif
        </div>
    </div>

    {{-- Filters (Admin Only, or Teachers viewing subjects) --}}
    @if(Auth::user()->isAdmin() || Auth::user()->isDocente())
    <form method="GET" action="{{ route('panel.schedules.index') }}" class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end">
        <input type="hidden" name="view" value="{{ $viewType }}">
        
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Materia</label>
            <select name="subject_id" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <option value="">Todas las materias</option>
                @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->title }} ({{ $sub->teacher->name ?? 'S/A' }})</option>
                @endforeach
            </select>
        </div>

        @if(Auth::user()->isAdmin())
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ver horario como...</label>
            <select name="role_filter" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none" onchange="toggleUserSelect(this.value)">
                <option value="">Vista General</option>
                <option value="docente" {{ request('role_filter') == 'docente' ? 'selected' : '' }}>Profesor</option>
                <option value="estudiante" {{ request('role_filter') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
            </select>
        </div>

        <div class="flex-1 min-w-[200px]" id="userSelectContainer" style="display: {{ request()->filled('role_filter') ? 'block' : 'none' }}">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" id="userSelectLabel">Seleccionar Usuario</label>
            <select name="user_id" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
                <option value="">Seleccione...</option>
                <optgroup label="Docentes" class="docente-options" style="display: {{ request('role_filter') == 'docente' ? 'block' : 'none' }}">
                    @foreach($docentes as $d)
                        <option value="{{ $d->id }}" {{ request('user_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Estudiantes" class="estudiante-options" style="display: {{ request('role_filter') == 'estudiante' ? 'block' : 'none' }}">
                    @foreach($estudiantes as $e)
                        <option value="{{ $e->id }}" {{ request('user_id') == $e->id ? 'selected' : '' }}>{{ $e->matricula ?? $e->id }} - {{ $e->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        @endif

        <div class="flex gap-2">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['subject_id', 'role_filter', 'user_id']))
                <a href="{{ route('panel.schedules.index', ['view' => $viewType]) }}" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </form>
    @endif

    {{-- VIEW PORTION --}}
    @if($viewType === 'list')
        {{-- LIST VIEW --}}
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Materia</th>
                        <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Profesor</th>
                        <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Día</th>
                        <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Horario</th>
                        <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Salón</th>
                        @if(Auth::user()->isAdmin())<th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500 text-right">Acciones</th>@endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800">{{ $s->subject->title ?? '—' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">
                                    {{ isset($s->subject->teacher) ? strtoupper(substr($s->subject->teacher->name, 0, 1)) : '?' }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $s->subject->teacher->name ?? 'Sin asignar' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">
                                {{ $s->day_of_week }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-slate-600">
                            {{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }}
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-slate-600">
                            📍 {{ $s->classroom }}
                        </td>
                        @if(Auth::user()->isAdmin())
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('panel.schedules.edit', $s) }}" class="p-2 text-ax-500 hover:bg-ax-50 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('panel.schedules.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este horario definitivamente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-500 bg-slate-50/50">
                            <div class="text-4xl mb-3 opacity-50">📅</div>
                            <p class="font-medium">No hay horarios registrados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        {{-- GRID VIEW --}}
        @if($schedules->isEmpty())
            <div class="py-24 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <div class="text-6xl mb-4 opacity-50">🏝️</div>
                <h4 class="text-xl font-bold text-slate-700 mb-2">¡Tiempo Libre!</h4>
                <p class="text-slate-500">Actualmente no hay horarios asignados para los filtros seleccionados.</p>
            </div>
        @else
            <div class="relative overflow-x-auto custom-scrollbar pb-6">
                
                <div class="min-w-[900px] relative mt-4" id="timetableContainer">
                    <div id="timeIndicator" class="time-indicator hidden transition-all duration-[60000ms] ease-linear"></div>

                    <div class="grid grid-cols-6 gap-3">
                        {{-- Corner Empty Space --}}
                        <div class="h-12"></div>

                        {{-- Days Headers --}}
                        @foreach($days as $day)
                            <div class="h-12 flex flex-col items-center justify-center bg-slate-50 rounded-xl border border-slate-200 shadow-sm font-bold text-slate-700 uppercase tracking-widest text-xs">
                                {{ $day }}
                            </div>
                        @endforeach

                        {{-- Grid Body --}}
                        @foreach($hours as $hour)
                            {{-- Hour Label --}}
                            <div class="flex items-start justify-end pr-4 text-xs font-bold text-slate-400 -mt-2 h-[80px]">
                                {{ sprintf('%02d:00', $hour) }}
                            </div>

                            {{-- Day Columns for this hour --}}
                            @foreach($days as $day)
                                <div class="relative h-[80px] border-t border-slate-100 {{ $loop->last ? '' : 'border-r border-slate-50/50' }} p-1 transition-colors hover:bg-slate-100">
                                    
                                    @php
                                        $blocks = $schedules->filter(function($s) use ($day, $hour) {
                                            $startHour = (int) substr($s->start_time, 0, 2);
                                            return $s->day_of_week === $day && $startHour === $hour;
                                        });
                                    @endphp

                                    @foreach($blocks as $block)
                                        @php
                                            $start = strtotime($block->start_time);
                                            $end = strtotime($block->end_time);
                                            $durationHours = ($end - $start) / 3600;
                                            $heightPx = ($durationHours * 80) + (($durationHours - 1) * 12);

                                            $colorClasses = [
                                                'subject-blue', 'subject-rose', 'subject-mint', 
                                                'subject-amber', 'subject-violet', 'subject-sky', 
                                                'subject-coral', 'subject-pink', 'subject-teal', 'subject-indigo'
                                            ];
                                            $colorClass = $colorClasses[$block->subject_id % count($colorClasses)];
                                        @endphp

                                        <div class="absolute top-1 left-1 right-1 z-10 rounded-xl p-3 subject-card {{ $colorClass }} shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col" 
                                             style="height: {{ $heightPx - 8 }}px;">
                                            
                                            {{-- Admin Fast Delete Button --}}
                                            @if(Auth::user()->isAdmin())
                                            <button onclick="deleteSchedule({{ $block->id }})" class="absolute top-1.5 right-1.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110 shadow-md" title="Eliminar bloque">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            @endif

                                            <div class="flex justify-between items-start mb-1 pr-6">
                                                <div class="font-extrabold text-sm leading-tight group-hover:text-opacity-90">{{ $block->subject->title ?? 'Materia' }}</div>
                                            </div>
                                            <div class="bg-white/30 backdrop-blur-sm rounded-md px-1.5 py-0.5 text-[10px] font-bold tracking-wide self-start mb-1">
                                                {{ substr($block->start_time,0,5) }} - {{ substr($block->end_time,0,5) }}
                                            </div>

                                            <div class="text-[11px] font-medium opacity-80 mb-auto">
                                                📍 {{ $block->classroom }}
                                            </div>

                                            <div class="text-[11px] font-semibold flex items-center gap-1 opacity-90 mt-2">
                                                <span>👨‍🏫</span> {{ explode(' ', $block->subject->teacher->name ?? 'Sin asignar')[0] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

@section('scripts')
<script>
    function toggleUserSelect(role) {
        const container = document.getElementById('userSelectContainer');
        if (!role) {
            container.style.display = 'none';
            return;
        }
        container.style.display = 'block';
        
        document.querySelectorAll('.docente-options').forEach(el => el.style.display = role === 'docente' ? 'block' : 'none');
        document.querySelectorAll('.estudiante-options').forEach(el => el.style.display = role === 'estudiante' ? 'block' : 'none');
        
        // Clear value when changing role
        document.querySelector('select[name="user_id"]').value = '';
    }

    function deleteSchedule(id) {
        if(confirm('¿Seguro que deseas eliminar este bloque de horario?')) {
            fetch(`/panel/schedules/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) window.location.reload();
            });
        }
    }
    @endif
</script>
@endsection
