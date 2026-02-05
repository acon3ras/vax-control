<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class LocationManager extends Component
{
    public $locations;
    public $name;
    public $description;
    public $is_active = true;
    public $type = 'DEPENDENCY'; // Default type for external destinations
    public $selectedId;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string',
        'description' => 'nullable|string',
    ];

    public function render()
    {
        $this->locations = Location::where('type', '!=', 'VACCINATION_POINT') // Exclude internal storage
            ->orderBy('name')
            ->get();
            
        return view('livewire.location-manager');
    }

    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
             $this->js("alert('Acción no autorizada.')");
             return;
        }
        $this->reset(['name', 'description', 'selectedId']);
        $this->type = 'DEPENDENCY';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
             $this->js("alert('Acción no autorizada.')");
             return;
        }
        $location = Location::findOrFail($id);
        $this->selectedId = $id;
        $this->name = $location->name;
        $this->type = $location->type;
        $this->description = $location->description;
        $this->is_active = $location->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
             $this->js("alert('Acción no autorizada.')");
             return;
        }

        $this->validate();

        if ($this->selectedId) {
            $location = Location::find($this->selectedId);
            if (!$location) {
                session()->flash('error', 'La ubicación no existe.');
                return;
            }
            $location->update([
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
        } else {
            Location::create([
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
        }

        $this->showModal = false;
        session()->flash('message', $this->selectedId ? 'Dependencia actualizada.' : 'Dependencia creada.');
        $this->reset(['name', 'description', 'selectedId', 'type', 'is_active']);
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
             return;
        }
        $location = Location::find($id);
        if (!$location) {
            session()->flash('error', 'La ubicación no existe.');
            return;
        }
        $location->update(['is_active' => !$location->is_active]);
        session()->flash('message', 'Estado de la ubicación actualizado correctamente.');
    }
}
