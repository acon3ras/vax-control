<div>
    <!-- Load Chart.js (Idempotent) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true" wire:click="close"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">
                            Estadísticas: <span class="text-premium-600">{{ $vaccine->name }}</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-wide font-semibold">{{ $vaccine->code }}</p>
                    </div>
                    <button wire:click="close" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body / Chart -->
                <div class="px-6 py-6 bg-white">
                    <div class="space-y-4">
                        <div class="h-64 relative w-full" 
                             x-data='{
                                chart: null,
                                init() {
                                    // Helper to load plugin safely
                                    const loadPluginAndRender = () => {
                                        if (typeof ChartDataLabels === "undefined") {
                                            let script = document.createElement("script");
                                            script.src = "https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2";
                                            script.onload = () => {
                                                Chart.register(ChartDataLabels);
                                                this.renderChart();
                                            };
                                            script.onerror = () => {
                                                console.warn("DataLabels plugin failed to load. Rendering basic chart.");
                                                this.renderChart(); // Fallback
                                            };
                                            document.head.appendChild(script);
                                        } else {
                                            // Plugin already loaded
                                            Chart.register(ChartDataLabels);
                                            this.renderChart();
                                        }
                                    };

                                    // Main Load Logic
                                    if (typeof Chart === "undefined") {
                                        let script = document.createElement("script");
                                        script.src = "https://cdn.jsdelivr.net/npm/chart.js";
                                        script.onload = () => loadPluginAndRender(); // Chain logic
                                        document.head.appendChild(script);
                                    } else {
                                        loadPluginAndRender();
                                    }
                                },
                                renderChart() {
                                    let ctx = this.$refs.canvas.getContext("2d");
                                    
                                    if (this.chart) this.chart.destroy();

                                    this.chart = new Chart(ctx, {
                                        type: "doughnut",
                                        data: {
                                            labels: @json($labels), 
                                            datasets: [{
                                                data: [
                                                    {{ $administeredData }}, 
                                                    {{ $dispatchedData }}, 
                                                    {{ $wastageData }}
                                                ],
                                                backgroundColor: [
                                                    "#10b981", // Emerald 500
                                                    "#3b82f6", // Blue 500
                                                    "#ef4444"  // Red 500
                                                ],
                                                borderColor: "#ffffff",
                                                borderWidth: 2,
                                                hoverOffset: 4
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            layout: {
                                                padding: 20
                                            },
                                            plugins: {
                                                legend: {
                                                    position: "bottom",
                                                    labels: { 
                                                        usePointStyle: true, 
                                                        boxWidth: 8, 
                                                        padding: 20,
                                                        font: { family: "Inter", size: 12 } 
                                                    }
                                                },
                                                tooltip: {
                                                    enabled: true,
                                                    backgroundColor: "rgba(15, 23, 42, 0.9)",
                                                    titleFont: { family: "Inter", size: 13 },
                                                    bodyFont: { family: "Inter", size: 12 },
                                                    padding: 12,
                                                    cornerRadius: 8,
                                                },
                                                datalabels: {
                                                    color: "#ffffff",
                                                    font: {
                                                        family: "Inter",
                                                        weight: "bold",
                                                        size: 14
                                                    },
                                                    formatter: (value, ctx) => {
                                                        // Only show if value > 0 to avoid clutter
                                                        return value > 0 ? value : "";
                                                    },
                                                    textShadowBlur: 4,
                                                    textShadowColor: "rgba(0, 0, 0, 0.3)"
                                                }
                                            },
                                            cutout: "50%",
                                            animation: {
                                                animateScale: true,
                                                animateRotate: true
                                            }
                                        }
                                    });
                                }
                             }'
                             x-init="init()"
                        >
                            <canvas x-ref="canvas"></canvas>
                        </div>
                        <div class="text-center text-xs text-slate-400 mt-4">
                            Distribución de movimientos del periodo seleccionado.
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex justify-end">
                    <button type="button" wire:click="close" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:border-slate-300 hover:text-slate-800 transition-all shadow-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
