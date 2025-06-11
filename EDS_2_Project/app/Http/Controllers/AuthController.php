<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this import

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials['email'] = strtolower($credentials['email']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return match(auth()->user()->role) { // Change from 'tipo' to 'role'
                'administrador' => redirect()->route('admin.dashboard'),
                'gestor' => redirect()->route('gestor.dashboard'),
                'funcionario' => redirect()->route('funcionario.dashboard'),
                default => redirect()->route('login.show')->withErrors(['role' => 'Função inválida!']),
            };
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas',
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