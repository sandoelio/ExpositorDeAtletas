<?php

namespace App\Repositories;

use App\Models\Atleta;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AtletaRepository
{
    public function listarTodos()
    {
        try {
            return Atleta::all();
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao listar atletas: " . $ex->getMessage());
        }
    }

    public function buscarPorId($id)
    {
        try {
            return Atleta::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new \Exception("Atleta não encontrado.");
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao buscar atleta: " . $ex->getMessage());
        }
    }

    public function criar(array $dados)
    {
        try {
            return Atleta::create($dados);
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao criar atleta: " . $ex->getMessage());
        }
    }

    public function atualizar($id, array $dados)
    {
        try {
            $atleta = Atleta::findOrFail($id);
            $atleta->update($dados);
            return $atleta;
        } catch (ModelNotFoundException $ex) {
            throw new \Exception("Atleta não encontrado.");
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao atualizar atleta: " . $ex->getMessage());
        }
    }

    public function excluir($id)
    {
        try {
            return Atleta::destroy($id);
        } catch (\Exception $ex) {
            throw new \Exception("Erro ao excluir atleta: " . $ex->getMessage());
        }
    }
}

