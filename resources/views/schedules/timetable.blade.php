@extends('layouts.app')
@section('title', 'Mi Horario Semanal')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-indigo-50 text-indigo-500 rounded-xl">📅</span>
                Mi Horario de Clases
            </h3>
            <p class="text-slate-500 mt-1 font-medium">Visualización de tu jornada académica.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3 shadow-sm">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-sm font-bold text-slate-700">Hora Actual</span>
                <span id="currentTimeDisplay" class="text-xs font-mono bg-white px-2 py-1 rounded-md border border-slate-200 text-slate-500"></span>
            </div>
        </div>
    </div>

    @if($schedules->isEmpty())
        <div class="py-24 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
            <div class="text-6xl mb-4 opacity-50">🏝️</div>
            <h4 class="text-xl font-bold text-slate-700 mb-2">¡Tiempo Libre!</h4>
            <p class="text-slate-500">Actualmente no tienes horarios asignados para ninguna materia.</p>
        </div>
    @else
        <div class="relative overflow-x-auto custom-scrollbar pb-6">
            {{-- Wrapper relative to hold the absolute time indicator --}}
            <div class="min-w-[900px] relative mt-4" id="timetableContainer">
                
                {{-- Time Indicator Line (position updated via JS) --}}
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
                            <div class="relative h-[80px] border-t border-slate-100 {{ $loop->last ? '' : 'border-r border-slate-50/50' }} p-1 transition-colors hover:bg-slate-50/50">
                                @php
                                    $blocks = $schedules->filter(function($s) use ($day, $hour) {
                                        $startHour = (int) substr($s->start_time, 0, 2);
                                        return $s->day_of_week === $day && $startHour === $hour;
                                    });
                                @endphp

                                @foreach($blocks as $block)
                                    @php
                                        // Calculate duration in hours to set card height
                                        $start = strtotime($block->start_time);
                                        $end = strtotime($block->end_time);
                                        $durationHours = ($end - $start) / 3600;
                                        // 80px per hour base height + 12px gap absorption
                                        $heightPx = ($durationHours * 80) + (($durationHours - 1) * 12);

                                        // Subject colors assignment based on ID to be consistent
                                        $subjectId = $block->subject_id;
                                        $colorClasses = [
                                            'subject-blue', 'subject-rose', 'subject-mint', 
                                            'subject-amber', 'subject-violet', 'subject-sky', 
                                            'subject-coral', 'subject-pink', 'subject-teal', 'subject-indigo'
                                        ];
                                        $colorClass = $colorClasses[$subjectId % count($colorClasses)];
                                    @endphp

                                    <div class="absolute top-1 left-1 right-1 z-10 rounded-xl p-3 subject-card {{ $colorClass }} shadow-sm hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 group cursor-pointer overflow-hidden flex flex-col" style="height: {{ $heightPx - 8 }}px;">
                                        
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="font-extrabold text-sm leading-tight group-hover:text-opacity-90">{{ $block->subject->title ?? 'Materia' }}</div>
                                            <div class="bg-white/30 backdrop-blur-sm rounded-md px-1.5 py-0.5 text-[10px] font-bold tracking-wide">
                                                {{ substr($block->start_time,0,5) }}
                                            </div>
                                        </div>

                                        <div class="text-[11px] font-medium opacity-80 mb-auto">
                                            📍 {{ $block->classroom }}
                                        </div>

                                        @if(Auth::user()->isAlumno() && isset($block->subject->teacher->name))
                                            <div class="text-[11px] font-semibold flex items-center gap-1 opacity-90 mt-2">
                                                <span>👨‍🏫</span> {{ explode(' ', $block->subject->teacher->name)[0] }}
                                            </div>
                                        @endif

                                        {{-- Hover Action Quick Actions --}}
                                        <div class="absolute inset-x-0 bottom-0 bg-white/95 backdrop-blur-md p-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex justify-center gap-2 rounded-b-xl border-t border-black/5">
                                            <button class="text-[10px] font-bold bg-slate-800 text-white px-3 py-1.5 rounded-lg hover:bg-slate-700 transition-colors w-full">Ver Detalles</button>
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateTimeIndicator() {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        
        // Update digital clock
        const timeDisplay = document.getElementById('currentTimeDisplay');
        if(timeDisplay) {
            timeDisplay.innerText = `${currentHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
        }

        const indicator = document.getElementById('timeIndicator');
        if(!indicator) return;

        // Our timetable starts at min hour (e.g. 7 or 8)
        // Let's grab the first hour from the DOM to calculate offset
        const hourLabels = document.querySelectorAll('.text-slate-400');
        if(hourLabels.length === 0) return;
        
        const firstHourText = hourLabels[0].innerText;
        const firstHour = parseInt(firstHourText.split(':')[0]);
        const lastHour = firstHour + hourLabels.length;

        // If current time is within our timetable range
        if(currentHour >= firstHour && currentHour <= lastHour) {
            indicator.classList.remove('hidden');
            
            // Calculate top position
            // Header is ~48px (h-12) + gap (12px) = 60px
            // Each hour row is 80px height + 12px gap = 92px total per hour block
            const headerOffset = 60;
            const hourRowHeight = 92;
            
            const hoursPassed = currentHour - firstHour;
            const minutesRatio = currentMinute / 60;
            
            const topPosition = headerOffset + (hoursPassed * hourRowHeight) + (minutesRatio * hourRowHeight);
            
            indicator.style.top = `${topPosition}px`;
        } else {
            indicator.classList.add('hidden');
        }
    }

    // Run immediately and every 1 minute
    updateTimeIndicator();
    setInterval(updateTimeIndicator, 60000);
});
</script>
@endsection
