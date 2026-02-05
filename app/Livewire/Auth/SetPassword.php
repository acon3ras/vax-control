<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class SetPassword extends Component
{
    public $user;
    public $password = '';
    public $password_confirmation = '';

    public function mount(\App\Models\User $user)
    {
        if (!request()->hasValidSignature()) {
            abort(403, 'Enlace no válido o expirado.');
        }

        if ($user->isActive()) {
            return redirect()->route('login')->with('message', 'Esta cuenta ya está activa. Inicia sesión.');
        }

        $this->user = $user;
    }

    public function save()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $this->user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
            'status' => 'ACTIVE',
        ]);

        auth()->login($this->user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.set-password');
    }
}
