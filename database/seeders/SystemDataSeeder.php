<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Vaccine;
use App\Models\Batch;

class SystemDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Location
        Location::firstOrCreate(
            ['name' => 'Hospital de Puerto Aysén'],
            [
                'type' => 'VACCINATION_POINT',
                'description' => 'Ubicación central del inventario',
                'is_active' => true,
            ]
        );

        // 2. Ensure all vaccines have at least one generic batch
        $vaccines = Vaccine::all();
        foreach ($vaccines as $vaccine) {
            Batch::firstOrCreate(
                [
                    'vaccine_id' => $vaccine->id,
                    'batch_number' => 'STOCK_UNICO',
                ],
                [
                    'expiry_date' => now()->addYears(10), // Far in the future to ignore by now
                    'manufacturer_batch' => 'N/A',
                ]
            );
        }
    }
}
