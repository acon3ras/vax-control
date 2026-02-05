<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
    <!-- Sidebar Navigation -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-0">
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <h2 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Manual de Usuario
                </h2>
            </div>
            <nav class="p-2 space-y-1">
                <button wire:click="setTab('home')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'home' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Inicio / Bienvenida
                </button>
                
                <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest mt-4">Inventario</div>
                
                <button wire:click="setTab('stock')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'stock' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Gestión de Stock
                </button>

                <button wire:click="setTab('history')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'history' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Historial de Actividad
                </button>

                <button wire:click="setTab('reports')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'reports' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Reportes Mensuales
                </button>

                <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest mt-4">Administración</div>

                <button wire:click="setTab('catalog')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'catalog' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Catálogo de Vacunas
                </button>

                <button wire:click="setTab('dependencies')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'dependencies' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Gestión de Dependencias
                </button>

                <button wire:click="setTab('users')" 
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-3 {{ $activeTab === 'users' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Gestión de Usuarios
                </button>


            </nav>
        </div>
    </div>

    <!-- Content Area -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-8 overflow-y-auto">
        
        <!-- Tab: HOME -->
        @if($activeTab === 'home')
            <div class="max-w-3xl mx-auto space-y-6 animate-fade-in-down">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-slate-900 mb-4">Centro de Ayuda Vax Control</h1>
                    <p class="text-lg text-slate-600">Bienvenido al manual interactivo del sistema. Aquí encontrarás guías paso a paso para realizar las tareas más comunes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card Stock -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('stock')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Gestión de Stock desde el Dashboard</h3>
                        <p class="text-sm text-slate-600">Aprende a ingresar, administrar, despachar y mermar vacunas.</p>
                    </div>

                    <!-- Card History -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('history')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Historial de Actividad</h3>
                        <p class="text-sm text-slate-600">Revisa el registro completo de movimientos, usuarios y causas.</p>
                    </div>

                    <!-- Card Reports -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('reports')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Reportes Mensuales</h3>
                        <p class="text-sm text-slate-600">Descarga informes PDF con el resumen de gestión.</p>
                    </div>

                    <!-- Card Catalog -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('catalog')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Catálogo de Vacunas</h3>
                        <p class="text-sm text-slate-600">Administra el listado de vacunas, crea nuevas y gestiona su estado.</p>
                    </div>

                    <!-- Card Dependencies -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('dependencies')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Gestión de Dependencias</h3>
                        <p class="text-sm text-slate-600">Crea y administra las unidades internas (Vacunatorio, Farmacia, etc).</p>
                    </div>

                    <!-- Card Users -->
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-100 cursor-pointer hover:shadow-md transition-shadow" wire:click="setTab('users')">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Gestión de Usuarios</h3>
                        <p class="text-sm text-slate-600">Administra accesos, activa cuentas y asigna roles al personal.</p>
                    </div>


                </div>
            </div>
        @endif

        <!-- Tab: STOCK -->
        @if($activeTab === 'stock')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Gestión de Stock</h1>
                        <p class="text-slate-500">¿Cómo ingresar, administrar, despachar o mermar vacunas?</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <section class="space-y-4">
                        <p class="text-slate-600">
                            En el Dashboard, cada vacuna tiene 4 botones de acción a la derecha. Identifica cuál necesitas usar según la tarea que vas a realizar:
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/botones_accion.jpg') }}" alt="Botones de acción" class="w-full h-auto">
                        </div>
                    </section>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-slate-900 bg-white px-2 py-1 rounded border border-slate-200 font-bold text-lg">+</span>
                                <h3 class="font-bold text-slate-800">1. Ingresar Stock</h3>
                            </div>
                            <p class="text-sm text-slate-600 mb-2"><strong>¿Cuándo usarlo?</strong> Cuando recibes nuevas cajas o dosis desde la central o proveedores.</p>
                            <p class="text-xs text-slate-500">Este botón <span class="text-emerald-600 font-bold">AUMENTA</span> el inventario disponible.</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-slate-900 bg-white px-2 py-1 rounded border border-slate-200 font-bold text-lg">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                </span>
                                <h3 class="font-bold text-slate-800">2. Administrar</h3>
                            </div>
                            <p class="text-sm text-slate-600 mb-2"><strong>¿Cuándo usarlo?</strong> Cada vez que se vacuna a un paciente.</p>
                            <p class="text-xs text-slate-500">Este botón <span class="text-amber-600 font-bold">DISMINUYE</span> el inventario (Consumo).</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-slate-900 bg-white px-2 py-1 rounded border border-slate-200 font-bold text-lg">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                </span>
                                <h3 class="font-bold text-slate-800">3. Despachar / Traslado</h3>
                            </div>
                            <p class="text-sm text-slate-600 mb-2"><strong>¿Cuándo usarlo?</strong> Al enviar vacunas a otra dependencia interna (ej. de Farmacia a Vacunatorio).</p>
                            <p class="text-xs text-slate-500">Este botón mueve stock entre bodegas. No cambia el total del hospital, solo su ubicación.</p>
                        </div>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-slate-900 bg-white px-2 py-1 rounded border border-slate-200 font-bold text-lg">
                                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </span>
                                <h3 class="font-bold text-slate-800">4. Merma / Pérdida</h3>
                            </div>
                            <p class="text-sm text-slate-600 mb-2"><strong>¿Cuándo usarlo?</strong> Si una vacuna se rompe, pierde cadena de frío o vence.</p>
                            <p class="text-xs text-slate-500">Este botón <span class="text-red-600 font-bold">ELIMINA</span> stock del inventario y requiere justificación.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab: HISTORY -->
        @if($activeTab === 'history')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Historial de Actividad</h1>
                        <p class="text-slate-500">Todo movimiento queda registrado para seguridad y trazabilidad.</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <section class="space-y-4">
                        <p class="text-slate-600">
                            El sistema almacena automáticamente <strong>quién, cuándo, dónde y por qué</strong> se realizó cada cambio en el inventario. Nadie puede borrar estos registros.
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/historial_actividad.jpg') }}" alt="Pantalla de Historial de Actividad" class="w-full h-auto">
                        </div>

                        <div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-100">
                            <h4 class="font-bold text-slate-800 mb-2">Columnas Importantes:</h4>
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-600">
                                <li><strong>Usuario:</strong> Nombre de la persona que realizó la acción.</li>
                                <li><strong>Acción:</strong> Tipo de movimiento (Ingreso, Administración, Despacho, Merma).</li>
                                <li><strong>Detalle:</strong> Cantidad de dosis afectadas (positivas o negativas).</li>
                                <li><strong>Notas:</strong> Justificación obligatoria en casos de Mermas o Despachos.</li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        @endif

        <!-- Tab: REPORTS -->
        @if($activeTab === 'reports')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Reportes Mensuales</h1>
                        <p class="text-slate-500">Generación de informes de gestión en PDF</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <section class="space-y-4">
                        <p class="text-slate-600">
                            La sección de reportes permite descargar un documento oficial con el <strong>resumen de movimientos y stock actual</strong>. Este documento es útil para rendiciones de cuentas y auditorías.
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/reportes_pdf.jpg') }}" alt="Pantalla de Reportes" class="w-full h-auto">
                        </div>

                        <div class="mt-6 space-y-4">
                            <h4 class="font-bold text-slate-800">Pasos para descargar:</h4>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600 ml-2">
                                <li>Dirígete a la sección <strong>Reportes y Métricas</strong> en el menú principal.</li>
                                <li>Selecciona el <strong>Mes</strong> y <strong>Año</strong> que deseas consultar.</li>
                                <li>Presiona el botón verde <strong>DESCARGAR PDF</strong>.</li>
                            </ol>
                            <p class="text-xs text-slate-500 italic mt-2 ml-2">El archivo se descargará automáticamente a tu dispositivo.</p>
                        </div>
                    </section>
                </div>
            </div>
        @endif

        <!-- Tab: CATALOG -->
        @if($activeTab === 'catalog')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Catálogo de Vacunas</h1>
                        <p class="text-slate-500">Gestión maestra de los productos disponibles en el hospital</p>
                    </div>
                </div>

                <div class="space-y-12">
                    <!-- Section 1: List & Actions -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">1. Listado General y Acciones</h3>
                        <p class="text-slate-600">
                            En esta pantalla verás todas las vacunas registradas. Puedes ver rápidamente:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-600 ml-4 mb-4">
                            <li><strong>Código y Nombre:</strong> Identificación del producto.</li>
                            <li><strong>Estado:</strong> Switch verde (Activa) o gris (Inactiva/Oculta).</li>
                            <li><strong>Acciones:</strong> Botones para Editar (Lápiz) o poner en Cuarentena (Triángulo Alerta).</li>
                        </ul>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/gestion_vacunas.jpg') }}" alt="Listado de Vacunas" class="w-full h-auto">
                        </div>
                        
                        <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100 flex gap-3 text-emerald-800 text-sm mt-4">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p><strong>Tip:</strong> Si desactivas una vacuna (switch gris), esta dejará de aparecer en el Dashboard principal, pero no se borrará su historial.</p>
                        </div>
                    </section>

                    <!-- Section 2: New Vaccine -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">2. Crear Nueva Vacuna</h3>
                        <p class="text-slate-600">
                            Presiona el botón verde <strong>+ Nueva Vacuna</strong> arriba a la derecha para abrir el formulario de registro.
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/nueva_vacuna.jpg') }}" alt="Formulario Nueva Vacuna" class="w-full h-auto">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="bg-slate-50 p-4 rounded-lg">
                                <h4 class="font-bold text-slate-800 text-sm mb-1">Campos Clave:</h4>
                                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1">
                                    <li><strong>Código Interno:</strong> Debe ser único (ej. PFIZER, HAV).</li>
                                    <li><strong>Stock Mínimo:</strong> El sistema alertará si bajas de esta cantidad.</li>
                                    <li><strong>Stock Óptimo:</strong> Meta ideal de stock para esta vacuna.</li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        @endif

        <!-- Tab: DEPENDENCIES -->
        @if($activeTab === 'dependencies')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Gestión de Dependencias</h1>
                        <p class="text-slate-500">Administración de unidades o servicios internos del hospital</p>
                    </div>
                </div>

                <div class="space-y-12">
                    
                    <!-- Section 1: List & Overview -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">1. Listado de Unidades</h3>
                        <p class="text-slate-600">
                            Las <strong>Dependencias</strong> representan las ubicaciones físicas o lógicas donde se almacenan o administran vacunas (ej. Farmacia Central, Vacunatorio, Urgencias).
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/gestion_dependencias.jpg') }}" alt="Listado de Dependencias" class="w-full h-auto">
                        </div>
                        
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-100 mt-4">
                            <ul class="list-disc list-inside space-y-2 text-sm text-slate-600">
                                <li><strong>Nombre:</strong> Identificador de la unidad.</li>
                                <li><strong>Tipo:</strong> Generalmente "DEPENDENCY" para unidades internas.</li>
                                <li><strong>Estado:</strong> Las dependencias inactivas no pueden recibir ni despachar stock.</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Section 2: Create New -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">2. Crear Nueva Dependencia</h3>
                        <p class="text-slate-600">
                            Para registrar una nueva unidad, haz clic en el botón verde <strong>+ Nueva Dependencia</strong>.
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/nueva_dependencia.jpg') }}" alt="Crear Dependencia" class="w-full h-auto">
                        </div>

                        <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-100 flex gap-3 text-yellow-800 text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p><strong>Atención:</strong> Asegúrate de que el nombre sea claro y único para evitar confusiones al momento de realizar despachos internos.</p>
                        </div>
                    </section>

                </div>
            </div>
        @endif

        <!-- Tab: USERS -->
        @if($activeTab === 'users')
            <div class="max-w-4xl mx-auto animate-fade-in-down">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Gestión de Usuarios</h1>
                        <p class="text-slate-500">Control de acceso y asignación de responsabilidades</p>
                    </div>
                </div>

                <div class="space-y-12">
                    
                    <section class="space-y-4">
                        <p class="text-slate-600">
                            Cualquier funcionario puede registrarse en la plataforma, pero <strong>no tendrá acceso a ninguna función</strong> hasta que un usuario con rol de <strong>Encargado</strong> o <strong>Administrador</strong> le asigne un rol específico o lo active.
                        </p>
                        
                        <div class="border rounded-xl overflow-hidden shadow-sm">
                            <img src="{{ asset('images/manual/gestion_usuarios.jpg') }}" alt="Gestión de Usuarios" class="w-full h-auto">
                        </div>
                    </section>
                    
                    <section class="space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">Descripción de Roles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Role: Administrador -->
                            <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100">
                                <h4 class="font-bold text-emerald-900 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Administrador
                                </h4>
                                <p class="text-sm text-emerald-800">
                                    Tiene acceso total al sistema. Puede gestionar usuarios, configuración global, eliminar registros (si es necesario) y ver todos los reportes.
                                </p>
                            </div>

                            <!-- Role: Encargado -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Encargado
                                </h4>
                                <p class="text-sm text-blue-800">
                                    Es quien <strong>asigna roles</strong> a otros usuarios. Responsable de la gestión de equipos y supervisión general.
                                </p>
                            </div>

                            <!-- Role: Supervisor -->
                            <div class="bg-violet-50 p-4 rounded-lg border border-violet-100">
                                <h4 class="font-bold text-violet-900 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-violet-500 rounded-full"></span> Supervisor
                                </h4>
                                <p class="text-sm text-violet-800">
                                    Rol de auditoría. Puede ver todos los movimientos, stocks y reportes, pero tiene restricciones para realizar modificaciones críticas.
                                </p>
                            </div>

                            <!-- Role: Vacunador -->
                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-slate-500 rounded-full"></span> Vacunador
                                </h4>
                                <p class="text-sm text-slate-800">
                                    Rol operativo. Su función principal es <strong>Administrar</strong> vacunas (registrar consumo) y consultar stock disponible.
                                </p>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        @endif


    </div>
</div>
