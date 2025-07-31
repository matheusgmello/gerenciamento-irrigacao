<?php
namespace App\Servicos;

use App\Repositorios\IrrigacaoRepositorio;
use App\Repositorios\PivoRepositorio;
use App\Utils\Helpers;

class IrrigacaoServico
{
    private $repo;
    private $repoPivo;

    public function __construct()
    {
        $this->repo = new IrrigacaoRepositorio();
        $this->repoPivo = new PivoRepositorio();
    }

    public function criar(array $dados, string $userId): array
    {
        if (!isset($dados['pivotId']) || !isset($dados['applicationAmount']) || !isset($dados['irrigationDate'])) {
            throw new \Exception("Campos obrigatórios faltando", 400);
        }

        $pivo = $this->repoPivo->buscar_por_id($dados['pivotId']);
        if (!$pivo || $pivo['userId'] !== $userId) {
            throw new \Exception("Pivô inválido ou não pertence ao usuário", 400);
        }

        $irrigacao = [
            'id' => Helpers::gerar_uuid(),
            'pivotId' => $dados['pivotId'],
            'applicationAmount' => floatval($dados['applicationAmount']),
            'irrigationDate' => $dados['irrigationDate'],
            'userId' => $userId
        ];
        $this->repo->salvar($irrigacao);
        return $irrigacao;
    }

    public function listar(string $userId): array
    {
        return $this->repo->listar_por_usuario($userId);
    }

    public function buscar(string $id, string $userId): array
    {
        $registro = $this->repo->buscar_por_id($id);
        if (!$registro || $registro['userId'] !== $userId) {
            throw new \Exception("Registro não encontrado", 404);
        }
        return $registro;
    }

    public function remover(string $id, string $userId): void
    {
        $registro = $this->repo->buscar_por_id($id);
        if (!$registro || $registro['userId'] !== $userId) {
            throw new \Exception("Registro não encontrado", 404);
        }
        $this->repo->remover($id);
    }
}
