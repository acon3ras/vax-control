<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Roles si no existen
        $this->call(RoleSeeder::class);

        // 2. Crear Usuario Admin Principal
        $admin = User::firstOrCreate(
            ['email' => 'informatica.hpa@saludaysen.cl'],
            [
                'rut' => '161516201',
                'name' => 'Alexi Contreras Vera',
                'password' => Hash::make('password'), // Contraseña temporal, cambiar luego
                'unit_service' => 'Informatica',
                'position_title' => 'Soporte TIC',
                'status' => 'ACTIVE'
            ]
        );

        // 3. Asignar Rol Admin
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command->info('Usuario Administrador creado/verificado correctamente.');
    }
}
