<?php

namespace App\Http\Controllers;

use App\Models\Olheiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminOlheiroController extends Controller
{
    public function index(Request $request)
    {
        $query = Olheiro::query()->orderBy('nome');

        if ($request->filled('nome')) {
            $nome = trim((string) $request->nome);
            $query->where('nome', 'like', '%' . $nome . '%');
        }

        $olheiros = $query->paginate(4)->withQueryString();

        return view('admin.olheiros.index', [
            'olheiros' => $olheiros,
            'filtroNome' => (string) ($request->nome ?? ''),
        ]);
    }

    public function edit($id)
    {
        $olheiro = Olheiro::findOrFail($id);

        return view('admin.olheiros.edit', [
            'olheiro' => $olheiro,
        ]);
    }

    public function update(Request $request, $id)
    {
        $olheiro = Olheiro::findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:olheiros,email,' . $olheiro->id,
            'telefone' => 'required|string|max:25',
            'entidade' => 'required|string|max:255',
            'cidade' => 'required|string|max:120',
            'login' => 'required|string|min:4|max:60|unique:olheiros,login,' . $olheiro->id,
            'password' => 'nullable|string|min:6|max:255|confirmed',
        ]);

        $payload = [
            'nome' => trim($data['nome']),
            'email' => strtolower(trim($data['email'])),
            'telefone' => trim($data['telefone']),
            'entidade' => trim($data['entidade']),
            'cidade' => trim($data['cidade']),
            'login' => trim($data['login']),
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $olheiro->update($payload);

        return redirect()
            ->route('admin.olheiros.index')
            ->with('success', 'Tecnico/olheiro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $olheiro = Olheiro::findOrFail($id);
        $olheiro->delete();

        return redirect()
            ->route('admin.olheiros.index')
            ->with('success', 'Tecnico/olheiro excluido com sucesso.');
    }
}
