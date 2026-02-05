<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vaccine;
use App\Models\Movement;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Reports extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        if (auth()->user()->hasRole('vacunador')) {
            return redirect()->route('dashboard');
        }

        $this->month = date('m');
        $this->year = date('Y');
    }

    public function generate()
    {
        // Increase memory and time limits for PDF generation
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $startDate = Carbon::createFromDate($this->year, $this->month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        // Fetch Vaccines with MovementItems filtered by the related Movement date
        // Note: Using standard Query Builder for performance if needed, but keeping Eloquent for now as dataset is small
        $vaccines = Vaccine::with(['movementItems' => function($q) use ($startDate, $endDate) {
            $q->whereHas('movement', function($mq) use ($startDate, $endDate) {
                $mq->whereBetween('posted_at', [$startDate, $endDate]);
            })->with('movement'); // Eager load the movement to access 'type'
        }])->get();

        $reportData = [];

        foreach($vaccines as $vac) {
            $items = $vac->movementItems;
            
            // Calculate sums based on the TYPE of the related MOVEMENT
            $inputs = $items->filter(fn($item) => $item->movement->type === 'RECEIPT')->sum('quantity');
            $administered = $items->filter(fn($item) => $item->movement->type === 'ADMINISTRATION')->sum('quantity');
            $dispatched = $items->filter(fn($item) => $item->movement->type === 'DISPATCH')->sum('quantity');
            $wastage = $items->filter(fn($item) => $item->movement->type === 'WASTAGE')->sum('quantity');
            
            // Current Stock (Snapshot)
            $currentStock = $vac->stocks()->sum('quantity');

            if ($inputs > 0 || $administered > 0 || $dispatched > 0 || $wastage > 0 || $currentStock > 0) {
                $reportData[] = [
                    'name' => $vac->name,
                    'code' => $vac->code,
                    'inputs' => $inputs,
                    'administered' => $administered,
                    'dispatched' => $dispatched,
                    'wastage' => $wastage,
                    'current_stock' => $currentStock
                ];
            }
        }

        try {
            // Prepare Logo as Base64 to avoid DOMPDF path issues in production
            $path = public_path('images/logo.png');
            $logoBase64 = '';
            
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                if ($data !== false) {
                     $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }

            // Render View to HTML String
            $html = view('pdf.monthly-report', [
                'data' => $reportData,
                'month' => $startDate->translatedFormat('F'),
                'year' => $this->year,
                'user' => auth()->user()->name,
                'logoBase64' => $logoBase64
            ])->render();
            
            // NATIVE DOMPDF USAGE (Bypassing Laravel Wrapper)
            // This matches the successful test_pdf.php script
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();

            return response()->streamDownload(function () use ($output) {
                echo $output;
            }, 'reporte_vacunas_' . $this->month . '_' . $this->year . '.pdf');
            
        } catch (\Throwable $e) {
            // Fallback: Download error log if PDF fails
            return response()->streamDownload(function () use ($e) {
                echo "ERROR CRITICO AL GENERAR PDF:\n";
                echo $e->getMessage() . "\n\n";
                echo "STACK TRACE:\n" . $e->getTraceAsString();
            }, 'error_log.txt');
        }
    }

    public function render()
    {
        return view('livewire.reports');
    }
}
