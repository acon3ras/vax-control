<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vaccine;
use Livewire\WithPagination;

class VaccineManager extends Component
{
    use WithPagination;
    
    // Changing listener to a method that updates the key
    protected $listeners = ['refreshVaccineManager' => 'refreshList'];
    
    public $refreshId = 0;
    
    public $search = '';
    public $name, $code, $manufacturer, $presentation, $dose_per_unit = 1, $initial_batch = '';
    public $min_stock = 0, $optimal_stock = 0;
    public $status = 'ACTIVE';
    public $showModal = false;
    public $selectedVaccineId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:vaccines,code',
        'manufacturer' => 'nullable|string|max:255',
        'presentation' => 'required|string|max:100',
        'dose_per_unit' => 'required|integer|min:1',
        'min_stock' => 'required|integer|min:0',
        'optimal_stock' => 'required|integer|gte:min_stock',
        'initial_batch' => 'nullable|string|max:50',
    ];

    public function render()
    {
        $vaccines = Vaccine::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->withSum(['stocks as active_stock' => function($query) {
                $query->where('location_id', 1);
            }], 'quantity')
            ->withSum(['stocks as quarantine_stock' => function($query) {
                $query->whereHas('location', function($q) {
                    $q->where('type', 'QUARANTINE');
                });
            }], 'quantity')
            ->latest()
            ->paginate(10);

        return view('livewire.vaccine-manager', [
            'vaccines' => $vaccines
        ]);
    }

    public function openModal($id = null)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
            $this->js("alert('Acción no autorizada. Solo Administradores y Encargados pueden editar vacunas.')");
            return;
        }
        $this->resetValidation();
        $this->selectedVaccineId = $id;

        if ($id) {
            $vaccine = Vaccine::find($id);
            if (!$vaccine) {
                session()->flash('error', 'La vacuna no existe.');
                return;
            }
            $this->name = $vaccine->name;
            $this->code = $vaccine->code;
            $this->manufacturer = $vaccine->manufacturer;
            $this->presentation = $vaccine->presentation;
            $this->dose_per_unit = $vaccine->dose_per_unit;
            $this->min_stock = $vaccine->min_stock;
            $this->optimal_stock = $vaccine->optimal_stock;
            $this->status = $vaccine->status;
        } else {
            $this->reset(['name', 'code', 'manufacturer', 'presentation', 'dose_per_unit', 'min_stock', 'optimal_stock', 'status']);
            $this->dose_per_unit = 1;
            $this->min_stock = 0;
            $this->optimal_stock = 0;
            $this->status = 'ACTIVE';
        }

        $this->showModal = true;
    }

    public function save()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
            $this->js("alert('Acción no autorizada. Solo Administradores y Encargados pueden editar vacunas.')");
            return;
        }

        $rules = $this->rules;
        if ($this->selectedVaccineId) {
            $rules['code'] = 'required|string|max:50|unique:vaccines,code,' . $this->selectedVaccineId;
        }

        $this->validate($rules);

        // Use proper create or update logic
        if ($this->selectedVaccineId) {
            $vaccine = Vaccine::find($this->selectedVaccineId);
            if (!$vaccine) {
                $this->addError('vaccine_id', 'La vacuna no existe.');
                return;
            }
            $vaccine->update([
                'name' => $this->name,
                'code' => $this->code,
                'manufacturer' => $this->manufacturer,
                'presentation' => $this->presentation,
                'dose_per_unit' => $this->dose_per_unit,
                'min_stock' => $this->min_stock,
                'optimal_stock' => $this->optimal_stock,
                'status' => $this->status,
            ]);
        } else {
            $vaccine = Vaccine::create([
                'name' => $this->name,
                'code' => $this->code,
                'manufacturer' => $this->manufacturer,
                'presentation' => $this->presentation,
                'dose_per_unit' => $this->dose_per_unit,
                'min_stock' => $this->min_stock,
                'optimal_stock' => $this->optimal_stock,
                'status' => $this->status,
            ]);
        }

        // Ensure a batch exists for the vaccine
        \App\Models\Batch::firstOrCreate(
            [
                'vaccine_id' => $vaccine->id,
                'batch_number' => $this->initial_batch ?: 'STOCK_UNICO',
            ],
            [
                'expiry_date' => now()->addYears(10),
                'manufacturer_batch' => 'N/A',
            ]
        );

        $this->showModal = false;
        session()->flash('message', $this->selectedVaccineId ? 'Vacuna actualizada con éxito.' : 'Vacuna creada con éxito.');
        $this->reset(['name', 'code', 'manufacturer', 'presentation', 'dose_per_unit', 'min_stock', 'optimal_stock', 'status', 'selectedVaccineId', 'initial_batch']);
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
            return;
        }
        $vaccine = Vaccine::find($id);
        if (!$vaccine) {
            session()->flash('error', 'La vacuna no existe.');
            return;
        }
        
        if ($vaccine->status === 'ACTIVE') {
            $vaccine->status = 'INACTIVE';
        } elseif ($vaccine->status === 'INACTIVE') {
            $vaccine->status = 'ACTIVE';
        }
        // If QUARANTINE, do nothing or handle differently? User asked for a button.
        // Let's assume this toggle behaves as on/off for active/inactive. Quarantine is special.
        $vaccine->save();
        
        session()->flash('message', 'Estado de la vacuna actualizado correctamente.');
    }

    public function toggleQuarantine($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'encargado'])) {
            $this->js("alert('Acción no autorizada. Solo Administradores y Encargados pueden poner vacunas en cuarentena.')");
            return;
        }

        $vaccine = Vaccine::find($id);
        if (!$vaccine) {
            session()->flash('error', 'La vacuna no existe.');
            return;
        }
        
        if ($vaccine->status === 'QUARANTINE') {
            $vaccine->status = 'ACTIVE'; // Restore to active
        }
        $vaccine->save();
        
        session()->flash('message', 'Estado de cuarentena actualizado correctamente.');
    }

    public function refreshList()
    {
        $this->refreshId++;
    }
}
