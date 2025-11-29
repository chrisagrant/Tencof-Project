<?php

namespace App\Http\Controllers;

use App\Enum\UserRoleEnum;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        $roles = UserRoleEnum::toArray();
        return view('auth.register', [
            'roles' => $roles
        ]);
    }

    public function register(AuthRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->intended('/login')->with('success', 'Registrasi berhasil! Silahkan Login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(AuthLoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login berhasil.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showSplash()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('loading');
    }
}
