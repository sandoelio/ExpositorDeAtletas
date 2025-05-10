<?php

namespace App\Services;

use App\Repositories\AtletaRepository;

use Illuminate\Http\Request;

class AtletaService
{
    protected $atletaRepository;

    public function __construct(AtletaRepository $atletaRepository)
    {
        $this->atletaRepository = $atletaRepository;
    }

    public function listarTodos()
    {
        try {
            return $this->atletaRepository->listarTodos();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function buscarPorId($id)
    {
        try {
            return $this->atletaRepository->buscarPorId($id);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function criarAtleta(array $dados)
    {
        try {
            return $this->atletaRepository->criar($dados);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function atualizarAtleta($id, array $dados)
    {
        try {
            return $this->atletaRepository->atualizar($id, $dados);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function excluirAtleta($id)
    {
        try {
            return $this->atletaRepository->excluir($id);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function buscarAtletas(Request $request)
    {
        try {
            $filtros = $request->only(['idade_min', 'idade_max', 'posicao_jogo', 'cidade', 'entidade']);
            return $this->atletaRepository->buscarAtletas($filtros);
        } catch (\Exception $ex) {
            return response()->json([
                'erro' => 'Erro ao buscar atletas.',
                'detalhes' => $ex->getMessage()
            ], 500);
        }
    }

    public function buscarPorCpf(Request $request)
    {
        try {
            return $this->atletaRepository->buscarPorCpf($request->cpf);
        } catch (\Exception $ex) {
            return response()->json([
                'erro' => 'Erro ao buscar atleta pelo CPF.',
                'detalhes' => $ex->getMessage()
            ], 500);
        }
    }

    public function listarPosicoesUnicas()
    {
        return $this->atletaRepository->listarPosicoesUnicas();
    }

    public function listarCidadesUnicas()
    {
        return $this->atletaRepository->listarCidadesUnicas();
    }
    
    public function listarEntidadesUnicas()
    {
        return $this->atletaRepository->listarEntidadesUnicas();
    }
}

