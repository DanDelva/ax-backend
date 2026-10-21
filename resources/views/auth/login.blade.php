<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AX Panel - Iniciar Sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen flex">
    
    <div class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
        {{-- Background decorative elements --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 bg-gradient-to-br from-slate-50 to-indigo-50/50">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-ax-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sky-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="w-full max-w-[420px]">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-10 shadow-elevated border border-white">
                
                <div class="text-center mb-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-ax-500 to-ax-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-glow-purple transform rotate-3">
                        <span class="font-extrabold text-3xl text-white">AX</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Bienvenido de nuevo</h1>
                    <p class="text-slate-500 mt-2 font-medium">Ingresa tus credenciales para continuar</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <ul class="text-rose-800 text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('panel.login.submit') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-ax-500/20 focus:border-ax-500 transition-all font-medium"
                                placeholder="tu@correo.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Contraseña</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-ax-500/20 focus:border-ax-500 transition-all font-medium"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-ax-500 hover:bg-ax-600 text-white font-bold rounded-xl shadow-glow-purple hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
