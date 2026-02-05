<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Vaccine;
use App\Models\Movement;
use App\Models\MovementItem;
use App\Models\Location;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;

class InventoryAdjuster extends Component
{
    use WithFileUploads;
    public $showModal = false;
    public $type = 'RECEIPT';
    public $vaccine_id;
    public $quantity = 1;
    public $notes = '';
    public $batch_number = 'STOCK_UNICO'; 
    
    // New fields
    public $destination_id;
    public $reason;
    public $externalLocations = [];
    public $locked = false;
    public $evidence; // File upload property


    protected $listeners = ['openAdjustmentModal' => 'open'];

    protected $rules = [
        'type' => 'required|string',
        'vaccine_id' => 'required|exists:vaccines,id',
        'quantity' => 'required|integer|min:1',
        'notes' => 'nullable|string|max:255',
        'batch_number' => 'required|string',
        'destination_id' => 'nullable|required_if:type,DISPATCH|exists:locations,id',
        'reason' => 'nullable|required_if:type,WASTAGE|required_if:type,QUARANTINE_MOVE|string',
        'evidence' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf', // Max 5MB
    ];

    #[On('openAdjustmentModal')]
    public function open($vaccineId = null, $type = 'RECEIPT')
    {
        \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Event Received for Vaccine {$vaccineId}");
        $this->resetValidation();
        $this->vaccine_id = $vaccineId;
        $this->type = $type;
        $this->quantity = 1;
        $this->notes = '';
        $this->batch_number = 'STOCK_UNICO';
        $this->destination_id = null;
        $this->reason = null;
        $this->evidence = null;
        
        // Lock context if called with specific parameters
        $this->locked = ($vaccineId !== null);
        
        // CHECK STOCK LEVEL
        // If stock is 0, we should only allow RECEIPT (Ingreso)
        // We set type to RECEIPT automatically if it was something else but there is no stock (except for Receipt itself)
        if ($this->vaccine_id) {
             $currentStock = \App\Models\Stock::where('vaccine_id', $this->vaccine_id)
                ->where('location_id', 1)
                ->sum('quantity');
                
             if ($currentStock <= 0 && $this->type !== 'RECEIPT') {
                 $this->type = 'RECEIPT';
                 // Optionally add a toast or message, but automatic switch is safer
             }
        }

        $this->externalLocations = Location::where('type', 'DEPENDENCY')->where('is_active', true)->orderBy('name')->get();
        
        // Permission Logic
        $user = auth()->user();
        
        // 1. Supervisor is Read-Only
        if ($user->hasRole('supervisor')) {
             $this->dispatch('toast', message: 'El perfil Supervisor es de solo lectura.', type: 'warning');
             return;
        }

        // 2. Define Allowed Roles for specific actions
        $fullAccessRoles = ['admin', 'encargado'];
        $limitedAccessRoles = ['vacunador']; // specific actions only

        // Check if user has FULL access
        if ($user->hasAnyRole($fullAccessRoles)) {
            // Allowed to do anything
        } 
        // Check if user has LIMITED access (Vacunador)
        elseif ($user->hasAnyRole($limitedAccessRoles)) {
            // Vacunador only allowed: ADMINISTRATION and WASTAGE
            if (!in_array($type, ['ADMINISTRATION', 'WASTAGE'])) {
                 $this->dispatch('toast', message: 'Su perfil solo está autorizado para registrar Administraciones (Vacunas) y Mermas.', type: 'warning');
                 return;
            }
        } 
        // No authorized role
        else {
            $this->dispatch('toast', message: 'No tiene permisos para realizar esta acción.', type: 'error');
            return;
        }

        $this->showModal = true;
    }

    public function getNotesPlaceholderProperty()
    {
        return match($this->type) {
            'RECEIPT' => 'N° Factura, Orden de Compra...',
            'ADMINISTRATION' => 'N° Box, Nombre Paciente (Opcional)...',
            'DISPATCH' => 'Motivo del traslado, Campaña...',
            'WASTAGE' => 'Detalle del incidente...',
            'QUARANTINE_MOVE' => 'Motivo de la cuarentena (rotura de cadena de frío, alerta sanitaria)...',
            'INVENTORY_ADJUSTMENT' => 'Justificación del ajuste manual...',
            default => 'Notas adicionales...'
        };
    }


    public function getAvailableStockProperty()
    {
        if (!$this->vaccine_id) return 0;
        
        // Main Location (ID 1)
        return \App\Models\Stock::where('vaccine_id', $this->vaccine_id)
            ->where('location_id', 1)
            ->sum('quantity');
    }

