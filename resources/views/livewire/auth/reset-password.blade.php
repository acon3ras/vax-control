    <div class="flex items-center justify-center min-h-[80vh]">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl w-full max-w-md border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl"></div>

            <div class="relative">
                <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight">
                    Restablecer Contraseña
                </h2>
                <p class="mt-2 text-center text-sm text-slate-500">
                    Crea una nueva contraseña segura para tu cuenta.
                </p>
            </div>

            @if ($errorMessage)
                <div class="mt-4 rounded-2xl bg-rose-50 p-4 border border-rose-100">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-rose-800">
                                {{ $errorMessage }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="mt-8 space-y-6" wire:submit="resetPassword">
                <input type="hidden" wire:model="token">
                <input type="hidden" wire:model="email">

                <div>
                    <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nueva Contraseña</label>
                    <input wire:model="password" id="password" name="password" type="password" required 
                        class="block w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500/50 transition-all font-bold text-slate-700 placeholder-slate-300" 
                        placeholder="••••••••">
                    @error('password') <span class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Confirmar Contraseña</label>
                    <input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" required 
                        class="block w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500/50 transition-all font-bold text-slate-700 placeholder-slate-300" 
                        placeholder="••••••••">
                </div>

                <div>
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center">
                        <span class="flex items-center">
                            <svg wire:loading.remove wire:target="resetPassword" class="h-5 w-5 mr-2 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <svg wire:loading wire:target="resetPassword" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            RESTABLECER CONTRASEÑA
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
