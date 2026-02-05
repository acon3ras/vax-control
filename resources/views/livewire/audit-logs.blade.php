<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Bitácora de Auditoría 🛡️</h1>
            <p class="text-sm text-slate-500 mt-1">Registro detallado de acciones y seguridad del sistema</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por usuario o evento..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-premium-500 text-sm">
        </div>
        
        <select wire:model.live="userFilter" class="w-full sm:w-48 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-premium-500">
            <option value="">Todos los Usuarios</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="eventFilter" class="w-full sm:w-48 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-premium-500">
            <option value="">Todos los Eventos</option>
            @foreach($events as $evt)
                <option value="{{ $evt }}">{{ $evt }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Actor (Quién)</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Evento (Qué)</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Detalles (Cómo)</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">IP / Agente</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-700 block">{{ $log->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs text-slate-400 font-mono">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 mr-3">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $log->user->name ?? 'Sistema / Desconocido' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClass = match($log->event) {
                                        'LOGIN', 'LOGOUT' => 'bg-indigo-50 text-indigo-700 border border-indigo-100',
                                        'ROLE_ASSIGNED', 'ROLE_UPDATED', 'ROLE_REMOVED', 'ROLES_DETACHED' => 'bg-purple-50 text-purple-700 border border-purple-100',
                                        'STATUS_CHANGE' => 'bg-rose-50 text-rose-700 border border-rose-100', 
                                        'update' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                        'create', 'insert' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                        default => 'bg-slate-100 text-slate-600 border border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $badgeClass }}">
                                    {{ str_replace('🔐 ', '', str_replace('📝 ', '', str_replace('✏️ ', '', $this->translateEvent($log->event, $log)))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 max-w-xs break-words">
                                    <div class="font-bold text-slate-800 mb-1">
                                        {{ $this->translateEvent($log->event, $log) }}
                                    </div>
                                    
                                    <div class="space-y-1">
                                        @if(!empty($log->old_values))
                                            <div class="text-[11px] text-rose-600 bg-rose-50/50 px-2 py-1 rounded border border-rose-100">
                                                <span class="font-black uppercase text-[9px]">Anterior:</span>
                                                <div class="mt-0.5">
                                                    @foreach($log->old_values as $k => $v)
                                                        @if(!in_array($k, ['updated_at', 'posted_at', 'posted_by']))
                                                            <div class="flex justify-between">
                                                                <span class="opacity-70">{{ $k }}:</span>
                                                                <span class="font-bold">{{ $this->translateValue($k, $v) }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if(!empty($log->new_values))
                                            <div class="text-[11px] text-emerald-600 bg-emerald-50/50 px-2 py-1 rounded border border-emerald-100">
                                                <span class="font-black uppercase text-[9px]">Actual:</span>
                                                <div class="mt-0.5">
                                                    @foreach($log->new_values as $k => $v)
                                                        @if(!in_array($k, ['updated_at', 'posted_at', 'posted_by']))
                                                            <div class="flex justify-between">
                                                                <span class="opacity-70">{{ $k }}:</span>
                                                                <span class="font-bold">{{ $this->translateValue($k, $v) }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-500 font-mono">{{ $log->ip_address ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[150px]" title="{{ $log->user_agent }}">{{ $log->user_agent ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-slate-500 font-medium">No se encontraron registros de auditoría.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $logs->links() }}
        </div>
    </div>
</div>
