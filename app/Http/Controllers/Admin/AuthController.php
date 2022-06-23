<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'g-recaptcha-response'=>'recaptcha',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (auth()->attempt($credentials)) {
            if (auth()->user()->role != 1) {
                auth()->logout();
                return redirect()->back()->withErrors(['errors' => 'You are not authorized !']);
            }
            return redirect()->route('home');
        }
        return redirect()->back()->withErrors(['errors' => 'Invalid credentials']);
    }

    public function login_page(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.login');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        auth()->logout();
        return redirect()->route('auth.login');
    }
}
