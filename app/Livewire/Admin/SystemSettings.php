<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class SystemSettings extends Component
{
    public $allowedDomains;

    public function mount()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }
        $this->allowedDomains = \App\Models\SystemSetting::getValue('allowed_domains', '');
    }

    public function save()
    {
        $this->validate([
            'allowedDomains' => 'required|string',
        ]);

        \App\Models\SystemSetting::setValue('allowed_domains', $this->allowedDomains);

        session()->flash('message', 'Configuración guardada correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings')
            ->layout('components.layouts.app', ['title' => 'Configuración del Sistema']);
    }
}
