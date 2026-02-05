<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movement;

class MovementHistory extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $movements = Movement::with(['user', 'items.vaccine', 'sourceLocation', 'destinationLocation'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('items.vaccine', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('type', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(20);

        return view('livewire.movement-history', [
            'movements' => $movements
        ]);
    }
}
