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

        $user = Auth::user();

        // Cek apakah akun aktif
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda tidak aktif.',
            ]);
        }

        // Redirect berdasarkan usertype
        return match ($user->usertype) {
            'marketing' => redirect(route('marketing.dashboard')),
            'supervisor' => redirect(route('supervisor.dashboard')),
            'credit' => redirect(route('creditAnalyst.dashboard')),
            'head' => redirect(route('headMarketing.dashboard')),
            'admin' => redirect(route('admin.dashboard')),
            'root' => redirect(route('superAdmin.dashboard')),
            'surveyor' => redirect(route('surveyor.dashboard')),
            default => redirect()->route('login')->withErrors([
                'email' => 'Role akun Anda tidak valid.',
            ]),
        };
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
