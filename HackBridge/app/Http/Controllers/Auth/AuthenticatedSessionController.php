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
        return view('welcome');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Force a clean slate before attempting a fresh login. If a remember-me
        // cookie from a previous account is still sitting in the browser, this
        // guarantees it can never silently override whoever is being logged in
        // right now — logout() clears both the session and queues the remember
        // cookie for deletion, and Auth::attempt() below then authenticates
        // strictly based on the submitted credentials.
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $user = Auth::user();

        return redirect()->to($user->is_admin ? route('admin.dashboard') : route('dashboard'));
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