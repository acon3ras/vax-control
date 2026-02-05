<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-3xl border border-slate-100">
        
        <div class="flex justify-center mb-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover shadow-lg shadow-premium-500/20">
                <span class="text-xl font-bold tracking-tight text-slate-900">Control de <span class="text-premium-500">Vacunas</span></span>
            </div>
        </div>

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-slate-900">Activar Cuenta</h2>
            <p class="text-slate-500 text-sm mt-1">Crea una contraseña segura para tu cuenta.</p>
        </div>

        <form wire:submit.prevent="save" class="space-y-5">
            <!-- Email (Read Only) -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email</label>
                <input type="text" value="{{ $user->email }}" disabled class="block w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nueva Contraseña</label>
                <input wire:model="password" id="password" type="password" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('password') border-red-500 @enderror" required autofocus />
                @error('password') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Confirmar Contraseña</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm" required />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-premium-600 hover:bg-premium-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-premium-500 transition-all">
                    Activar Cuenta &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
