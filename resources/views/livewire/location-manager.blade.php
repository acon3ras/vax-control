<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Dependencias</h1>
        <button wire:click="create" class="bg-premium-600 hover:bg-premium-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-premium-500/30 transition-all transform active:scale-95 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Nueva Dependencia
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nombre</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipo</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Estado</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($locations as $location)
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-slate-900">{{ $location->name }}</div>
                            <div class="text-xs text-slate-400">{{ $location->description ?? 'Sin descripción' }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $location->type }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <button wire:click="toggleStatus({{ $location->id }})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $location->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $location->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button wire:click="edit({{ $location->id }})" class="text-premium-600 hover:text-premium-800 font-bold text-sm">Editar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">No hay dependencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div 
            class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="$set('showModal', false)" class="fixed inset-0 transition-opacity bg-slate-900/60 cursor-pointer" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">{{ $selectedId ? 'Editar Dependencia' : 'Nueva Dependencia' }}</h3>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nombre</label>
                        <input wire:model="name" type="text" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all font-semibold">
                        @error('name') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Descripción</label>
                        <textarea wire:model="description" rows="3" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all font-semibold"></textarea>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-500 font-bold hover:text-slate-700">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-premium-600 hover:bg-premium-700 text-white font-bold rounded-xl shadow-lg shadow-premium-500/30">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
