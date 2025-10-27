<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    //
    public function login()
    {
        if (Auth::check()) {
            # code...
            $user = Auth::user();
            if ($user->role === 'Admin') {
                return redirect()->intended('/customer');
            } elseif ($user->role === 'CS') {
                return redirect()->intended('/customer');
            } elseif ($user->role === 'NOC') {
                return redirect()->intended('/ticket');
            }
        }
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role user
            $user = Auth::user();

            if ($user->role === 'Admin') {
                return redirect()->intended('/customer');
            } elseif ($user->role === 'CS') {
                return redirect()->intended('/customer');
            } elseif ($user->role === 'NOC') {
                return redirect()->intended('/ticket');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah, fix.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
