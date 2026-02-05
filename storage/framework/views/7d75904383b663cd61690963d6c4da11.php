<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Control de Vacunas'); ?> - Sistema de Gestión</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN (For immediate premium look) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        premium: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        vax: {
                            gold: '#C5A059',
                            dark: '#0F172A',
                            accent: '#38BDF8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Glassmorphism effects */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .dark-glass {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    </style>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 dark-glass text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center space-x-3 px-6 h-20 border-b border-white/5">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-12 h-12 object-contain hover:scale-105 transition-transform">
                <span class="text-lg font-bold tracking-tight text-white">Control de <span class="text-premium-400">Vacunas</span></span>
            </div>

            <nav class="mt-6 px-4 space-y-1 overflow-y-auto h-[calc(100vh-5rem)]">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('dashboard') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <div class="pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Inventario</div>
                


                <a href="<?php echo e(route('activity')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('activity') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Historial
                </a>



                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->user()->hasRole('vacunador')): ?>
                <div class="pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Administración</div>
                
                <a href="<?php echo e(route('vaccines.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('vaccines.index') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    Catálogo de Vacunas
                </a>

                <a href="<?php echo e(route('locations.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('locations.index') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Dependencias
                </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Configuración -->
                <!-- Configuración -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('encargado')): ?>
                    <div class="pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Sistema</div>
                    
                    <a href="<?php echo e(route('users.index')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('users.index') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Usuarios
                    </a>

                    <?php if(auth()->user()->hasRole('admin')): ?>
                    <a href="<?php echo e(route('audit-logs')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('audit-logs') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Bitácora (Auditoría)
                    </a>

                    <a href="<?php echo e(route('system.settings')); ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo e(request()->routeIs('system.settings') ? 'bg-premium-500/10 text-premium-400 border border-premium-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all'); ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Configuración
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


        </aside>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar -->
            <header class="h-16 glass flex items-center justify-between px-8 z-40 border-b border-slate-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div class="relative ml-auto" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 hover:bg-slate-100 rounded-xl p-2 transition-colors focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-semibold text-slate-900"><?php echo e(auth()->user()->name); ?></div>
                            <div class="text-[10px] text-slate-500"><?php echo e(auth()->user()->roles->first()->label ?? ucfirst(auth()->user()->roles->first()->name ?? 'Sin Rol')); ?></div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center overflow-hidden shadow-sm ring-2 ring-transparent group-hover:ring-premium-500/20 transition-all">
                            <span class="text-slate-400 text-sm font-bold"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50 origin-top-right divide-y divide-slate-100"
                         style="display: none;">
                        
                        <div class="px-4 py-3">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Mi Cuenta</p>
                            <p class="text-sm font-medium text-slate-900 truncate"><?php echo e(auth()->user()->email); ?></p>
                        </div>

                        <div class="py-1">
                            <a href="<?php echo e(route('profile')); ?>" class="group flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Perfil
                            </a>
                            <a href="<?php echo e(route('access-logs')); ?>" class="group flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-purple-600">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Historial de Accesos
                            </a>
                            <a href="<?php echo e(route('changelog')); ?>" class="group flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                Novedades (Changelog)
                            </a>

                        </div>

                        <div class="py-1">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-8">
                <div class="max-w-7xl mx-auto">
                    <?php echo e($slot); ?>

                </div>
            </main>
            <!-- Global Modals -->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('inventory-adjuster', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3011160097-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </div>

    <script src="<?php echo e(asset('vendor/livewire/livewire.js')); ?>" data-csrf="<?php echo e(csrf_token()); ?>" data-update-uri="<?php echo e(asset('/livewire/update')); ?>" data-navigate-once="true"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Livewire !== 'undefined') {
                Livewire.start();
                console.log('Livewire started manually via asset bundle');
            } else {
                console.error('Livewire failed to load from vendor asset');
            }
        });


        window.addEventListener('trigger-open-modal', event => {
            console.log('BRIDGE: Event intercepted', event.detail);
            
            if (typeof Livewire === 'undefined') {
                console.error('Livewire is not loaded, cannot dispatch event');
                alert('Error: Livewire no cargó correctamente. Revisa la consola.');
                return;
            }

            Livewire.dispatch('openAdjustmentModal', { 
                vaccineId: event.detail.vaccineId, 
                type: event.detail.type 
            });
        });
    </script>
    <!-- TOAST NOTIFICATION SYSTEM -->
    <div 
        x-data="{ 
            notifications: [],
            add(message, type = 'info') {
                const id = Date.now();
                this.notifications.push({ id, message, type });
                setTimeout(() => this.remove(id), 5000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }
        }"
        x-on:toast.window="add($event.detail.message, $event.detail.type)"
        class="fixed top-4 right-4 z-[9999] flex flex-col space-y-3 pointer-events-none"
    >
        <template x-for="note in notifications" :key="note.id">
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-8"
                class="pointer-events-auto w-80 bg-white shadow-2xl rounded-xl border-l-4 p-4 flex items-start transform backdrop-blur-sm bg-white/95"
                :class="{
                    'border-emerald-500': note.type === 'success',
                    'border-blue-500': note.type === 'info',
                    'border-amber-500': note.type === 'warning',
                    'border-rose-500': note.type === 'error'
                }"
            >
                <!-- Icons -->
                <div class="flex-shrink-0">
                    <template x-if="note.type === 'success'">
                        <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                    <template x-if="note.type === 'error'">
                        <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                     <template x-if="note.type === 'warning'">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                     <template x-if="note.type === 'info'">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>
                <!-- Content -->
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-slate-800" x-text="note.type === 'error' ? 'Error' : (note.type === 'success' ? 'Éxito' : (note.type === 'warning' ? 'Atención' : 'Información'))"></p>
                    <p class="text-sm font-medium text-slate-600 mt-0.5 leading-snug" x-text="note.message"></p>
                </div>
                <!-- Close -->
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="remove(note.id)" class="bg-transparent rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none transition-colors">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

</body>
</html>
<?php /**PATH D:\laragon\www\vax-control\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>