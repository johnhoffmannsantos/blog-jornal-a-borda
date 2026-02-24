<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('As credenciais fornecidas estão incorretas.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function quickLogin($role)
    {
        if (!app()->environment('local')) {
            abort(403, 'Acesso rápido disponível apenas em desenvolvimento');
        }

        $users = \App\Models\User::where('role', $role)->get();
        
        if ($users->isEmpty()) {
            return redirect()->route('login')->with('error', 'Nenhum usuário encontrado com este perfil');
        }

        Auth::login($users->first());
        return redirect()->route('admin.dashboard');
    }
}
