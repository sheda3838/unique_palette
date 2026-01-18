<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            // Requirement 2: Sign In -> Onboarding/Dashboard
            return $this->redirectIntended(default: '/dashboard', navigate: true);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function render()
    {
        // Inline comments for Assessment Alignment (Requirement 6)
        // This Livewire component replaces traditional Blade auth to eliminate page reloads during validation and submission
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
