<?php

namespace App\Repositories;

use App\Models\Atleta;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AtletaRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Atleta();
    }


    public function listarTodos()
    {
        try {
            return Atleta::paginate(6);
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

    public function buscarAtletas(array $filtros)
    {
        $query = Atleta::query();

        // Se idade for passada, calculamos e filtramos na aplicação
        if (isset($filtros['idade_min']) && isset($filtros['idade_max'])) {
             $hoje = now();
            $dataMax = $hoje->copy()->subYears($filtros['idade_min'])->endOfDay(); // mais jovem
            $dataMin = $hoje->copy()->subYears($filtros['idade_max'])->startOfDay(); // mais velho

            $query->whereBetween('data_nascimento', [$dataMin, $dataMax]);
        }

        if (isset($filtros['posicao_jogo'])) {
            $query->where('posicao_jogo', 'LIKE', '%' . $filtros['posicao_jogo'] . '%');
        }

        if (isset($filtros['cidade'])) {
            $query->where('cidade', 'LIKE', '%' . $filtros['cidade'] . '%');
        }

        if (isset($filtros['entidade'])) {
            $query->where('entidade', 'LIKE', '%' . $filtros['entidade'] . '%');
        }

        return $query->get();
    }

    public function buscarPorCpf($cpf)
    {
        $cpfSomenteNumeros = preg_replace('/\D/', '', $cpf); // Remove todos os caracteres não numéricos

        return Atleta::whereRaw("REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = ?", [$cpfSomenteNumeros])
                    ->first();
    }

    public function listarPosicoesUnicas()
    {
        return $this->model->select('posicao_jogo')->distinct()->get();
    }

    public function listarCidadesUnicas()
    {
        return $this->model->select('cidade')->distinct()->get();
    }
    public function listarEntidadesUnicas()
    {
        return $this->model->select('entidade')->distinct()->get();
    }
}

