<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['username' => 'Nieprawidłowy login lub hasło.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
            return redirect()->intended(route('admin.users.index'));
        }

        return redirect()->intended('/');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); //kasuje całą sesję i daje nowy identyfikator sesji, z tokenem csrf
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
