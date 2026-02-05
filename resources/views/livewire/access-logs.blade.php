<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Historial de Accesos</h1>
            <p class="text-sm text-slate-500 mt-1">Registro de actividad de inicio de sesión de tu cuenta</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fecha y Hora</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dirección IP</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dispositivo / Navegador</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-slate-900">{{ $log->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $log->ip_address }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs text-slate-500 max-w-xs truncate" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center text-xs font-bold text-emerald-600">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Exitoso
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">No hay registros de acceso recientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($logs->hasPages())
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
