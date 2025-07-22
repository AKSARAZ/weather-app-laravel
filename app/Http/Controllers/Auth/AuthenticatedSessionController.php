<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider; // Pastikan ini ada di atas
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- LOGIKA BARU DIMULAI DI SINI ---
        if (Auth::user()->is_admin) {
            // Jika user adalah admin, arahkan ke dashboard admin
            return redirect()->intended(route('admin.dashboard')); 
        }

        // Jika user biasa, arahkan ke dashboard user
        return redirect()->intended(RouteServiceProvider::HOME);
        // --- LOGIKA BARU SELESAI DI SINI ---
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
