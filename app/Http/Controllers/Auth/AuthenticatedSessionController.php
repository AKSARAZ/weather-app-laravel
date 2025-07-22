<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // =========================================================
        // MULAI LOGIKA REDIRECT MULTI-ROLE (TANPA RouteServiceProvider)
        // =========================================================
        
        $user = Auth::user();

        if ($user->is_admin) {
            // Jika user adalah admin, arahkan ke dashboard admin
            return redirect()->intended(route('admin.dashboard'));
        }
        
        // Jika user biasa, arahkan ke dashboard user
        return redirect()->intended(route('dashboard'));

        // ===================================
        // SELESAI LOGIKA REDIRECT MULTI-ROLE
        // ===================================
    }

    /**
     * Destroy an incoming authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}