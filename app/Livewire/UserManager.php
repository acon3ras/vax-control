<?php

namespace App\Livewire;

use Livewire\Component;

class UserManager extends Component
{
    use \Livewire\WithPagination;

    public $search = '';

    public function toggleRole($userId, $roleName)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return;

        $role = \App\Models\Role::where('name', $roleName)->first();
        if (!$role) return;

        if ($user->hasRole($roleName)) {
            $user->roles()->detach($role);
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($user),
                'auditable_id' => $user->id,
                'event' => 'ROLE_REMOVED',
                'old_values' => ['role' => $roleName],
                'new_values' => [],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } else {
            $user->roles()->attach($role);
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($user),
                'auditable_id' => $user->id,
                'event' => 'ROLE_ASSIGNED',
                'old_values' => [],
                'new_values' => ['role' => $roleName],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }
    }

    public function toggleStatus($userId)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasAnyRole(['admin', 'encargado'])) {
            return;
        }

        $user = \App\Models\User::find($userId);
        if (!$user) return;

        // Prevent self-lockout
        if ($user->id === $currentUser->id) {
            $this->js("alert('No puedes desactivar tu propia cuenta.')");
            return;
        }

        $user->status = ($user->status === 'ACTIVE') ? 'BLOCKED' : 'ACTIVE';
        $user->save();

        \App\Models\AuditLog::create([
            'user_id' => $currentUser->id,
            'auditable_type' => get_class($user),
            'auditable_id' => $user->id,
            'event' => 'STATUS_CHANGE',
            'old_values' => ['status' => $user->getOriginal('status')], // Note: getOriginal might be same if saved, but we know logic
            'new_values' => ['status' => $user->status],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        $statusMsg = $user->status === 'ACTIVE' ? 'activado' : 'bloqueado';
        session()->flash('message', "Usuario {$user->name} ha sido {$statusMsg}.");
    }

    public $editingUser = null;
    public $name, $email, $rut, $position_title, $unit_service, $role;
    public $isEditing = false;

    public function edit($id)
    {
        $this->editingUser = \App\Models\User::find($id);
        if ($this->editingUser) {
            $this->name = $this->editingUser->name;
            $this->email = $this->editingUser->email;
            $this->rut = $this->editingUser->rut;
            $this->position_title = $this->editingUser->position_title;
            $this->unit_service = $this->editingUser->unit_service;
            
            // Get current role name
            $currentRole = $this->editingUser->roles->first();
            $this->role = $currentRole ? $currentRole->name : '';
            
            $this->isEditing = true;
        }
    }

    public function cancel()
    {
        $this->reset(['editingUser', 'name', 'email', 'rut', 'position_title', 'unit_service', 'role', 'isEditing']);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUser->id,
            'rut' => 'required|string|max:20|unique:users,rut,' . $this->editingUser->id,
            'position_title' => 'required|string|max:255',
            'unit_service' => 'required|string|max:255',
            'role' => 'nullable|exists:roles,name',
        ]);

        // Security Check: Authorized Role Assignment
        $currentUser = auth()->user();
        $allowedRoles = [];

        if ($currentUser->hasRole('admin')) {
             $allowedRoles = ['admin', 'supervisor', 'encargado', 'vacunador'];
        } elseif ($currentUser->hasRole('encargado')) {
             $allowedRoles = ['encargado', 'supervisor', 'vacunador'];
        }

        if ($this->role && !in_array($this->role, $allowedRoles)) {
            $this->addError('role', 'No tienes permisos para asignar este rol.');
            return;
        }

        $this->editingUser->update([
            'name' => $this->name,
            'email' => $this->email,
            'rut' => $this->rut,
            'position_title' => $this->position_title,
            'unit_service' => $this->unit_service,
        ]);

        // Sync Role
        if ($this->role) {
            $roleModel = \App\Models\Role::where('name', $this->role)->first();
            if ($roleModel) {
                // Double check if Encargado is assigning Admin (Redundant with above check, but safe)
                if (!$currentUser->hasRole('admin') && in_array($roleModel->name, ['admin', 'supervisor'])) {
                     $this->addError('role', 'Acción no autorizada.');
                     return;
                }

                $this->editingUser->roles()->sync([$roleModel->id]);
                // If the user was PENDING and got a role -> Set as ACTIVE
                if ($this->editingUser->status === 'PENDING') {
                    $this->editingUser->update(['status' => 'ACTIVE']);
                }

                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'auditable_type' => get_class($this->editingUser),
                    'auditable_id' => $this->editingUser->id,
                    'event' => 'ROLE_UPDATED',
                    'old_values' => ['role' => $currentRole->name ?? null],
                    'new_values' => ['role' => $roleModel->name],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
        } else {
            // If no role selected, detach all? Or keep? 
            // Usually we want at least one role. But user might want to remove roles.
            $this->editingUser->roles()->detach();
            
             \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($this->editingUser),
                'auditable_id' => $this->editingUser->id,
                'event' => 'ROLES_DETACHED',
                'old_values' => ['role' => $currentRole->name ?? null],
                'new_values' => [],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }

        $this->cancel();
        session()->flash('message', 'Usuario actualizado correctamente.');
    }

    public function render()
    {
        $users = \App\Models\User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('rut', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        // Filter Roles based on Auth User
        $currentUser = auth()->user();
        if ($currentUser->hasRole('admin')) {
            $roles = \App\Models\Role::all(); // Admin sees all
        } elseif ($currentUser->hasRole('encargado')) {
            $roles = \App\Models\Role::whereIn('name', ['encargado', 'supervisor', 'vacunador'])->get();
        } else {
            $roles = collect(); // Others see nothing
        }

        return view('livewire.user-manager', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}
