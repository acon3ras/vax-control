<?php

use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------------
// RUTA DE EMERGENCIA (Borrar luego)
// -----------------------------------------------------------------------------
Route::get('/fix-cache', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return '<h1 style="color:green">Caché borrada correctamente</h1> <a href="/login">Ir al Login</a>';
    } catch (\Exception $e) {
        return '<h1>Error borrando caché</h1><pre>'.$e->getMessage().'</pre>';
    }
});

// -----------------------------------------------------------------------------
// Rutas de Autenticación
// -----------------------------------------------------------------------------
Route::get('/login', \App\Livewire\Login::class)->name('login');

Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');

Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');


// -----------------------------------------------------------------------------
// Rutas Protegidas (Dashboard)
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'active'])->group(function () {
    
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Descomentaremos estas rutas UNA POR UNA si el Dashboard carga.
    Route::get('/vaccines', \App\Livewire\VaccineManager::class)->name('vaccines.index');
    Route::get('/adjust-inventory', \App\Livewire\InventoryAdjuster::class)->name('inventory.adjust');
    Route::get('/locations', \App\Livewire\LocationManager::class)->name('locations.index');
    Route::get('/users', \App\Livewire\UserManager::class)->name('users.index');
    Route::get('/activity', \App\Livewire\MovementHistory::class)->name('activity');
    Route::get('/audit-logs', \App\Livewire\AuditLogs::class)->name('audit-logs');
    Route::get('/profile', \App\Livewire\UserProfile::class)->name('profile');

    Route::get('/access-logs', \App\Livewire\AccessLogs::class)->name('access-logs');
    Route::get('/changelog', \App\Livewire\Changelog::class)->name('changelog');
    Route::get('/settings', \App\Livewire\Admin\SystemSettings::class)->name('system.settings');
});

// -----------------------------------------------------------------------------
// RUTA DE MIGRACIÓN (Emergencia - Borrar luego)
// -----------------------------------------------------------------------------
Route::get('/migrate', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<h1 style="color:green">Migraciones ejecutadas correctamente</h1><pre>'.\Illuminate\Support\Facades\Artisan::output().'</pre><a href="/dashboard">Ir al Dashboard</a>';
    } catch (\Exception $e) {
        return '<h1>Error ejecutando migraciones</h1><pre>'.$e->getMessage().'</pre>';
    }
});

// Redirección Raíz
Route::get('/', function () {
    return redirect()->route('dashboard');
});
