<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovementItem extends Model
{
    protected $fillable = [
        'movement_id',
        'vaccine_id',
        'batch_id',
        'quantity',
        'patient_id',
        'meta_data',
    ];

    protected $casts = [
        'meta_data' => 'json',
    ];

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
