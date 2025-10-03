<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    function showLoginForm(): View
    {
        return view('logins.form');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        $data = $request->getParsedBody();
        $credentials = [
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ];
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes() && Auth::attempt($credentials)) {
            session()->regenerate();

            return redirect()->intended(route('products.list'));
        }

        $validator->errors()->add('credentials', 'The provided credentials do not match our records.');
        return redirect()
            ->back()
            ->withErrors($validator);
    }

    function logout(): RedirectResponse
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
