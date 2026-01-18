<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $currentLocale;

    public function mount()
    {
        $this->currentLocale = session('locale', config('app.locale'));
    }

    public function switchLanguage($locale)
    {
        if (in_array($locale, ['en', 'si', 'ta'])) {
            session(['locale' => $locale]);
            $this->currentLocale = $locale;
            return redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
