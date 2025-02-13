<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a login form.
     *
     * @return \Illuminate\View\View
     */
    public function login(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Authenticating and redirecting to the respective routes.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('app.index');
        }

        return redirect()->back()
            ->with('flashMessage', 'The email and password are incorrect.');
    }

    /**
     * Logout from the auth session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
