<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Vaccine extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'code',
        'manufacturer',
        'presentation',
        'dose_per_unit',
        'status',
        'min_stock',
        'optimal_stock',
    ];

    protected $casts = [
        'dose_per_unit' => 'integer',
        'min_stock' => 'integer', // Ensuring integer casting
        'optimal_stock' => 'integer',
    ];

    public function getIsActiveAttribute()
    {
        return $this->status === 'ACTIVE';
    }

    public function isActive()
    {
        return $this->status === 'ACTIVE';
    }

    public function isQuarantined()
    {
        return $this->status === 'QUARANTINE';
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function movementItems()
    {
        return $this->hasMany(MovementItem::class);
    }
}
