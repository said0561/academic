<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
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
    public function store(Request $request): RedirectResponse
    {
        // Validate phone and password
        $credentials = $request->validate([
            'phone' => [
                'required',
                'regex:/^255[0-9]{9}$/', // must start with 255 + 9 digits
            ],
            'password' => ['required', 'string'],
        ]);

        // Attempt login using phone + password
        if (! Auth::attempt(
            ['phone' => $credentials['phone'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'phone' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('phone');
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
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
