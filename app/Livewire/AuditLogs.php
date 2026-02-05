<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $eventFilter = '';
    public $userFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($this->search) {
            $query->where(function($q) {
                // Search in JSON columns is tricky depending on DB buffer, 
                // so we focus on User Name or Event type or Event ID
                $q->whereHas('user', function($u) {
                    $u->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('event', 'like', '%' . $this->search . '%')
                ->orWhere('auditable_type', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->eventFilter) {
            $query->where('event', $this->eventFilter);
        }

        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        $logs = $query->paginate(20);

        return view('livewire.audit-logs', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(), // For filter dropdown
            'events' => AuditLog::distinct('event')->pluck('event') // dynamic event list
        ]);
    }

    public function getEventBadgeProperty($event) {
        return match($event) {
            'LOGIN', 'LOGOUT' => 'bg-slate-100 text-slate-800',
            'ROLE_ASSIGNED', 'ROLE_UPDATED' => 'bg-purple-100 text-purple-800',
            'ROLE_REMOVED', 'ROLES_DETACHED' => 'bg-orange-100 text-orange-800',
            'STATUS_CHANGE' => 'bg-rose-100 text-rose-800',
            'create', 'insert' => 'bg-emerald-100 text-emerald-800',
            'update' => 'bg-blue-100 text-blue-800',
            'delete' => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-600'
        };
    }

    public function translateEvent($event, $log = null)
    {
        // Special case: If it's an update where status goes from DRAFT to POSTED, it's just a confirmation.
        if ($event === 'update' && $log && ($log->new_values['status'] ?? null) === 'POSTED') {
            return '✅ Movimiento Confirmado';
        }

        return match($event) {
            'LOGIN' => '🔐 Inicio de Sesión',
            'LOGOUT' => '🚪 Cierre de Sesión',
            'ROLE_ASSIGNED' => '👤 Rol Asignado',
            'ROLE_UPDATED' => '🔄 Rol Actualizado',
            'ROLE_REMOVED' => '🚫 Rol Eliminado',
            'ROLES_DETACHED' => '🧹 Roles Limpiados',
            'STATUS_CHANGE' => '⚖️ Cambio de Estado',
            'create', 'insert' => '📝 Registro Creado',
            'update' => '✏️ Registro Editado',
            'delete' => '🗑️ Registro Eliminado',
            default => $event
        };
    }

    public function translateValue($key, $value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        $translations = [
            'status' => [
                'DRAFT' => 'En Proceso',
                'POSTED' => 'Finalizado/Confirmado',
                'ACTIVE' => 'Activo',
                'BLOCKED' => 'Bloqueado',
                'PENDING' => 'Pendiente'
            ],
            'role' => [
                'admin' => 'Administrador',
                'encargado' => 'Encargado',
                'vacunador' => 'Vacunador',
                'supervisor' => 'Supervisor'
            ],
            'type' => [
                'RECEIPT' => 'Ingreso de Stock',
                'ADMINISTRATION' => 'Vacuna Aplicada',
                'WASTAGE' => 'Merma/Pérdida',
                'DISPATCH' => 'Despacho',
                'TRANSFER' => 'Traslado'
            ],
            'reason' => [
                'Expiry' => 'Vencimiento',
                'Loss' => 'Pérdida',
                'Breakage' => 'Rotura/Daño',
                'Theft' => 'Hurto/Robo',
                'Cold Chain Failure' => 'Falla Cadena Frío'
            ]
        ];

        return $translations[$key][$value] ?? $value;
    }
}
