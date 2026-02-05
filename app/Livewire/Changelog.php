<?php

namespace App\Livewire;

use Livewire\Component;

class Changelog extends Component
{
    public function render()
    {
        $path = base_path('CHANGELOG.md');
        $content = file_exists($path) ? file_get_contents($path) : '# Changelog no encontrado en: ' . $path;
        
        return view('livewire.changelog', [
            'content' => \Illuminate\Support\Str::markdown($content),
        ]);
    }
}
