<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Batch extends Model
{
    use Auditable;

    protected $fillable = [
        'vaccine_id',
        'batch_number',
        'expiry_date',
        'manufacturer_batch',
    ];

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
