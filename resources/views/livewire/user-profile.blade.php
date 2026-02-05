<div class="max-w-4xl mx-auto space-y-8">
    
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Mi Perfil</h1>
        <p class="text-sm text-slate-500 mt-1">Administra tu información personal y seguridad.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Profile Information -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Información Personal</h2>
            
            @if (session('profile-success'))
                <div class="mb-6 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ session('profile-success') }}
                </div>
            @endif

            <form wire:submit="updateProfile" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nombre Completo</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 font-medium text-slate-900 transition-all">
                    @error('name') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 font-medium text-slate-900 transition-all">
                    @error('email') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg shadow-slate-900/10 transition-all transform active:scale-[0.98]">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Seguridad</h2>

            @if (session('password-success'))
                <div class="mb-6 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ session('password-success') }}
                </div>
            @endif

            <form wire:submit="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Contraseña Actual</label>
                    <input type="password" wire:model="current_password" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 font-medium text-slate-900 transition-all">
                    @error('current_password') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                    <input type="password" wire:model="new_password" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 font-medium text-slate-900 transition-all">
                    @error('new_password') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirmar Nueva Contraseña</label>
                    <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 font-medium text-slate-900 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-premium-500 hover:bg-premium-600 text-white font-bold rounded-xl shadow-lg shadow-premium-500/20 transition-all transform active:scale-[0.98]">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
