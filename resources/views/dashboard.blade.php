@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h3 class="text-lg font-bold text-slate-800 mb-1">¡Hola de nuevo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h3>
    <p class="text-slate-500">Aquí tienes un resumen de tu actividad.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 stagger-children">
    @if($role === 'admin')
        {{-- Admin Stats --}}
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                👥
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalUsers }}</div>
                <div class="text-sm font-medium text-slate-500">Usuarios Totales</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                🎒
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalStudents }}</div>
                <div class="text-sm font-medium text-slate-500">Estudiantes</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                👨‍🏫
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalTeachers }}</div>
                <div class="text-sm font-medium text-slate-500">Docentes</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                🎓
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalSpecialties }}</div>
                <div class="text-sm font-medium text-slate-500">Especialidades</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                📚
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalSubjects }}</div>
                <div class="text-sm font-medium text-slate-500">Materias</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                📝
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalEnrollments }}</div>
                <div class="text-sm font-medium text-slate-500">Inscripciones</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                💰
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $pendingPayments }}</div>
                <div class="text-sm font-medium text-slate-500">Pagos Pendientes</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                📄
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $pendingExcuses }}</div>
                <div class="text-sm font-medium text-slate-500">Justificantes Ptes.</div>
            </div>
        </div>

    @elseif($role === 'docente')
        {{-- Docente Stats --}}
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-ax-50 text-ax-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                📚
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalSubjects }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Materias</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                🎒
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalStudents }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Alumnos</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                📊
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalGrades }}</div>
                <div class="text-sm font-medium text-slate-500">Calificaciones Reg.</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                ✅
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalAttendances }}</div>
                <div class="text-sm font-medium text-slate-500">Asistencias Reg.</div>
            </div>
        </div>

    @else
        {{-- Estudiante Stats --}}
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                📊
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalGrades }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Calificaciones</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                💰
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalPayments }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Pagos</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5 relative overflow-hidden">
            @if($pendingPayments > 0)
                <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/10 rounded-bl-full -z-10"></div>
                <div class="absolute top-3 right-3 w-2.5 h-2.5 bg-amber-500 rounded-full animate-ping"></div>
            @endif
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                ⏳
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $pendingPayments }}</div>
                <div class="text-sm font-medium text-slate-500">Pagos Pendientes</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                ✅
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalAttendances }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Asistencias</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-card hover:shadow-elevated transition-all duration-300 group border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300">
                📄
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-800">{{ $totalExcuses }}</div>
                <div class="text-sm font-medium text-slate-500">Mis Justificantes</div>
            </div>
        </div>
    @endif
</div>
@endsection
