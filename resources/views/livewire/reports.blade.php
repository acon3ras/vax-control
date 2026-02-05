<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Reportes Mensuales</h1>
            <p class="text-sm text-slate-500 mt-1">Generación de informes de gestión en PDF</p>
        </div>
    </div>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-premium-100 rounded-2xl flex items-center justify-center text-premium-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                </div>

                <h2 class="text-center text-xl font-bold text-slate-900 mb-2">Resumen Mensual de Vacunas</h2>
                <p class="text-center text-slate-500 text-sm mb-8">Selecciona el periodo para descargar el informe detallado de movimientos y stock.</p>

                <form wire:submit.prevent="generate" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Mes</label>
                            <select wire:model="month" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Año</label>
                            <select wire:model="year" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all">
                                @for($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-premium-600 hover:bg-premium-700 text-white font-black rounded-xl shadow-lg shadow-premium-500/30 transition-all transform active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        DESCARGAR PDF
                    </button>
                    
                    <div wire:loading wire:target="generate" class="text-center text-xs font-bold text-premium-600 animate-pulse">
                        Generando documento, por favor espere...
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
