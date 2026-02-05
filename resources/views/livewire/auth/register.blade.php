<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-3xl border border-slate-100">
        
        <div class="flex justify-center mb-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover shadow-lg shadow-premium-500/20">
                <span class="text-xl font-bold tracking-tight text-slate-900">Control de <span class="text-premium-500">Vacunas</span></span>
            </div>
        </div>

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-slate-900">Crear Cuenta</h2>
            <p class="text-slate-500 text-sm mt-1">Ingresa tus datos para solicitar acceso.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('message') }}
            </div>
        @else

        <form wire:submit.prevent="register" class="space-y-5" novalidate>
            <!-- RUT -->
            <div>
                <label for="rut" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">RUT</label>
                <input wire:model.blur="rut" id="rut" type="text" placeholder="Ej: 12.345.678-9" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('rut') border-red-500 @enderror" required autofocus />
                @error('rut') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Nombre Completo -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nombre Completo</label>
                <input wire:model="name" id="name" type="text" placeholder="Nombres y Apellidos" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('name') border-red-500 @enderror" required />
                <p class="text-[10px] text-slate-400 mt-1">Debe incluir al menos un nombre y dos apellidos.</p>
                @error('name') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email Institucional</label>
                <input wire:model="email" id="email" type="email" placeholder="nombre@hospital.cl" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('email') border-red-500 @enderror" required />
                @error('email') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Cargo -->
                <div>
                    <label for="position_title" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Cargo</label>
                    <input wire:model="position_title" id="position_title" type="text" placeholder="Ej: Enfermero/a" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('position_title') border-red-500 @enderror" required />
                    @error('position_title') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Servicio/Area -->
                <div>
                    <label for="unit_service" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Servicio / Área</label>
                    <input wire:model="unit_service" id="unit_service" type="text" placeholder="Ej: Urgencias" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('unit_service') border-red-500 @enderror" required />
                    @error('unit_service') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-premium-600 hover:bg-premium-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-premium-500 transition-all">
                    Registrarse &rarr;
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-premium-600 transition-colors">
                    ¿Ya tienes cuenta? Iniciar Sesión
                </a>
            </div>
        </form>
        @endif
    </div>
</div>
