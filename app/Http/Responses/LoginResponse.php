<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        if ($user && ($user->role === 'buyer' || $user->role === 'artist')) {
            return redirect()->intended('/');
        }

        if ($user && ! $user->role) {
            return redirect()->intended(route('onboarding'));
        }

        return redirect()->intended(config('fortify.home'));
    }
}
