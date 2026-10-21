<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AX Panel - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-[#f4f6fb] min-h-screen flex">
    
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 w-64 bg-navy-800 text-white flex flex-col z-50 transition-transform duration-300 shadow-elevated">
        <div class="px-6 py-5 flex items-center gap-3 border-b border-navy-700/50">
            <div class="w-10 h-10 bg-gradient-to-br from-ax-500 to-ax-700 rounded-xl flex items-center justify-center font-extrabold text-xl shadow-glow-purple">
                AX
            </div>
            <span class="font-bold text-xl tracking-tight">AX Panel</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 custom-scrollbar flex flex-col gap-1">
            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-2">Principal</div>
            
            <a href="{{ route('panel.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.dashboard') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('panel.schedule.timetable') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.schedule.timetable') ? 'bg-ax-500 text-white shadow-glow-purple border-l-4 border-ax-300' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white border-l-4 border-transparent hover:border-ax-500/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Mi Horario
            </a>

            @if(Auth::user()->isAdmin())
            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-4">Gestión de Usuarios</div>
            <a href="{{ route('panel.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.users.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Usuarios
            </a>

            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-4">Académico</div>
            <a href="{{ route('panel.specialties.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.specialties.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                Especialidades
            </a>
            <a href="{{ route('panel.subjects.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.subjects.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Materias
            </a>
            <a href="{{ route('panel.enrollments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.enrollments.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Inscripciones
            </a>
            <a href="{{ route('panel.grades.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.grades.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Calificaciones
            </a>
            <a href="{{ route('panel.schedules.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.schedules.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Gestionar Horarios
            </a>

            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-4">Control</div>
            <a href="{{ route('panel.attendances.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.attendances.index', 'panel.attendances.create', 'panel.attendances.edit') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Asistencias
            </a>
            <a href="{{ route('panel.attendances.quick') }}" class="flex items-center gap-3 px-4 py-2.5 pl-8 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.attendances.quick') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Pase de Lista Rápido
            </a>
            <a href="{{ route('panel.excuses.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.excuses.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Justificantes
            </a>
            <a href="{{ route('panel.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.payments.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pagos
            </a>

            @elseif(Auth::user()->isDocente())
            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-4">Mis Clases</div>
            <a href="{{ route('panel.subjects.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.subjects.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Mis Materias
            </a>
            <a href="{{ route('panel.grades.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.grades.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Calificaciones
            </a>
            <a href="{{ route('panel.attendances.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.attendances.index', 'panel.attendances.create', 'panel.attendances.edit') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Asistencias
            </a>
            <a href="{{ route('panel.attendances.quick') }}" class="flex items-center gap-3 px-4 py-2.5 pl-8 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.attendances.quick') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Pase de Lista Rápido
            </a>
            <a href="{{ route('panel.excuses.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.excuses.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Justificantes
            </a>

            @else
            <div class="text-[10px] uppercase tracking-wider text-navy-300 font-bold px-4 py-2 mt-4">Mi Información</div>
            <a href="{{ route('panel.grades.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.grades.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Mis Calificaciones
            </a>
            <a href="{{ route('panel.attendances.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.attendances.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Mis Asistencias
            </a>
            <a href="{{ route('panel.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.payments.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Mis Pagos
            </a>
            <a href="{{ route('panel.excuses.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('panel.excuses.*') ? 'bg-ax-500 text-white shadow-glow-purple' : 'text-navy-200 hover:bg-navy-700/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Mis Justificantes
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-navy-700/50 bg-navy-800/80 backdrop-blur-md">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-navy-600 flex items-center justify-center font-bold text-ax-300 ring-2 ring-ax-500/30">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-navy-300 capitalize truncate">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form action="{{ route('panel.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-rose-300 bg-rose-500/10 hover:bg-rose-500 hover:text-white border border-rose-500/20 rounded-xl transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        {{-- Glassmorphism Topbar --}}
        <header class="sticky top-0 z-40 bg-white/70 backdrop-blur-lg border-b border-slate-200/60 px-8 py-4 flex items-center justify-between shadow-sm">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h2>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                    <div class="w-2 h-2 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-violet-500' : (Auth::user()->role === 'docente' ? 'bg-sky-500' : 'bg-emerald-500') }} animate-pulse"></div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-600">{{ Auth::user()->role }}</span>
                </div>
                <div class="text-sm font-medium text-slate-500 bg-slate-100/80 px-3 py-1.5 rounded-full">
                    {{ now()->translatedFormat('l d \d\e F, Y') }}
                </div>
            </div>
        </header>

        {{-- Content Area --}}
        <div class="flex-1 p-8 overflow-x-hidden">
            @if(session('success'))
                <div class="animate-slide-down mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="animate-slide-down mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="animate-slide-down mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-amber-800 font-bold mb-1">Hay errores en el formulario:</h4>
                        <ul class="text-amber-700 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="animate-fade-in-up">
                @yield('content')
            </div>
        </div>
    </main>

    @yield('scripts')
</body>
</html>
