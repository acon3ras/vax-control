<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;
use App\Models\Location;
use App\Models\Batch;
use App\Models\Movement;
use App\Models\MovementItem;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $vaccines = Vaccine::all();
        $location = Location::where('name', 'Hospital de Puerto Aysén')->first();

        // Ensure user exists for auditing
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@vaxcontrol.cl'],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'status' => 'ACTIVE'
            ]
        );

        if (!$location) {
             $location = Location::create([
                'name' => 'Hospital de Puerto Aysén',
                'type' => 'VACCINATION_POINT',
                'description' => 'Ubicación central del inventario',
                'is_active' => true,
            ]);
        }

        // Data arrays (Ordered matching the vaccines)
        // Ensure arrays are large enough or handle missing keys in loop
        $stocks = [10, 40, 30, 47, 0, 22, 0, 46, 16, 58, 15, 2, 0, 26, 34, 25, 12, 37, 37, 0, 16, 0, 0, 24, 20, 19, 0, 0, 0, 0];
        $perdidas = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $administradas = [2, 0, 2, 1, 0, 6, 0, 0, 10, 3, 0, 0, 0, 14, 30, 0, 10, 0, 0, 0, 0, 3, 0, 1, 0, 0, 0, 0, 0, 0];
        $despachadas = [47, 0, 40, 0, 5, 40, 0, 10, 0, 0, 0, 0, 0, 28, 10, 0, 20, 10, 0, 0, 0, 0, 0, 32, 70, 0, 0, 0, 0, 0];
        $cuarentena = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        foreach ($vaccines as $index => $vaccine) {
            DB::transaction(function () use ($vaccine, $index, $location, $stocks, $perdidas, $administradas, $despachadas, $user) {
                $batch = Batch::firstOrCreate(
                    ['vaccine_id' => $vaccine->id, 'batch_number' => 'STOCK_UNICO'],
                    ['expiry_date' => now()->addYears(10), 'manufacturer_batch' => 'N/A']
                );

                // Calculate Total to "Load" initially
                $totalToLoad = ($stocks[$index] ?? 0) + ($perdidas[$index] ?? 0) + ($administradas[$index] ?? 0) + ($despachadas[$index] ?? 0);

                if ($totalToLoad > 0) {
                    // 1. Initial Receipt
                    $m1 = Movement::create([
                        'user_id' => $user->id,
                        'type' => 'RECEIPT',
                        'destination_location_id' => $location->id,
                        'notes' => 'Carga inicial de inventario',
                        'status' => 'DRAFT',
                        'posted_at' => now()->startOfMonth(),
                    ]);
                    MovementItem::create([
                        'movement_id' => $m1->id,
                        'vaccine_id' => $vaccine->id,
                        'batch_id' => $batch->id,
                        'quantity' => $totalToLoad,
                    ]);
                    $m1->post();

                    // 2. Register Administration
                    if (($administradas[$index] ?? 0) > 0) {
                        $m2 = Movement::create([
                            'user_id' => $user->id,
                            'type' => 'ADMINISTRATION',
                            'source_location_id' => $location->id,
                            'notes' => 'Registros acumulados del mes',
                            'status' => 'DRAFT',
                            'posted_at' => now(),
                        ]);
                        MovementItem::create([
                            'movement_id' => $m2->id,
                            'vaccine_id' => $vaccine->id,
                            'batch_id' => $batch->id,
                            'quantity' => $administradas[$index],
                        ]);
                        $m2->post();
                    }

                    // 3. Register Dispatch
                    if (($despachadas[$index] ?? 0) > 0) {
                        $m3 = Movement::create([
                            'user_id' => $user->id,
                            'type' => 'DISPATCH',
                            'source_location_id' => $location->id,
                            'notes' => 'Despachos acumulados del mes',
                            'status' => 'DRAFT',
                            'posted_at' => now(),
                        ]);
                        MovementItem::create([
                            'movement_id' => $m3->id,
                            'vaccine_id' => $vaccine->id,
                            'batch_id' => $batch->id,
                            'quantity' => $despachadas[$index],
                        ]);
                        $m3->post();
                    }

                    // 4. Register Losses (Perdidas)
                    if (($perdidas[$index] ?? 0) > 0) {
                        $m4 = Movement::create([
                            'user_id' => $user->id,
                            'type' => 'WASTAGE',
                            'source_location_id' => $location->id,
                            'notes' => 'Pérdidas acumuladas del mes',
                            'status' => 'DRAFT',
                            'posted_at' => now(),
                        ]);
                        MovementItem::create([
                            'movement_id' => $m4->id,
                            'vaccine_id' => $vaccine->id,
                            'batch_id' => $batch->id,
                            'quantity' => $perdidas[$index],
                        ]);
                        $m4->post();
                    }
                }
            });
        }
    }
}
