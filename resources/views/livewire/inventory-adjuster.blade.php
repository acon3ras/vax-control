<div 
    x-data="{ show: @entangle('showModal').live }"
    x-show="show"
    x-on:close-modal.window="show = false"
    x-cloak
    class="fixed inset-0 z-[70] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background Overlay -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            wire:click="$set('showModal', false)" 
            class="fixed inset-0 transition-opacity bg-slate-900/60 cursor-pointer" 
            aria-hidden="true"
        ></div>

        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-[1.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-4 sm:align-middle sm:max-w-md sm:w-full"
        >
            <div class="px-5 py-5">
                    <!-- Title -->
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Ajustar Inventario</h3>
                            <p class="text-xs text-slate-500 mt-1">Registrar movimiento manual de stock</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-rose-50 border-2 border-rose-200 rounded-2xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-bold text-rose-700">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="process" class="space-y-4">
                        <!-- Movement Type -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Tipo de Operación</label>
                            
                            @if($locked)
                                <div class="px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl flex items-center font-bold text-slate-700">
                                    @if($type === 'RECEIPT') <span class="text-emerald-500 mr-2">●</span> INGRESO DE STOCK
                                    @elseif($type === 'ADMINISTRATION') <span class="text-blue-500 mr-2">●</span> APLICACIÓN / VACUNATORIO
                                    @elseif($type === 'DISPATCH') <span class="text-indigo-500 mr-2">●</span> DESPACHO EXTERNO
                                    @elseif($type === 'WASTAGE') <span class="text-rose-500 mr-2">●</span> MERMA / PÉRDIDA
                                    @elseif($type === 'QUARANTINE_MOVE') <span class="text-amber-500 mr-2">●</span> MOVER A CUARENTENA
                                    @elseif($type === 'QUARANTINE_RELEASE') <span class="text-emerald-500 mr-2">●</span> LIBERAR DE CUARENTENA
                                    @endif
                                </div>
                            @else
                                    @php
                                        // Calculate locally for UI state
                                        $hasStock = $this->availableStock > 0;
                                    @endphp

                                    <button 
                                        type="button" 
                                        wire:click="$set('type', 'RECEIPT')"
                                        class="flex items-center justify-center px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm {{ $type == 'RECEIPT' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-400 hover:border-slate-200' }}"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" /></svg>
                                        INGRESO
                                    </button>
                                    
                                    <button 
                                        type="button" 
                                        wire:click="$set('type', 'ADMINISTRATION')"
                                        @if(!$hasStock) disabled @endif
                                        class="flex items-center justify-center px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm {{ $type == 'ADMINISTRATION' ? 'bg-blue-50 border-blue-500 text-blue-700 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-400 hover:border-slate-200' }} {{ !$hasStock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        APLICACIÓN
                                    </button>
                                    
                                    <button 
                                        type="button" 
                                        wire:click="$set('type', 'WASTAGE')"
                                        @if(!$hasStock) disabled @endif
                                        class="flex items-center justify-center px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm {{ $type == 'WASTAGE' ? 'bg-rose-50 border-rose-500 text-rose-700 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-400 hover:border-slate-200' }} {{ !$hasStock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        MERMA/PÉRDIDA
                                    </button>
                                    
                                    <button 
                                        type="button" 
                                        wire:click="$set('type', 'DISPATCH')"
                                        @if(!$hasStock) disabled @endif
                                        class="flex items-center justify-center px-4 py-3 rounded-2xl border-2 transition-all font-bold text-sm {{ $type == 'DISPATCH' ? 'bg-amber-50 border-amber-500 text-amber-700 shadow-sm' : 'bg-slate-50 border-slate-100 text-slate-400 hover:border-slate-200' }} {{ !$hasStock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-11V3"></path></svg>
                                        DESPACHO
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Vaccine Selection -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Vacuna Seleccionada</label>
                            
                            @if($locked && $vaccine_id)
                                <div class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-700 flex justify-between items-center">
                                    <span>{{ $vaccines->find($vaccine_id)->name ?? 'Vacuna Desconocida' }}</span>
                                    <span class="text-xs font-mono bg-slate-200 text-slate-500 px-2 py-1 rounded-md">{{ $vaccines->find($vaccine_id)->code ?? '' }}</span>
                                </div>
                                <!-- Hidden input to maintain state -->
                            @else
                                <select wire:model="vaccine_id" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-bold text-slate-700">
                                    <option value="">Seleccione una vacuna...</option>
                                    @foreach($vaccines as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->code }})</option>
                                    @endforeach
                                </select>
                                @error('vaccine_id') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Cantidad (Dosis)</label>
                                <input wire:model="quantity" type="number" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-black text-xl text-slate-900">
                                @error('quantity') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                <!-- Stock Display -->
                            <div class="mb-4 bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                                @if($vaccine_id)
                                    @if(in_array($type, ['QUARANTINE_MOVE', 'QUARANTINE_RELEASE']))
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Available Stock -->
                                            <div class="text-center p-2 rounded-lg {{ $type === 'QUARANTINE_MOVE' ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50' }}">
                                                <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Disponible</span>
                                                <span class="block text-xl font-black {{ $type === 'QUARANTINE_MOVE' ? 'text-emerald-600' : 'text-slate-600' }}">
                                                    {{ $this->availableStock }}
                                                </span>
                                            </div>
                                            <!-- Quarantine Stock -->
                                            <div class="text-center p-2 rounded-lg {{ $type === 'QUARANTINE_RELEASE' ? 'bg-amber-50 border border-amber-100' : 'bg-slate-50' }}">
                                                <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">En Cuarentena</span>
                                                <span class="block text-xl font-black {{ $type === 'QUARANTINE_RELEASE' ? 'text-amber-600' : 'text-slate-600' }}">
                                                    {{ $this->quarantineStock }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Normal Single Stock Display -->
                                        <div class="flex items-center justify-between text-xs font-bold px-1">
                                            <span class="text-slate-400 uppercase tracking-wider">STOCK ACTUAL:</span>
                                            <span class="text-emerald-600 text-lg">
                                                {{ $this->stock }} Dosis
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            </div>

                            <!-- Optional Batch -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Lote (Opcional)</label>
                                <input wire:model="batch_number" type="text" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-bold text-slate-600">
                            </div>
                        </div>

                        <!-- Specific Fields based on Type -->
                        @if ($type === 'DISPATCH')
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Destino / Dependencia</label>
                                <select wire:model="destination_id" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-bold text-slate-700">
                                    <option value="">Seleccione el destino...</option>
                                    @foreach($externalLocations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                @error('destination_id') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if ($type === 'WASTAGE')
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Motivo de la Pérdida</label>
                                <select wire:model="reason" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-bold text-slate-700">
                                    <option value="">Seleccione el motivo...</option>
                                    <option value="Vencimiento">Vencimiento</option>
                                    <option value="Rotura">Rotura / Daño físico</option>
                                    <option value="Pérdida de Cadena de Frío">Pérdida de Cadena de Frío</option>
                                    <option value="Robo/Hurto">Robo o Hurto</option>
                                    <option value="Error de Preparación">Error de Preparación</option>
                                </select>
                                @error('reason') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if ($type === 'QUARANTINE_MOVE')
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Motivo de la Cuarentena <span class="text-rose-500">*</span></label>
                                <select wire:model.live="reason" class="block w-full px-4 py-3 bg-slate-50 border-2 {{ $errors->has('reason') ? 'border-rose-500' : 'border-slate-100' }} rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-bold text-slate-700">
                                    <option value="">Seleccione el motivo...</option>
                                    <option value="Pérdida de Cadena de Frío">Pérdida de Cadena de Frío</option>
                                    <option value="Alerta Sanitaria">Alerta Sanitaria</option>
                                    <option value="Rotura de Empaque">Rotura de Empaque</option>
                                    <option value="Sospecha de Contaminación">Sospecha de Contaminación</option>
                                    <option value="Revisión de Calidad">Revisión de Calidad</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                @error('reason') 
                                    <span class="text-xs text-rose-500 font-bold mt-1 block flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif

                        <!-- Notes -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Notas / Referencia</label>
                            <textarea wire:model="notes" rows="2" class="block w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-premium-500/10 focus:border-premium-500 transition-all font-medium text-slate-600 text-sm" placeholder="{{ $this->notesPlaceholder }}"></textarea>
                        </div>

                        <!-- Evidence Upload -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Evidencia (Opcional)</label>
                            <div class="flex items-center space-x-4">
                                <label class="cursor-pointer flex items-center justify-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl hover:bg-slate-100 transition-all font-bold text-sm text-slate-500 w-full hover:border-slate-400">
                                    <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span wire:loading.remove wire:target="evidence">Adjuntar Foto / Doc</span>
                                    <span wire:loading wire:target="evidence">Subiendo...</span>
                                    <input type="file" wire:model="evidence" class="hidden" accept="image/*,application/pdf">
                                </label>
                            </div>
                            @if ($evidence)
                                <div class="mt-2 text-xs font-bold text-emerald-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Archivo seleccionado: {{ $evidence->getClientOriginalName() }}
                                </div>
                            @endif
                            @error('evidence') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 flex items-center space-x-4">
                            <button type="button" wire:click="$set('showModal', false)" wire:loading.attr="disabled" class="flex-1 px-6 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors disabled:opacity-50">Cancelar</button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="process" class="flex-[2] px-6 py-4 bg-premium-600 hover:bg-premium-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-premium-600/30 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                <span wire:loading.remove wire:target="process">REGISTRAR MOVIMIENTO</span>
                                <span wire:loading wire:target="process" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
