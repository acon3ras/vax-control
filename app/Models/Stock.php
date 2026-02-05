<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'location_id',
        'vaccine_id',
        'batch_id',
        'quantity',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
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
