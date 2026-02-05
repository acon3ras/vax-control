<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->audit('created');
        });

        static::updated(function ($model) {
            $model->audit('updated');
        });

        static::deleted(function ($model) {
            $model->audit('deleted');
        });
    }

    protected function audit($event)
    {
        // Skip audit if no user is authenticated (e.g., seeders, console commands)
        if (!Auth::check()) {
            return;
        }

        $oldValues = $event === 'created' ? null : $this->getRawOriginal();
        $newValues = $event === 'deleted' ? null : $this->getAttributes();

        // For updates, only store changed values
        if ($event === 'updated') {
            $oldValues = array_intersect_key($oldValues, $this->getChanges());
            $newValues = $this->getChanges();
        }

        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'auditable_type' => get_class($this),
                'auditable_id' => $this->id,
                'event' => $event,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'user_agent' => Request::userAgent(),
                'ip_address' => Request::ip(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the main operation
            \Log::error('Error creating audit log: ' . $e->getMessage());
        }
    }
}
