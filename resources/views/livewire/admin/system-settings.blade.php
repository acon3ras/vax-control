<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full font-outfit">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">
            Configuración del Sistema
        </h1>
        <p class="text-slate-500 mt-1">
            Gestione las variables globales y parámetros de seguridad.
        </p>
    </div>

    <div class="glass rounded-2xl border border-white/20 p-6 md:p-8 shadow-sm">
        <div class="flex items-center space-x-3 mb-6 border-b border-slate-100 pb-4">
            <div class="p-2 bg-premium-100 rounded-lg text-premium-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-slate-800">Restricciones de Registro</h2>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <label for="allowedDomains" class="block text-sm font-medium text-slate-700 mb-2">
                    Dominios de Correo Permitidos
                </label>
                <div class="relative">
                    <textarea 
                        id="allowedDomains" 
                        wire:model="allowedDomains" 
                        rows="4" 
                        class="w-full rounded-xl border-slate-200 focus:border-premium-500 focus:ring focus:ring-premium-200 transition-all text-slate-600 shadow-sm"
                        placeholder="ej: saludaysen.cl, ssaaysen.cl"
                    ></textarea>
                </div>
                <p class="mt-2 text-sm text-slate-500">
                    Ingrese los dominios separados por comas. Solo los correos terminados en estos dominios podrán registrarse.
                </p>
                @error('allowedDomains') 
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                <button 
                    type="submit" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-premium-600 hover:bg-premium-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-premium-500 transition-all transform hover:scale-[1.02]"
                >
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 1m0 0l-3-1m3 1v-4"></path></svg>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Success Notification -->
    <div 
        x-data="{ show: false, message: '' }"
        x-init="@this.on('saved', () => { show = true; setTimeout(() => show = false, 3000) })"
        x-show="show" 
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 right-4 max-w-sm w-full bg-white border border-premium-100 shadow-lg rounded-xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-50"
        style="display: none;"
    >
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-premium-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-slate-900">
                        Guardado exitosamente
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        La configuración del sistema ha sido actualizada.
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="bg-white rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-premium-500">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
