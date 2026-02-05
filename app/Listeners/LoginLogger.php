<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LoginLogger
{
    /**
     * Create the event listener.
     */
    public function __construct(protected Request $request)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        AuditLog::create([
            'user_id' => $event->user->id,
            'auditable_type' => 'App\Models\User',
            'auditable_id' => $event->user->id,
            'event' => 'login',
            'user_agent' => $this->request->userAgent(),
            'ip_address' => $this->request->ip(),
            'old_values' => null,
            'new_values' => null,
        ]);
    }
}
