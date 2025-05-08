<?php

namespace App\Services;

use App\Repositories\AtletaRepository;

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
}

