<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user && ($user->role === 'buyer' || $user->role === 'artist')) {
            return redirect('/');
        }

        if ($user && ! $user->role) {
            return redirect()->route('onboarding');
        }

        return redirect(config('fortify.home'));
    }
}
