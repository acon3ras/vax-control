<div class="space-y-8" wire:poll.10s>
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Control de vacunas Hospital de Puerto Aysén 🏥</h1>
            <p class="text-sm text-slate-500 mt-1">Resumen Mensual 📅</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center bg-white rounded-xl shadow-sm border border-slate-200 p-1">
                <select wire:model.live="selectedMonth" class="bg-transparent border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer py-1 pl-3 pr-8">
                    @foreach($this->months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
                <div class="w-px h-4 bg-slate-200"></div>
                <select wire:model.live="selectedYear" class="bg-transparent border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer py-1 pl-3 pr-8">
                    @foreach($this->years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            
            @if(!$isCurrentMonth)
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-wider">
                    Modo Histórico
                </span>
            @endif
        </div>
    </div>

    <!-- KPI Grid -->
    @if(auth()->user()->hasRole('vacunador'))
        <!-- VISTA SIMPLIFICADA PARA VACUNADOR -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Administradas (Foco Principal) -->
            <div wire:click="openReport('ADMINISTRADAS_MES')" class="glass p-8 rounded-3xl border-2 border-emerald-100/50 relative overflow-hidden group cursor-pointer hover:border-emerald-400 transition-all active:scale-95 bg-gradient-to-br from-emerald-50/50 to-white shadow-lg shadow-emerald-100/20">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] mb-2">Vacunas Administradas</div>
                        <div class="text-5xl font-black text-emerald-600 tracking-tighter">{{ number_format($my_administradas) }}</div>
                        <div class="mt-2 inline-flex items-center text-[10px] font-bold text-white bg-emerald-500 px-3 py-1 rounded-full">
                            REGISTRADAS POR MÍ 💉
                        </div>
                    </div>
                    <div class="p-4 bg-white rounded-2xl text-emerald-500 shadow-sm border border-emerald-100">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>
            

        </div>
    @else
        <!-- VISTA COMPLETA (Admin/Supervisor/Encargado) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Administradas -->
            <div wire:click="openReport('ADMINISTRADAS_MES')" class="glass p-6 rounded-3xl border border-slate-200/50 relative overflow-hidden group cursor-pointer hover:border-emerald-200 transition-all active:scale-95">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Administradas Mes</div>
                        <div class="text-4xl font-extrabold text-emerald-600 tracking-tighter">{{ number_format($administradas_mes) }}</div>
                    </div>
                    <div class="p-2 bg-emerald-50 rounded-xl text-emerald-500 mb-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-[10px] font-bold text-emerald-500 bg-emerald-50 w-max px-2 py-0.5 rounded-lg border border-emerald-100">
                    REGISTRADO 💉
                </div>
            </div>

            <!-- Despachadas -->
            <div wire:click="openReport('DESPACHADAS_MES')" class="glass p-6 rounded-3xl border border-slate-200/50 relative overflow-hidden group cursor-pointer hover:border-blue-200 transition-all active:scale-95">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Despachadas Mes</div>
                        <div class="text-4xl font-extrabold text-blue-600 tracking-tighter">{{ number_format($despachadas_mes) }}</div>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-xl text-blue-500 mb-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-[10px] font-bold text-blue-500 bg-blue-50 w-max px-2 py-0.5 rounded-lg border border-blue-100">
                    DESPACHADO 🏥
                </div>
            </div>

            <!-- Perdida -->
            <div wire:click="openReport('PERDIDA_MES')" class="glass p-6 rounded-3xl border border-slate-200/50 relative overflow-hidden group cursor-pointer hover:border-rose-200 transition-all active:scale-95">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pérdida Mes</div>
                        <div class="text-4xl font-extrabold text-rose-600 tracking-tighter">{{ number_format($perdida_mes) }}</div>
                    </div>
                    <div class="p-2 bg-rose-50 rounded-xl text-rose-500 mb-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                </div>
                <div class="mt-2 text-[11px] font-bold text-rose-500">
                    <span class="bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">{{ number_format($porcentaje_perdida, 1) }}% TASA ⚠️</span>
                </div>
            </div>

            <!-- Cuarentena -->
            <div class="glass p-6 rounded-3xl border border-slate-200/50 relative overflow-hidden group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Cuarentena Mes</div>
                        <div class="text-4xl font-extrabold text-amber-600 tracking-tighter">{{ number_format($cuarentena_mes) }}</div>
                    </div>
                    <div class="p-2 bg-amber-50 rounded-xl text-amber-500 mb-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-[10px] font-bold text-amber-500 bg-amber-50 w-max px-2 py-0.5 rounded-lg border border-amber-100 uppercase">
                    Stock Bloqueado 🔒
                </div>
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    @if($showReportModal)
        <div class="fixed inset-0 z-[80] overflow-y-auto" aria-modal="true">
            <!-- Backdrop -->
            <div wire:click="$set('showReportModal', false)" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Panel Centering Wrapper -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-4xl bg-white rounded-[2rem] text-left shadow-2xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 leading-none">
                                Reporte Detallado
                            </h3>
                            <p class="text-sm font-medium text-slate-500 mt-1">
                                @if($reportType === 'ADMINISTRADAS_MES') <span class="text-emerald-600 font-bold">Vacunas Administradas</span>
                                @elseif($reportType === 'DESPACHADAS_MES') <span class="text-blue-600 font-bold">Despachos a Dependencias</span>
                                @elseif($reportType === 'PERDIDA_MES') <span class="text-rose-600 font-bold">Mermas y Pérdidas</span>
                                @endif
                            </p>
                        </div>
                        <button wire:click="$set('showReportModal', false)" class="text-slate-400 hover:text-slate-600 p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Filters & Content -->
                    <div class="p-8 overflow-y-auto flex-1">
                        
                        <!-- Filter Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Vaccine Filter -->
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Filtrar por Vacuna</label>
                                <select wire:model.live="reportVaccineId" class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-premium-500 text-sm">
                                    <option value="">Todas las Vacunas</option>
                                    @foreach($stocks as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Range Start -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Desde</label>
                                <input type="date" wire:model.live="reportStartDate" class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-premium-500 text-sm">
                            </div>

                            <!-- Date Range End -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Hasta</label>
                                <input type="date" wire:model.live="reportEndDate" class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-premium-500 text-sm">
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase">Vacuna</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase">Responsable</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase text-right">Cantidad</th>
                                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($this->reportData as $row)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-3 text-xs font-bold text-slate-600">{{ $row->movement->posted_at ? \Carbon\Carbon::parse($row->movement->posted_at)->format('d/m/Y H:i') : '-' }}</td>
                                            <td class="px-6 py-3 text-xs font-bold text-slate-900">{{ $row->vaccine->name }}</td>
                                            <td class="px-6 py-3 text-xs text-slate-500">{{ $row->movement->user->name ?? 'Sistema' }}</td>
                                            <td class="px-6 py-3 text-sm font-black text-slate-900 text-right">{{ $row->quantity }}</td>
                                            <td class="px-6 py-3 text-xs text-slate-500 italic">{{Str::limit($row->movement->notes, 30) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">No hay movimientos registrados en este periodo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end">
                         <button wire:click="exportPdf" wire:loading.attr="disabled" class="flex items-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="exportPdf" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Exportar PDF 📄
                            </span>
                            <span wire:loading wire:target="exportPdf" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Generando PDF...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Visual Kardex Section -->
    <div class="glass rounded-[2rem] border border-slate-200/50 overflow-hidden shadow-sm">
        <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Estado de Stock por Vacuna 📦</h2>
            
            <!-- Search Bar -->
            <div class="relative max-w-sm w-full group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg wire:loading.remove wire:target="search" class="w-5 h-5 text-slate-400 group-focus-within:text-premium-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-premium-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar por nombre o código... 🔍" 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all sm:text-sm"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Código 🔖</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nombre Comercial 💉</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Existencia (Dosis) 📊</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Cuarentena (Dosis) 🔒</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Nivel de Stock 📉</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Acciones ⚡</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($stocks as $stock)
                        @php
                            $isCritical = $stock->optimal_stock > 0 && $stock->min_stock >= 0 && $stock->total_quantity <= $stock->min_stock;
                        @endphp
                        <tr wire:key="stock-{{ $stock->id }}" class="transition-all group border-b border-slate-100 {{ $isCritical ? 'bg-rose-50/60 hover:bg-rose-50' : 'hover:bg-slate-50/80' }}">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-white text-slate-600 border border-slate-200">
                                    {{ $stock->code }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div 
                                    wire:click="$dispatch('openChartModal', { vaccineId: {{ $stock->id }}, month: {{ $selectedMonth }}, year: {{ $selectedYear }} })"
                                    class="text-sm font-bold {{ $stock->status === 'QUARANTINE' ? 'text-amber-700' : 'text-slate-900' }} cursor-pointer hover:text-premium-600 transition-colors"
                                >
                                    {{ $stock->name }}
                                    @if($stock->status === 'QUARANTINE')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 ml-2">
                                            EN CUARENTENA
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{ $stock->manufacturer ?? 'Fabricante N/A' }}</div>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-slate-900 tabular-nums">
                                {{ number_format($stock->total_quantity ?? 0) }}
                            </td>
                            <td class="px-8 py-5 text-right font-bold text-amber-600 tabular-nums">
                                {{ $stock->quarantine_stock > 0 ? number_format($stock->quarantine_stock) : '-' }}
                            </td>
                            <td class="px-8 py-5 w-48">
                                <div class="flex items-center space-x-3">
                                        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                        @php
                                            // -- Logic for Progress Bar --
                                            $target = $stock->optimal_stock > 0 ? $stock->optimal_stock : ($max_quantity > 0 ? $max_quantity : 100);
                                            $curr = $stock->total_quantity;
                                            
                                            // Percentage for Width (Capped at 100 visually)
                                            // Ensure we don't divide by zero
                                            $percentage = $target > 0 ? ($curr / $target) * 100 : 0;
                                            $visualWidth = min($percentage, 100);

                                            // -- Detailed Color Logic --
                                            // "Verde si se acerca a stock optimo... pasando por naranjo amarillo hasta rojo segun el stock critico"
                                            
                                            $colorClass = 'bg-emerald-500'; // Default Green (Healthy)

                                            if ($stock->optimal_stock > 0) {
                                                if ($stock->min_stock >= 0 && $curr <= $stock->min_stock) {
                                                    // CRITICAL (<= Min)
                                                    $colorClass = 'bg-rose-600 animate-pulse'; 
                                                } elseif ($curr >= $stock->optimal_stock) {
                                                    // OPTIMAL (>= Opt)
                                                    $colorClass = 'bg-emerald-500'; 
                                                } else {
                                                    // INTERMEDIATE (Min < Curr < Opt)
                                                    // Calculate where we are in the "safety zone"
                                                    $range = $stock->optimal_stock - $stock->min_stock;
                                                    $distanceFromMin = $curr - $stock->min_stock;
                                                    
                                                    // If range is valid
                                                    if ($range > 0) {
                                                        $healthFactor = $distanceFromMin / $range; // 0.0 to 1.0
                                                        
                                                        if ($healthFactor < 0.35) {
                                                            // Closer to Min -> Orange
                                                            $colorClass = 'bg-orange-500'; 
                                                        } else {
                                                            // Closer to Opt -> Amber/Yellow
                                                            $colorClass = 'bg-amber-400'; 
                                                        }
                                                    } else {
                                                        $colorClass = 'bg-amber-400';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="h-full {{ $colorClass }} transition-all duration-700 shadow-sm" style="width: {{ $visualWidth }}%"></div>
                                    </div>
                                    <!-- Percentage removed as per user request -->
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                        @php
                                            // --- Action Buttons Logic ---
                                            $isQuarantine = $stock->status === 'QUARANTINE';
                                            $hasNoStock = $stock->total_quantity <= 0;
                                            $historicalMode = !$isCurrentMonth;

                                            // Receipt Logic
                                            $receiptDisabled = $isQuarantine || $historicalMode;
                                            $receiptTitle = $historicalMode ? 'Solo lectura - Modo Histórico' : 
                                                           ($isQuarantine ? 'No disponible - En Cuarentena' : 'Ingresar Stock');
                                            $receiptClass = $receiptDisabled ? 'text-slate-300 cursor-not-allowed' : 'text-emerald-600 hover:bg-emerald-50 hover:border-emerald-100 active:scale-95';
                                            // We remove onclick if disabled to be safe, though 'disabled' attribute usually suffices
                                            $receiptOnclick = $receiptDisabled ? '' : "window.dispatchEvent(new CustomEvent('trigger-open-modal', {detail: { vaccineId: {$stock->id}, type: 'RECEIPT' }}))";


                                            // Consumption Logic (Admin, Dispatch, Waste)
                                            $consumeDisabled = $isQuarantine || $historicalMode || $hasNoStock;
                                            
                                            // Title Prefix
                                            $consumeTitleCommon = $historicalMode ? 'Solo lectura - Modo Histórico' : 
                                                                 ($isQuarantine ? 'No disponible - En Cuarentena' : 
                                                                 ($hasNoStock ? 'Sin Stock Disponible' : ''));

                                            $adminTitle = $consumeTitleCommon ?: 'Administrar (Vacunatorio)';
                                            $dispatchTitle = $consumeTitleCommon ?: 'Despachar a Dependencia';
                                            $wasteTitle = $consumeTitleCommon ?: 'Registrar Merma/Pérdida';

                                            $consumeClass = function($baseColor) use ($consumeDisabled) {
                                                if ($consumeDisabled) return 'text-slate-300 cursor-not-allowed';
                                                // Map base colors (blue, indigo, rose)
                                                return match($baseColor) {
                                                    'blue' => 'text-blue-600 hover:bg-blue-50 hover:border-blue-100 active:scale-95',
                                                    'indigo' => 'text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 active:scale-95',
                                                    'rose' => 'text-rose-600 hover:bg-rose-50 hover:border-rose-100 active:scale-95',
                                                    default => 'text-slate-600'
                                                };
                                            };

                                            $adminOnclick = $consumeDisabled ? '' : "window.dispatchEvent(new CustomEvent('trigger-open-modal', {detail: { vaccineId: {$stock->id}, type: 'ADMINISTRATION' }}))";
                                            $dispatchOnclick = $consumeDisabled ? '' : "window.dispatchEvent(new CustomEvent('trigger-open-modal', {detail: { vaccineId: {$stock->id}, type: 'DISPATCH' }}))";
                                            $wasteOnclick = $consumeDisabled ? '' : "window.dispatchEvent(new CustomEvent('trigger-open-modal', {detail: { vaccineId: {$stock->id}, type: 'WASTAGE' }}))";

                                        @endphp

                                        <div class="flex items-center justify-end space-x-2">
                                            @if(!auth()->user()->hasRole('vacunador'))
                                                <!-- Receipt (Hidden for Vacunador) -->
                                                <button 
                                                    type="button"
                                                    @disabled($receiptDisabled)
                                                    onclick="{{ $receiptOnclick }}"
                                                    class="p-2 {{ $receiptClass }} rounded-lg transition-all border border-transparent"
                                                    title="{{ $receiptTitle }}"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            @endif
                                            
                                            <!-- Administrar -->
                                            <button 
                                                type="button"
                                                @disabled($consumeDisabled)
                                                onclick="{{ $adminOnclick }}"
                                                class="p-2 {{ $consumeClass('blue') }} rounded-lg transition-all border border-transparent"
                                                title="{{ $adminTitle }}"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            </button>

                                            @if(!auth()->user()->hasRole('vacunador'))
                                                <!-- Despachar (Hidden for Vacunador) -->
                                                <button 
                                                    type="button"
                                                    @disabled($consumeDisabled)
                                                    onclick="{{ $dispatchOnclick }}"
                                                    class="p-2 {{ $consumeClass('indigo') }} rounded-lg transition-all border border-transparent"
                                                    title="{{ $dispatchTitle }}"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                </button>
                                            @endif

                                            <!-- Pérdida/Merma -->
                                            <button 
                                                type="button"
                                                @disabled($consumeDisabled)
                                                onclick="{{ $wasteOnclick }}"
                                                class="p-2 {{ $consumeClass('rose') }} rounded-lg transition-all border border-transparent"
                                                title="{{ $wasteTitle }}"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </button>
                                        </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                No se encontraron vacunas que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <livewire:vaccine-chart-modal />

    <!-- Minimalist Manual Hint Popup -->
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = true, 1000)" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 max-w-sm w-full bg-white border border-slate-200 shadow-xl rounded-2xl p-4 flex items-start gap-4">
        
        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600 shrink-0">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        
        <div class="flex-1">
            <h4 class="font-bold text-slate-800 text-sm">¿Necesitas ayuda?</h4>
            <p class="text-xs text-slate-500 mt-1">Contacta al administrador para soporte.</p>
        </div>

        <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
