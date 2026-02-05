<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;
use Illuminate\Support\Str;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            'PREVENAR 13', 'BCG', 'HEXAVALENTE', 'NEUMO 23', 'NIMENRIX',
            'SRP MONO', 'SRP MULTI', 'HEP A', 'HEP B', 'DTPA',
            'HEPATITIS B PEDIATRICA', 'INMUNOGLOBULINA TETANICA',
            'INMUNOGLOBULINA RABICA', 'ANTIRABICA', 'ANTITETANICA',
            'VPH', 'INFLUENZA', 'VARICELA', 'NIRSEVIMAB 50',
            'PFIZER ACTUALIZADA', 'NIRSEVIMAB 100', 'SÍMICA-JYNNEOS',
            'MODERNA ACT', 'BEXSERO', 'MENQUADFI', 'VPH NONAVALENTE'
        ];

        foreach ($vaccines as $name) {
            Vaccine::firstOrCreate(
                ['name' => $name],
                [
                    'code' => $this->generateCode($name),
                    'presentation' => 'Frasco',
                    'dose_per_unit' => 1,
                    'status' => 'ACTIVE',
                    'min_stock' => 10,
                    'optimal_stock' => 50,
                    'manufacturer' => 'Laboratorio Genérico',
                ]
            );
        }
    }

    private function generateCode($name)
    {
        return Str::upper(Str::slug($name, ''));
    }
}
