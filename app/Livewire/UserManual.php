<?php

namespace App\Livewire;

use Livewire\Component;

class UserManual extends Component
{
    public $activeTab = 'home';

    protected $queryString = ['activeTab' => ['except' => 'home', 'as' => 'tema']];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.user-manual');
    }
}
