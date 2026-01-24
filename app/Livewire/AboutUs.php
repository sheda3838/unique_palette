<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class AboutUs extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        // This component replaces the traditional Blade-based about us page to enable SPA-like navigation
        return view('livewire.about-us');
    }
}
