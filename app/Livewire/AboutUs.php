<?php

namespace App\Livewire;

use Livewire\Component;

class AboutUs extends Component
{
    public function render()
    {
        // This component replaces the traditional Blade-based about us page to enable SPA-like navigation
        return view('livewire.about-us')->layout('layouts.public');
    }
}