    public function getQuarantineStockProperty()
    {
        if (!$this->vaccine_id) return 0;

        return \App\Models\Stock::where('vaccine_id', $this->vaccine_id)
            ->whereHas('location', function($q) {
                $q->where('type', 'QUARANTINE');
            })
            ->sum('quantity');
    }

    public function getStockProperty()
    {
        // For Release, return Quarantine Stock so it validates against what we are taking out
        if ($this->type === 'QUARANTINE_RELEASE') {
            return $this->quarantineStock;
        }
        
        // For everything else (including putting INTO quarantine), we check Available stock
        return $this->availableStock;
    }

    public function process()
    {
        \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Process started", [
            'type' => $this->type,
            'vaccine_id' => $this->vaccine_id,
            'quantity' => $this->quantity,
            'batch' => $this->batch_number
        ]);

        // Validate with explicit error messages
        try {
            $this->validate([
            'type' => 'required|string',
            'vaccine_id' => 'required|exists:vaccines,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
            'batch_number' => 'required|string',
            'destination_id' => 'nullable|required_if:type,DISPATCH|exists:locations,id',
            'reason' => [
                'nullable',
                'required_if:type,WASTAGE',
                'required_if:type,QUARANTINE_MOVE',
                'string'
            ],
            'evidence' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ], [
            'reason.required_if' => 'El motivo de la cuarentena es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'vaccine_id.required' => 'Debe seleccionar una vacuna.',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error("InventoryAdjuster: Validation Failed", $e->errors());
            throw $e;
        }

        $vaccine = Vaccine::find($this->vaccine_id);
        if (!$vaccine) {
            $this->addError('vaccine_id', 'La vacuna seleccionada no existe.');
            return;
        }
        
        // Allow QUARANTINE_MOVE even if vaccine is in quarantine (to move more stock)
        if ($vaccine->status !== 'ACTIVE' && $this->type !== 'QUARANTINE_MOVE') {
            $this->addError('vaccine_id', 'La vacuna seleccionada no está activa o se encuentra en cuarentena.');
            return;
        }

        try {
            DB::transaction(function () {
                // Find Main Location by Type or Name instead of hardcoded ID 1
                $location = Location::where('type', 'VACCINATION_POINT')->first();
                
                if (!$location) {
                    // Fallback try name search
                    $location = Location::where('name', 'like', '%Hospital%')->first();
                }

                if (!$location) {
                    throw new \Exception('La ubicación principal (ID 1) no está configurada.');
                }

                // Determine effective destination for DISPATCH
                $destination = ($this->type === 'DISPATCH') ? $this->destination_id : ($this->type === 'RECEIPT' || $this->type === 'INVENTORY_ADJUSTMENT' ? $location->id : null);
                
                // Determine effective source
                $source = ($this->type === 'RECEIPT' || $this->type === 'INVENTORY_ADJUSTMENT') ? null : $location->id;

                // Handle QUARANTINE_RELEASE Source Override (Must be done before validation)
                if ($this->type === 'QUARANTINE_RELEASE') {
                    $quarantineLocation = Location::where('type', 'QUARANTINE')->firstOrFail();
                    $source = $quarantineLocation->id;       // FROM Quarantine
                    $destination = $location->id;            // TO Main Stock
                }
                
                // Handle QUARANTINE_MOVE Destination Override
                 if ($this->type === 'QUARANTINE_MOVE') {
                    $quarantineLocation = Location::firstOrCreate(
                        ['name' => 'Bodega de Cuarentena'],
                        ['type' => 'QUARANTINE', 'is_active' => false, 'description' => 'Ubicación lógica para stock en cuarentena']
                    );
                    // Self-healing check
                    if ($quarantineLocation->type !== 'QUARANTINE') {
                        $quarantineLocation->update(['type' => 'QUARANTINE']);
                    }
                    
                    $destination = $quarantineLocation->id;
                    $source = $location->id; // From Main
                }

                // Ensure batch exists
                $batch = Batch::firstOrCreate(
                    ['vaccine_id' => $this->vaccine_id, 'batch_number' => $this->batch_number],
                    ['expiry_date' => now()->addYears(10), 'manufacturer_batch' => 'N/A']
                );

                // --- SMART BATCH SELECTION START ---
                // Attempts to auto-fix "Stock insufficient" when user leaves "STOCK_UNICO" (default)
                // but real stock is in specific batches (e.g. from initial migration or specific receipts).
                if ($this->batch_number === 'STOCK_UNICO' && !in_array($this->type, ['RECEIPT', 'INVENTORY_ADJUSTMENT'])) {
                     $currentBatchStock = \App\Models\Stock::where('location_id', $source)
                        ->where('vaccine_id', $this->vaccine_id)
                        ->where('batch_id', $batch->id)
                        ->sum('quantity');

                     if ($currentBatchStock < $this->quantity) {
                         // Find oldest batch with enough stock (FEFO - First Expired First Out)
                         // We join with batches table to sort by expiry_date
                         $bestBatchStock = \App\Models\Stock::where('stocks.location_id', $source)
                            ->where('stocks.vaccine_id', $this->vaccine_id)
                            ->where('stocks.quantity', '>=', $this->quantity)
                            ->join('batches', 'stocks.batch_id', '=', 'batches.id')
                            ->orderBy('batches.expiry_date', 'asc')
                            ->select('stocks.*') // Return Stock model
                            ->first();

                         if ($bestBatchStock) {
                             $originalBatch = $batch->batch_number;
                             $batch = $bestBatchStock->batch; // SWAP THE BATCH OBJECT
                             \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Smart Switched batch from {$originalBatch} to {$batch->batch_number} (FEFO)");
                         }
                     }
                }
                // --- SMART BATCH SELECTION END ---

                // Validate stock availability BEFORE creating movement for operations that reduce stock
                if (in_array($this->type, ['DISPATCH', 'ADMINISTRATION', 'EXPIRY', 'WASTAGE', 'BREAKAGE', 'LOSS', 'QUARANTINE_MOVE', 'QUARANTINE_RELEASE'])) {
                    // For QUARANTINE_MOVE, check total stock (all batches) to allow flexibility
                    // For other operations, check specific batch
                    $query = \App\Models\Stock::where('location_id', $source)
                        ->where('vaccine_id', $this->vaccine_id);
                    
                    // For QUARANTINE_MOVE and RELEASE, sum all batches; for others, check specific batch
                    if (!in_array($this->type, ['QUARANTINE_MOVE', 'QUARANTINE_RELEASE'])) {
                        $query->where('batch_id', $batch->id);
                    } else {
                         // SMART BATCH SELECTION FOR QUARANTINE RELEASE
                         // If we are releasing, and the user didn't specify a specific batch (or the one specified is empty),
                         // we should try to find which batch actually HOLDS the stock in quarantine to avoid negative balances.
                         if ($this->type === 'QUARANTINE_RELEASE') {
                             $currentBatchStock = \App\Models\Stock::where('location_id', $source)
                                ->where('vaccine_id', $this->vaccine_id)
                                ->where('batch_id', $batch->id)
                                ->sum('quantity');
                             
                             if ($currentBatchStock < $this->quantity) {
                                 // Try to find a batch that can satisfy the request
                                 $bestStock = \App\Models\Stock::where('location_id', $source)
                                    ->where('vaccine_id', $this->vaccine_id)
                                    ->where('quantity', '>=', $this->quantity)
                                    ->orderByDesc('quantity')
                                    ->first();
                                    
                                 if ($bestStock) {
                                     $batch = $bestStock->batch;
                                     // Refilter query with new batch if we were strict, but we are lax here. 
                                     // However, for the MovementItem below, $batch is now correct.
                                 }
                             }
                         }
                    }

                    $availableStock = $query->sum('quantity');
                    
                    if ($availableStock < $this->quantity) {
                        $batchInfo = ($this->type === 'QUARANTINE_MOVE') ? 'Total disponible' : $batch->batch_number;
                        throw new \Exception("Stock insuficiente. Disponible: {$availableStock}, Solicitado: {$this->quantity} (Lote: {$batchInfo})");
                    }
                }

                // Validate user is authenticated
                $userId = auth()->id();
                if (!$userId) {
                    throw new \Exception('Usuario no autenticado. Por favor, inicie sesión nuevamente.');
                }
                
                // --- BACKEND PERMISSION CHECK ---
                $user = \App\Models\User::find($userId);
                
                if ($user->hasRole('supervisor')) {
                    throw new \Exception('El perfil Supervisor es de solo lectura.');
                }

                $fullAccessRoles = ['admin', 'encargado'];
                $limitedAccessRoles = ['vacunador'];

                if (!$user->hasAnyRole(array_merge($fullAccessRoles, $limitedAccessRoles))) {
                    throw new \Exception('Acción no autorizada para su perfil.');
                }

                // If is Vacunador, check TYPE
                if (!$user->hasAnyRole($fullAccessRoles) && $user->hasAnyRole($limitedAccessRoles)) {
                     if (!in_array($this->type, ['ADMINISTRATION', 'WASTAGE'])) {
                         throw new \Exception('Su perfil solo puede registrar Administraciones y Mermas.');
                     }
                }
                // --------------------------------




                \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Creating movement...", ['source' => $source, 'dest' => $destination]);

                // Create Movement
                $movement = Movement::create([
                    'user_id' => $userId, 
                    'ip_address' => request()->ip(), // Capture IP address 
                    'type' => $this->type,
                    'source_location_id' => $source,
                    'destination_location_id' => $destination,
                    'notes' => $this->notes,
                    'reason' => $this->reason, // Save the specific reason (Theft, Expiry, etc.)
                    'status' => 'DRAFT',
                ]);
                
                \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Movement created", ['id' => $movement->id]);

                // Handle Evidence File Upload
                if ($this->evidence) {
                    $year = date('Y');
                    $month = date('m');
                    $day = date('d'); // Added Day
                    
                    $extension = $this->evidence->getClientOriginalExtension();
                    $fileName = $movement->id . '_' . time() . '.' . $extension;
                    
                    // Folder structure: evidence/2026/01/05
                    $folderPath = "evidence/{$year}/{$month}/{$day}";
                    
                    // Store in public disk
                    $path = $this->evidence->storeAs($folderPath, $fileName, 'public');
                    
                    // IMAGE OPTIMIZATION (Native PHP)
                    // Only for images (jpg, jpeg, png, webp) to save space
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fullPath = storage_path('app/public/' . $path);
                        $this->optimizeImage($fullPath, $extension);
                    }
                    
                    $movement->evidence_path = $path;
                    $movement->save();
                }

                // Create Item
                MovementItem::create([
                    'movement_id' => $movement->id,
                    'vaccine_id' => $this->vaccine_id,
                    'batch_id' => $batch->id,
                    'quantity' => $this->quantity,
                ]);

                // Post Movement (Atomic Stock Update)
                $movement->post();
                
                \Illuminate\Support\Facades\Log::info("InventoryAdjuster: Movement Posted Successfully");
            });

            $this->reset(); // Resets all properties (showModal becomes false)
            $this->dispatch('refreshDashboard');
            $this->dispatch('refreshVaccineManager'); 
            $this->dispatch('close-modal'); // Hard Close Signal
            
            // Use Toast for visibility AFTER modal closes
            $this->dispatch('toast', message: 'Inventario actualizado con éxito.', type: 'success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            // For other exceptions, show error message
            \Illuminate\Support\Facades\Log::error('Error en InventoryAdjuster::process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'type' => $this->type,
                'vaccine_id' => $this->vaccine_id,
            ]);
            $this->addError('quantity', $e->getMessage());
            session()->flash('error', 'Error al procesar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Resize and optimize image to max 1280px width/height and 80% quality
     */
    private function optimizeImage($path, $extension)
    {
        try {
            list($width, $height) = getimagesize($path);
            $maxDim = 1280;
            
            // If image is smaller than max dims, skip resizing but can still compress if needed
            if ($width <= $maxDim && $height <= $maxDim) {
                // Could just do compression here, but for simplicity skip small images
                return;
            }
            
            // Calculate new dimensions
            $ratio = $width / $height;
            if ($ratio > 1) {
                $newWidth = $maxDim;
                $newHeight = $maxDim / $ratio;
            } else {
                $newHeight = $maxDim;
                $newWidth = $maxDim * $ratio;
            }
            
            $src = match(strtolower($extension)) {
                'jpg', 'jpeg' => imagecreatefromjpeg($path),
                'png' => imagecreatefrompng($path),
                'webp' => imagecreatefromwebp($path),
                default => null
            };
            
            if (!$src) return;
            
            $dst = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG/WebP
            if (in_array(strtolower($extension), ['png', 'webp'])) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Save replacing original
            match(strtolower($extension)) {
                'jpg', 'jpeg' => imagejpeg($dst, $path, 80), // 80% quality
                'png' => imagepng($dst, $path, 8), // Compression level 8 (0-9)
                'webp' => imagewebp($dst, $path, 80),
                default => null
            };
            
            imagedestroy($src);
            imagedestroy($dst);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Image optimization failed: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inventory-adjuster', [
            'vaccines' => Vaccine::where('status', 'ACTIVE')->orderBy('name')->get()
        ]);
    }
}
