<?php

namespace App\Http\Controllers;

use App\Models\Olheiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OlheiroAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('olheiro')->check()) {
            return redirect()->route('olheiro.atletas.index');
        }

        return view('olheiro.login');
    }

    public function showRegisterForm()
    {
        if (Auth::guard('olheiro')->check()) {
            return redirect()->route('olheiro.atletas.index');
        }

        return view('olheiro.cadastro');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:olheiros,email',
            'telefone' => 'required|string|max:25',
            'entidade' => 'required|string|max:255',
            'cidade' => 'required|string|max:120',
            'login' => 'required|string|min:4|max:60|unique:olheiros,login',
            'password' => 'required|string|min:6|max:255|confirmed',
        ]);

        Olheiro::create([
            'nome' => trim($data['nome']),
            'email' => strtolower(trim($data['email'])),
            'telefone' => trim($data['telefone']),
            'entidade' => trim($data['entidade']),
            'cidade' => trim($data['cidade']),
            'login' => trim($data['login']),
            'password' => Hash::make($data['password']),
            'aprovado' => false,
            'aprovado_em' => null,
        ]);

        return redirect()
            ->route('olheiro.login.form')
            ->with('olheiro_success', 'Cadastro enviado com sucesso. Aguarde a validacao do administrador para fazer login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string|max:60',
            'password' => 'required|string|max:255',
        ]);

        $login = trim($credentials['login']);
        $olheiro = Olheiro::where('login', $login)->first();

        if ($olheiro && Hash::check($credentials['password'], $olheiro->password) && !$olheiro->aprovado) {
            return back()
                ->withErrors(['login' => 'Cadastro pendente de validacao do administrador.'])
                ->withInput($request->only('login'));
        }

        if (Auth::guard('olheiro')->attempt([
            'login' => $login,
            'password' => $credentials['password'],
            'aprovado' => true,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('olheiro.atletas.index');
        }

        return back()
            ->withErrors(['login' => 'Login ou senha invalidos.'])
            ->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::guard('olheiro')->logout();
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
