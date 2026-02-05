<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vaccine;
use App\Models\MovementItem;
use App\Models\Stock;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    protected $listeners = ['refreshDashboard' => '$refresh'];
    public $search = '';

    // Report State
    public $showReportModal = false;
    public $reportType = null;
    public $reportVaccineId = null;
    public $reportStartDate;
    public $reportEndDate;

    // Date Filter State
    public $selectedMonth;
    public $selectedYear;

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        
        // Init Report Dates to Current Month
        $this->reportStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->reportEndDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSelectedMonth() { $this->dispatch('refreshDashboard'); }
    public function updatedSelectedYear() { $this->dispatch('refreshDashboard'); }

    public function getMonthsProperty()
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
    }
    
    public function getYearsProperty()
    {
        return range(now()->year, 2024);
    }

    // ... Properties ...

    public function openReport($type)
    {
        $this->reportType = $type;
        $this->reportVaccineId = null; // Reset filter
        
        // Sync local report dates with dashboard selection for convenience initial state
        $start = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $this->reportStartDate = $start->format('Y-m-d');
        $this->reportEndDate = $start->copy()->endOfMonth()->format('Y-m-d');
        
        $this->showReportModal = true;
    }

    public function getReportDataProperty()
    {
        if (!$this->showReportModal) return [];

        // Use the SPECIFIC Report Dates, not global dashboard month
        $start = Carbon::parse($this->reportStartDate)->startOfDay();
        $end = Carbon::parse($this->reportEndDate)->endOfDay();

        // Base Query
        $query = MovementItem::with(['movement.user', 'vaccine', 'batch']);

        // Filter by Vaccine if selected
        if ($this->reportVaccineId) {
            $query->where('vaccine_id', $this->reportVaccineId);
        }

        // Apply Logic
        $user = auth()->user();
        $isVacunadorOnly = $user->hasRole('vacunador') && !$user->hasAnyRole(['admin', 'supervisor', 'encargado']);
        
        // Common User Filter Closure
        $applyUserFilter = function($q) use ($isVacunadorOnly, $user) {
            if ($isVacunadorOnly) {
                $q->where('user_id', $user->id);
            }
        };

        return match ($this->reportType) {
            'ADMINISTRADAS_MES' => $query->whereHas('movement', function ($q) use ($start, $end, $applyUserFilter) {
                $applyUserFilter($q);
                $q->where('type', 'ADMINISTRATION')
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$start, $end]);
            })->orderByDesc('id')->get(),

            'DESPACHADAS_MES' => $query->whereHas('movement', function ($q) use ($start, $end) {
                $q->where('type', 'DISPATCH')
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$start, $end]);
            })->orderByDesc('id')->get(),

            'PERDIDA_MES' => $query->whereHas('movement', function ($q) use ($start, $end) {
                $q->whereIn('type', ['WASTAGE', 'BREAKAGE', 'LOSS'])
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$start, $end]);
            })->orderByDesc('id')->get(),

            default => []
        };
    }

    public function exportPdf()
    {
        $data = $this->reportData;

        if ($data->isEmpty()) {
            $this->js("alert('No hay registros para generar el reporte en este periodo. Seleccione otro rango de fechas o vacuna.')");
            return;
        }

        $title = match($this->reportType) {
            'ADMINISTRADAS_MES' => 'VACUNAS ADMINISTRADAS',
            'DESPACHADAS_MES' => 'DESPACHOS A DEPENDENCIAS',
            'PERDIDA_MES' => 'MERMAS Y PÉRDIDAS',
            default => 'REPORTE'
        };

        $user = auth()->user();
        $isVacunadorOnly = $user->hasRole('vacunador') && !$user->hasAnyRole(['admin', 'supervisor', 'encargado']);
        $userFilterName = $isVacunadorOnly ? $user->name : null;

        // Prepare Logo
        $path = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $dataImg = file_get_contents($path);
            if ($dataImg !== false) {
                 $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report', [
            'data' => $data,
            'title' => $title,
            'startDate' => $this->reportStartDate,
            'endDate' => $this->reportEndDate,
            'userFilter' => $userFilterName,
            'logoBase64' => $logoBase64,
            'user' => $user->name
        ]);
        
        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'reporte_vacunas.pdf');
    }

    public function render()
    {
        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $isCurrentMonth = $startOfMonth->isCurrentMonth();

        // 1. Calculate Monthly KPIs
        $perdida_mes = MovementItem::whereHas('movement', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereIn('type', ['WASTAGE', 'BREAKAGE', 'LOSS'])
              ->where('status', 'POSTED')
              ->whereBetween('posted_at', [$startOfMonth, $endOfMonth]);
        })->sum('quantity');

        $administradas_mes = MovementItem::whereHas('movement', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->where('type', 'ADMINISTRATION')
              ->where('status', 'POSTED')
              ->whereBetween('posted_at', [$startOfMonth, $endOfMonth]);
        })->sum('quantity');

        $despachadas_mes = MovementItem::whereHas('movement', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->where('type', 'DISPATCH')
              ->where('status', 'POSTED')
              ->whereBetween('posted_at', [$startOfMonth, $endOfMonth]);
        })->sum('quantity');

        // Cuarentena: Stock of Vaccines in QUARANTINE status OR in QUARANTINE locations
        $cuarentena_mes = Stock::where(function($query) {
            $query->whereHas('location', function ($q) {
                $q->where('type', 'QUARANTINE');
            })->orWhereHas('vaccine', function ($q) {
                $q->where('status', 'QUARANTINE');
            });
        })->sum('quantity');

        // Stocks for formula (Total Active Stock)
        $total_stock_active = Stock::whereHas('location', function ($q) {
             $q->where('type', '!=', 'QUARANTINE');
        })->sum('quantity');

        // % Perdida: (Losses / (Current Stock + Administrations + Dispatches + Losses)) * 100
        // Denominator represents "Total Material Handled/Available" during the period
        $total_disponible = $total_stock_active + $administradas_mes + $despachadas_mes + $perdida_mes;
        $porcentaje_perdida = $total_disponible > 0 ? ($perdida_mes / $total_disponible) * 100 : 0;

        // -- New: Personal Stats for Vaccinators --
        $my_administradas = MovementItem::whereHas('movement', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->where('type', 'ADMINISTRATION')
              ->where('status', 'POSTED')
              ->where('user_id', auth()->id()) // Personal Data
              ->whereBetween('posted_at', [$startOfMonth, $endOfMonth]);
        })->sum('quantity');

        // 2. Fetch Visual Kardex (Stock per Vaccine)
        $search = $this->search;
        
        $vaccines = Vaccine::withSum(['stocks as current_stock' => function($query) {
                $query->whereHas('location', function($q) {
                    $q->where('type', '!=', 'QUARANTINE');
                });
            }], 'quantity')
            ->withSum(['stocks as quarantine_stock' => function($query) {
                $query->whereHas('location', function($q) {
                    $q->where('type', 'QUARANTINE');
                });
            }], 'quantity')
            ->whereIn('status', ['ACTIVE', 'QUARANTINE'])
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->withMax('movementItems as latest_movement_at', 'created_at')
            ->get()
            ->sort(function ($a, $b) {
                // 1. Priority: Has Stock > 0 (Show these first)
                $aHasStock = $a->current_stock > 0;
                $bHasStock = $b->current_stock > 0;
                
                if ($aHasStock !== $bHasStock) {
                    return $bHasStock <=> $aHasStock; // True (1) comes before False (0)
                }

                // 2. Priority: Latest Movement (Show recent first)
                return $b->latest_movement_at <=> $a->latest_movement_at;
            });

        // 3. Process Historical Stock Calculation if not current month
        $stocks = $vaccines->map(function ($vaccine) use ($isCurrentMonth, $endOfMonth) {
            $vaccine->total_quantity = $vaccine->current_stock ?? 0;

            if (!$isCurrentMonth) {
                // We need to REVERSE movements that happened AFTER the end of the selected month
                // to get back to the state at that time.
                // Current = Historical + (In - Out)
                // Historical = Current - (In - Out) where (In - Out) is the net change AFTER the date.
                
                // Fetch movements AFTER the period
                $futureMovements = MovementItem::where('vaccine_id', $vaccine->id)
                    ->whereHas('movement', function ($q) use ($endOfMonth) {
                        $q->where('status', 'POSTED')
                          ->where('posted_at', '>', $endOfMonth);
                    })
                    ->with('movement')
                    ->get();
                
                foreach ($futureMovements as $item) {
                    $type = $item->movement->type;
                    $qty = $item->quantity;

                    // Reverse the effect
                    // If it was an ADDITION (Receipt), we SUBTRACT it to go back in time.
                    // If it was a SUBTRACTION (Dispatch/Admin), we ADD it to go back.
                    
                    if (in_array($type, ['RECEIPT', 'INVENTORY_ADJUSTMENT', 'TRANSFER_IN'])) { // Assuming logic for transfer
                         // It added to stock, so we remove it
                         $vaccine->total_quantity -= $qty;
                    } elseif (in_array($type, ['DISPATCH', 'ADMINISTRATION', 'WASTAGE', 'LOSS', 'BREAKAGE', 'EXPIRY'])) {
                         // It removed from stock, so we add it back
                         $vaccine->total_quantity += $qty;
                    }
                }
            }
            
            return $vaccine;
        });

        $max_quantity = $stocks->max('total_quantity') ?: 1;

        return view('livewire.dashboard', [
            'perdida_mes' => $perdida_mes,
            'administradas_mes' => $administradas_mes,
            'my_administradas' => $my_administradas, // New Variable
            'despachadas_mes' => $despachadas_mes,
            'porcentaje_perdida' => $porcentaje_perdida,
            'cuarentena_mes' => $cuarentena_mes,
            'stocks' => $stocks,
            'max_quantity' => $max_quantity,
            'isCurrentMonth' => $isCurrentMonth
        ]);
    }

    public function exportReport()
    {
        $data = $this->reportData; // Reuses the current logic (filters applied)
        $fileName = 'reporte_' . strtolower($this->reportType) . '_' . now()->format('Ymd_Hi') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            
            // BOM for Excel compatibility (UTF-8)
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, ['Fecha', 'Vacuna', 'Responsable', 'Cantidad', 'Notas'], ';');

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->movement->posted_at ? \Carbon\Carbon::parse($row->movement->posted_at)->format('d/m/Y H:i') : '-',
                    $row->vaccine->name,
                    $row->movement->user->name ?? 'Sistema',
                    $row->quantity,
                    $row->movement->notes
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }

    public function openAdjustmentModal($vaccineId, $type)
    {
        \Illuminate\Support\Facades\Log::info("Dashboard: Button Clicked for Vaccine {$vaccineId} Type {$type}");
        $this->dispatch('openAdjustmentModal', vaccineId: $vaccineId, type: $type);
    }
}
