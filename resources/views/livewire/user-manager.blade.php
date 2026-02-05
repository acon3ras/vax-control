<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Usuarios</h1>
            <p class="text-sm text-slate-500 mt-1">Administra accesos y roles del personal.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative max-w-sm w-full group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuario..." class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="glass rounded-[2rem] border border-slate-200/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cargo / Área</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Estado</th>
                        @foreach($roles as $role)
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">{{ $role->label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-premium-100 text-premium-600 flex items-center justify-center text-sm font-bold mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-bold text-slate-900">{{ $user->name }}</div>
                                            <button wire:click="edit({{ $user->id }})" class="text-xs text-blue-600 hover:text-blue-800 underline">Editar</button>
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $user->rut }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $user->position_title }}</div>
                                <div class="text-xs text-slate-500">{{ $user->unit_service }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(auth()->user()->hasAnyRole(['admin', 'encargado']))
                                    <button 
                                        wire:click="toggleStatus({{ $user->id }})"
                                        wire:confirm="¿Estás seguro de cambiar el estado de este usuario?"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold transition-colors shadow-sm {{ $user->status === 'ACTIVE' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}"
                                        title="Click para cambiar estado"
                                    >
                                        {{ $user->status === 'ACTIVE' ? 'ACTIVO' : 'PENDIENTE/BLOQ' }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $user->status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $user->status === 'ACTIVE' ? 'ACTIVO' : 'PENDIENTE' }}
                                    </span>
                                @endif
                            </td>
                            @foreach($roles as $role)
                                <td class="px-6 py-4 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleRole({{ $user->id }}, '{{ $role->name }}')"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                            class="form-checkbox h-5 w-5 text-premium-600 rounded border-slate-300 focus:ring-premium-500 transition duration-150 ease-in-out"
                                        >
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + count($roles) }}" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit User Modal -->
    @if($isEditing)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:transition.opacity>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Editar Usuario</h2>
            
            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Completo</label>
                    <input type="text" wire:model="name" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">RUT</label>
                    <input type="text" wire:model="rut" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm">
                    @error('rut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                    <input type="email" wire:model="email" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cargo</label>
                        <input type="text" wire:model="position_title" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm">
                        @error('position_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Servicio / Área</label>
                        <input type="text" wire:model="unit_service" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm">
                        @error('unit_service') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Asignar Rol</label>
                    <select wire:model="role" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-premium-500 focus:border-premium-500 sm:text-sm bg-slate-50">
                        <option value="">-- Sin Rol --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Asignar un rol activará automáticamente al usuario si está pendiente.</p>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-premium-600 rounded-lg hover:bg-premium-700 shadow-sm shadow-premium-500/30">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
