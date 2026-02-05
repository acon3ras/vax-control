<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Historial de Actividad</h1>
            <p class="text-sm text-slate-500 mt-1">Registro completo de movimientos y acciones de usuarios.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative max-w-sm w-full group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg wire:loading.remove wire:target="search" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-premium-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar por vacuna o tipo..." 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-premium-500 focus:border-transparent transition-all sm:text-sm"
                >
            </div>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="glass rounded-[2rem] border border-slate-200/50 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fecha/Hora</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">IP Origen</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Acción</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detalle</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Foto</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Notas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900"><?php echo e($movement->created_at->format('d/m/Y')); ?></div>
                                <div class="text-xs text-slate-400"><?php echo e($movement->created_at->format('H:i')); ?> hrs</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    <?php echo e($movement->user->name ?? 'Sistema'); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-mono text-slate-400">
                                    <?php echo e($movement->ip_address ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <?php
                                    $typeClasses = match($movement->type) {
                                        'RECEIPT' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'ADMINISTRATION' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'DISPATCH' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'WASTAGE' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'INVENTORY_ADJUSTMENT' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                                    };
                                    $typeLabel = match($movement->type) {
                                        'RECEIPT' => 'INGRESO STOCK',
                                        'ADMINISTRATION' => 'ADMINISTRACIÓN',
                                        'DISPATCH' => 'DESPACHO',
                                        'WASTAGE' => 'MERMA / PÉRDIDA',
                                        'INVENTORY_ADJUSTMENT' => 'AJUSTE',
                                        default => $movement->type
                                    };
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border uppercase <?php echo e($typeClasses); ?>">
                                    <?php echo e($typeLabel); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $movement->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-1">
                                        <div class="text-sm font-bold text-slate-900"><?php echo e($item->vaccine->name); ?></div>
                                        <?php
                                            $isNegative = in_array($movement->type, ['DISPATCH', 'ADMINISTRATION', 'EXPIRY', 'WASTAGE', 'BREAKAGE', 'LOSS', 'QUARANTINE_MOVE']);
                                            $sign = $isNegative ? '-' : '+';
                                            $quantityColor = $isNegative ? 'text-rose-600' : 'text-emerald-600';
                                        ?>
                                        <div class="text-xs <?php echo e($quantityColor); ?> font-bold"><?php echo e($sign); ?><?php echo e($item->quantity); ?> dosis</div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($movement->evidence_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $movement->evidence_path)); ?>" target="_blank" class="inline-flex items-center p-2 rounded-xl bg-premium-50 text-premium-600 hover:bg-premium-100 transition-all shadow-sm" title="Ver archivo adjunto (Evidencia)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-300" title="Sin adjunto">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500 whitespace-normal break-words">
                                <?php echo e($movement->notes ?: '-'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($movement->reason): ?>
                                    <span class="block text-xs text-rose-500 font-bold mt-1">Causa: <?php echo e($movement->reason); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($movement->destinationLocation): ?>
                                    <span class="block text-xs text-indigo-500 font-bold mt-1">Destino: <?php echo e($movement->destinationLocation->name); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                No se encontraron movimientos registrados.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-slate-100">
            <?php echo e($movements->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\laragon\www\vax-control\resources\views/livewire/movement-history.blade.php ENDPATH**/ ?>