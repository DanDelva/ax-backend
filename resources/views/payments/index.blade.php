@extends('layouts.app')
@section('title', 'Pagos')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-card border border-slate-100 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-slate-100 pb-4">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-emerald-50 text-emerald-500 rounded-xl">💰</span>
                {{ Auth::user()->isAlumno() ? 'Mis Pagos' : 'Lista de Pagos' }}
            </h3>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('panel.payments.create') }}" class="inline-flex items-center gap-2 bg-ax-500 hover:bg-ax-600 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-glow-purple hover:-translate-y-0.5">
                + Nuevo Pago
            </a>
        @endif
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('panel.payments.index') }}" class="mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[250px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar (ID, Nombre, Concepto, Estatus)</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej: AL01, Mensualidad, validado..." class="w-full pl-10 bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-ax-500 focus:border-ax-500 block p-2.5 font-medium outline-none">
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
                <a href="{{ route('panel.payments.index') }}" class="bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors">
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
                    @if(!Auth::user()->isAlumno())<th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estudiante</th>@endif
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Concepto</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Monto</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Fecha</th>
                    <th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500">Estatus</th>
                    @if(Auth::user()->isAdmin())<th class="py-4 px-6 text-xs font-extrabold tracking-wider uppercase text-slate-500 text-right">Acciones</th>@endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $p)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-4 px-6 text-sm font-semibold text-slate-500">#{{ $p->id }}</td>
                    @if(!Auth::user()->isAlumno())
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">
                                {{ isset($p->user) ? strtoupper(substr($p->user->name, 0, 1)) : '?' }}
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-slate-800">{{ $p->user->name ?? '—' }}</span>
                                <div class="text-[10px] text-slate-400 font-bold">{{ $p->user->matricula ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td class="py-4 px-6 text-sm font-medium text-slate-700">{{ $p->concept }}</td>
                    <td class="py-4 px-6">
                        <span class="text-lg font-extrabold text-slate-800">
                            ${{ number_format($p->amount, 2) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500 font-medium">{{ \Carbon\Carbon::parse($p->payment_date)->format('d M, Y') }}</td>
                    <td class="py-4 px-6">
                        @if($p->status === 'validado')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Validado</span>
                        @elseif($p->status === 'pendiente')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">Pendiente</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">Rechazado</span>
                        @endif
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('panel.payments.edit', $p) }}" class="p-2 text-ax-500 hover:bg-ax-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('panel.payments.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar pago?')">
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
                    <td colspan="{{ Auth::user()->isAlumno() ? '5' : '7' }}" class="py-12 text-center text-slate-500 bg-slate-50/50">
                        <div class="text-4xl mb-3 opacity-50">💰</div>
                        <p class="font-medium">No se encontraron pagos.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
