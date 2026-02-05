<x-layouts.app title="Iniciar Sesión">
    <div class="flex items-center justify-center min-h-[80vh]">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-premium-400 to-premium-600"></div>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-premium-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-premium-600 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Bienvenido de nuevo</h2>
                <p class="text-sm text-slate-400 font-medium">Ingresa tus credenciales para acceder</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Correo Electrónico</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-premium-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <input name="email" type="email" value="{{ old('email') }}" required class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500/50 transition-all font-bold text-slate-700 placeholder-slate-300" placeholder="ejemplo@vaxcontrol.cl">
                    </div>
                    @error('email') <span class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Contraseña</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-premium-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input name="password" type="password" required class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500/50 transition-all font-bold text-slate-700 placeholder-slate-300" placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-premium-600 border-2 border-slate-300 rounded focus:ring-premium-500">
                        <span class="ml-2 font-bold text-slate-500">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="font-bold text-slate-400 hover:text-premium-600 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" class="w-full py-4 bg-premium-600 hover:bg-premium-700 text-white font-black rounded-2xl shadow-xl shadow-premium-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center">
                    INGRESAR AL SISTEMA
                </button>
                
                <div class="mt-6 text-center text-xs font-bold text-slate-400">
                    ¿No tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-premium-600 hover:text-premium-700 transition-colors ml-1 hover:underline">Solicitar Acceso</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
