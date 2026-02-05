<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\Auditable;

class Movement extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'ip_address',
        'type',
        'source_location_id',
        'destination_location_id',
        'reference_number',
        'notes',
        'reason',
        'status',
        'evidence_path',
        'posted_at',
        'posted_by',
    ];

    public function items()
    {
        return $this->hasMany(MovementItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function sourceLocation()
    {
        return $this->belongsTo(Location::class, 'source_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    /**
     * Post the movement and update stock atomically.
     */
    public function post()
    {
        if ($this->status === 'POSTED') {
            throw new \Exception('Este movimiento ya ha sido procesado.');
        }

        DB::transaction(function () {
            // Ensure items are loaded
            if (!$this->relationLoaded('items')) {
                $this->load('items');
            }

            // Validate that movement has items
            if ($this->items->isEmpty()) {
                throw new \Exception('El movimiento no tiene items asociados.');
            }

            foreach ($this->items as $item) {
                // Determine stock changes based on movement type
                switch ($this->type) {
                    case 'RECEIPT':
                    case 'INVENTORY_ADJUSTMENT':
                        $this->adjustStock($this->destination_location_id, $item, $item->quantity);
                        break;

                    case 'DISPATCH':
                    case 'ADMINISTRATION':
                    case 'EXPIRY':
                    case 'WASTAGE':
                    case 'BREAKAGE':
                    case 'LOSS':
                        $this->adjustStock($this->source_location_id, $item, -$item->quantity);
                        break;

                    case 'TRANSFER':
                    case 'QUARANTINE_MOVE':
                    case 'QUARANTINE_RELEASE':
                        $this->adjustStock($this->source_location_id, $item, -$item->quantity);
                        $this->adjustStock($this->destination_location_id, $item, $item->quantity);
                        break;
                }
            }

            $userId = auth()->id();
            if (!$userId) {
                throw new \Exception('Usuario no autenticado. No se puede procesar el movimiento.');
            }

            $this->update([
                'status' => 'POSTED',
                'posted_at' => now(),
                'posted_by' => $userId,
            ]);
        });
    }

    protected function adjustStock($locationId, $item, $quantity)
    {
        if (!$locationId) return;

        // Use lockForUpdate to prevent race conditions
        // First try to find existing stock with lock, if not exists, create new one
        $stock = Stock::where([
            'location_id' => $locationId,
            'vaccine_id' => $item->vaccine_id,
            'batch_id' => $item->batch_id,
        ])->lockForUpdate()->first();

        if (!$stock) {
            // Create new stock record if it doesn't exist
            $stock = new Stock([
                'location_id' => $locationId,
                'vaccine_id' => $item->vaccine_id,
                'batch_id' => $item->batch_id,
                'quantity' => 0,
            ]);
        }

        // Initialize quantity to 0 if it's null (safety check)
        $currentQuantity = $stock->quantity ?? 0;
        $newQuantity = $currentQuantity + $quantity;

        if ($newQuantity < 0) {
            // Load batch relationship if not already loaded
            if (!$item->relationLoaded('batch')) {
                $item->load('batch');
            }
            $batchNumber = $item->batch ? $item->batch->batch_number : 'N/A';
            throw new \Exception("Stock insuficiente para el lote {$batchNumber} en la ubicación seleccionada.");
        }

        $stock->quantity = $newQuantity;
        $stock->save();
    }
}
