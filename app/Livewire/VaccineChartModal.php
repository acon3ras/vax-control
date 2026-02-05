<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vaccine;
use App\Models\MovementItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VaccineChartModal extends Component
{
    public $vaccine;
    public $labels = [];
    public $administeredData = 0; // Changed to scalar as it's a sum now
    public $dispatchedData = 0;   // New property
    public $wastageData = 0;      // Changed to scalar as it's a sum now
    public $isOpen = false;

    public $selectedMonth;
    public $selectedYear;

    protected $listeners = ['openChartModal'];

    public function openChartModal($vaccineId, $month = null, $year = null)
    {
        $this->vaccine = Vaccine::find($vaccineId);
        if (!$this->vaccine) return;

        // Use passed values or default to current date
        $this->selectedMonth = $month ?? now()->month;
        $this->selectedYear = $year ?? now()->year;

        $this->loadChartData();
        $this->isOpen = true;
    }
    
    public function close()
    {
        $this->isOpen = false;
    }

    public function loadChartData()
    {
        // Define timeframe based on Selection
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        $monthName = ucfirst($date->translatedFormat('F Y')); // e.g. "Enero 2026"

        // 1. Administered Total
        $this->administeredData = MovementItem::where('vaccine_id', $this->vaccine->id)
            ->whereHas('movement', function ($q) use ($startDate, $endDate) {
                $q->where('type', 'ADMINISTRATION')
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$startDate, $endDate]);
            })->sum('quantity');

        // 2. Dispatched Total
        $this->dispatchedData = MovementItem::where('vaccine_id', $this->vaccine->id)
            ->whereHas('movement', function ($q) use ($startDate, $endDate) {
                $q->where('type', 'DISPATCH')
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$startDate, $endDate]);
            })->sum('quantity');

        // 3. Wastage Total
        $this->wastageData = MovementItem::where('vaccine_id', $this->vaccine->id)
            ->whereHas('movement', function ($q) use ($startDate, $endDate) {
                $q->whereIn('type', ['WASTAGE', 'LOSS', 'BREAKAGE', 'EXPIRY'])
                  ->where('status', 'POSTED')
                  ->whereBetween('posted_at', [$startDate, $endDate]);
            })->sum('quantity');

        // Labels
        $this->labels = ["Administradas ({$monthName})", "Despachadas ({$monthName})", "Pérdidas ({$monthName})"];
    }

    public function render()
    {
        return view('livewire.vaccine-chart-modal');
    }
}
