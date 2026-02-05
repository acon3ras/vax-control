<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;

class AccessLogs extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = AuditLog::where('user_id', auth()->id())
            ->where('event', 'LOGIN')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.access-logs', [
            'logs' => $logs
        ]);
    }
}
