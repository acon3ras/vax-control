<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestión de Vacunas</h1>
            <p class="text-sm text-slate-500 mt-1">Administra el catálogo de vacunas permitidas en el sistema.</p>
        </div>
        <button 
            wire:click="openModal"
            class="inline-flex items-center px-4 py-2 bg-premium-600 hover:bg-premium-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-premium-600/20"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nueva Vacuna
        </button>
    </div>

    <!-- Stats/Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-2xl border border-slate-200/50">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Total Vacunas</div>
            <div class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Vaccine::count() }}</div>
        </div>
        <div class="glass p-6 rounded-2xl border border-slate-200/50">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Activas</div>
            <div class="text-3xl font-bold text-emerald-600 mt-1">{{ \App\Models\Vaccine::where('status', 'ACTIVE')->count() }}</div>
        </div>
        <div class="glass p-6 rounded-2xl border border-slate-200/50">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest">En Cuarentena</div>
            <div class="text-3xl font-bold text-amber-500 mt-1">{{ \App\Models\Vaccine::where('status', 'QUARANTINE')->count() }}</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="glass rounded-2xl border border-slate-200/50 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative max-w-sm w-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input 
                    wire:model.live="search" 
                    type="text" 
                    placeholder="Buscar por nombre o código..." 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all sm:text-sm"
                >
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium flex items-center">
                <svg class="w-5 h-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl text-sm font-medium flex items-center">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Código</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nombre de Vacuna</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Fabricante</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Disponible</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Cuarentena</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($vaccines as $vaccine)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $vaccine->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">{{ $vaccine->name }}</div>
                                <div class="text-xs text-slate-500">{{ $vaccine->presentation }} • {{ $vaccine->dose_per_unit }} dosis/u</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $vaccine->manufacturer ?? 'No especificado' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold {{ $vaccine->active_stock > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $vaccine->active_stock ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="text-sm font-bold {{ $vaccine->quarantine_stock > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                        {{ $vaccine->quarantine_stock ?? 0 }}
                                    </span>
                                    @if($vaccine->quarantine_stock > 0)
                                        <button 
                                            wire:click.stop="$dispatch('openAdjustmentModal', { vaccineId: {{ $vaccine->id }}, type: 'QUARANTINE_RELEASE' })"
                                            class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-all"
                                            title="Liberar Stock de Cuarentena"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($vaccine->status === 'QUARANTINE')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Cuarentena
                                    </span>
                                @else
                                    <button 
                                        wire:click="toggleStatus({{ $vaccine->id }})"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $vaccine->status === 'ACTIVE' ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                    >
                                        <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $vaccine->status === 'ACTIVE' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <div class="flex items-center space-x-3">
                                    <button 
                                        wire:click="openModal({{ $vaccine->id }})"
                                        class="p-2 text-slate-400 hover:text-premium-600 hover:bg-premium-50 rounded-lg transition-all"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.138 2.97a2.121 2.121 0 113 3l-9.938 9.938a2 2 0 01-.914.517l-3.238.809.809-3.238a2 2 0 01.517-.914l9.938-9.938z"></path></svg>
                                    </button>
                                    
                                    <button 
                                        wire:click.stop="$dispatch('openAdjustmentModal', { vaccineId: {{ $vaccine->id }}, type: 'QUARANTINE_MOVE' })"
                                        @if($vaccine->active_stock <= 0) disabled @endif
                                        class="p-2 text-slate-400 rounded-lg transition-all {{ $vaccine->active_stock > 0 ? 'hover:text-amber-600 hover:bg-amber-50' : 'opacity-30 cursor-not-allowed' }}"
                                        title="{{ $vaccine->active_stock > 0 ? 'Mover stock a Cuarentena' : 'No hay stock disponible para mover' }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                No se encontraron vacunas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $vaccines->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div 
        x-data="{ show: @entangle('showModal') }" 
        x-show="show" 
        x-cloak 
        class="fixed inset-0 z-[60] overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="show = false"></div>

            <div 
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            >
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">{{ $selectedVaccineId ? 'Editar Vacuna' : 'Nueva Vacuna' }}</h3>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nombre de Vacuna</label>
                                <input wire:model="name" type="text" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('name') border-red-500 @enderror">
                                @error('name') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Código Interno</label>
                                <input wire:model="code" type="text" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm @error('code') border-red-500 @enderror">
                                @error('code') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Presentación</label>
                                <select wire:model="presentation" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                                    <option value="">Seleccionar...</option>
                                    <option value="Frasco">Frasco (Vial)</option>
                                    <option value="Jeringa Prellenada">Jeringa Prellenada</option>
                                    <option value="Ampolla">Ampolla</option>
                                </select>
                                @error('presentation') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Fabricante</label>
                                <input wire:model="manufacturer" type="text" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Lote (Opcional)</label>
                                <input wire:model="initial_batch" type="text" placeholder="Ej: STOCK_UNICO" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                                @error('initial_batch') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Dosis por Unidad</label>
                                <input wire:model="dose_per_unit" type="number" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                                @error('dose_per_unit') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Stock Mínimo (Alerta)</label>
                                <input wire:model="min_stock" type="number" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                                @error('min_stock') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Stock Óptimo (Meta)</label>
                                <input wire:model="optimal_stock" type="number" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:bg-white transition-all text-sm">
                                @error('optimal_stock') <span class="text-xs text-red-500 italic mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-6 flex justify-end space-x-3">
                            <button type="button" @click="show = false" class="px-6 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">Cancelar</button>
                            <button type="submit" class="px-8 py-2.5 bg-premium-600 hover:bg-premium-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-premium-600/20">
                                {{ $selectedVaccineId ? 'Guardar Cambios' : 'Crear Vacuna' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
