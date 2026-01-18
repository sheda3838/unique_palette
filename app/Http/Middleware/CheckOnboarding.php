<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        // Exclude logout, verification, and other critical auth routes
        if (
            $request->routeIs('logout') ||
            $request->routeIs('profile.show') ||
            $request->routeIs('verification.*') ||
            $request->is('email/*') ||
            $request->routeIs('livewire.*')
        ) {
            return $next($request);
        }

        $isOnboarding = $request->routeIs('onboarding');

        if (! $user->role) {
            return $isOnboarding ? $next($request) : redirect()->route('onboarding');
        }

        if ($user->role === 'buyer') {
            if (! $user->address) {
                return $isOnboarding ? $next($request) : redirect()->route('onboarding');
            }
        } elseif ($user->role === 'artist') {
            // Artist needs address AND bank details
            if (! $user->address || ! $user->bankDetail) {
                // If they are on onboarding page, let them proceed (Livewire handles steps)
                // If they are NOT on onboarding page, force them there
                return $isOnboarding ? $next($request) : redirect()->route('onboarding');
            }
        }

        // If completed but trying to access onboarding, redirect to dashboard
        if ($isOnboarding) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
