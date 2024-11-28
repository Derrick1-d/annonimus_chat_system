<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Events\LecturerStatusChanged;

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

        // Set lecturer online status to true and broadcast the event
        $lecturer = Auth::user();
        $lecturer->is_online = true;
        $lecturer->save();

        broadcast(new LecturerStatusChanged($lecturer->id, true))->toOthers();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Set lecturer online status to false and broadcast the event
        $lecturer = Auth::user();
        if ($lecturer) {
            $lecturer->is_online = false;
            $lecturer->save();

            broadcast(new LecturerStatusChanged($lecturer->id, false))->toOthers();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

