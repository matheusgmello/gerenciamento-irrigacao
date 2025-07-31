<?php
namespace App\Servicos;

use App\Repositorios\PivoRepositorio;
use App\Utils\Helpers;

class PivoServico
{
    private $repo;

    public function __construct()
    {
        $this->repo = new PivoRepositorio();
    }

    public function criar(array $dados, string $userId): array
    {
        if (empty($dados['description']) || !isset($dados['flowRate']) || !isset($dados['minApplicationDepth'])) {
            throw new \Exception("Campos obrigatórios faltando", 400);
        }

        $pivo = [
            'id' => Helpers::gerar_uuid(),
            'description' => $dados['description'],
            'flowRate' => floatval($dados['flowRate']),
            'minApplicationDepth' => floatval($dados['minApplicationDepth']),
            'userId' => $userId
        ];
        $this->repo->salvar($pivo);
        return $pivo;
    }

    public function listar(string $userId): array
    {
        return $this->repo->listar_por_usuario($userId);
    }

    public function buscar(string $id, string $userId): array
    {
        $pivo = $this->repo->buscar_por_id($id);
        if (!$pivo || $pivo['userId'] !== $userId) {
            throw new \Exception("Pivô não encontrado", 404);
        }
        return $pivo;
    }

    public function atualizar(string $id, array $dados, string $userId): array
    {
        $pivo = $this->repo->buscar_por_id($id);
        if (!$pivo || $pivo['userId'] !== $userId) {
            throw new \Exception("Pivô não encontrado", 404);
        }

        $atualizacoes = [];
        if (isset($dados['description'])) $atualizacoes['description'] = $dados['description'];
        if (isset($dados['flowRate'])) $atualizacoes['flowRate'] = floatval($dados['flowRate']);
        if (isset($dados['minApplicationDepth'])) $atualizacoes['minApplicationDepth'] = floatval($dados['minApplicationDepth']);

        $pivo_atualizado = $this->repo->atualizar($id, $atualizacoes);
        return $pivo_atualizado;
    }

    public function remover(string $id, string $userId): void
    {
        $pivo = $this->repo->buscar_por_id($id);
        if (!$pivo || $pivo['userId'] !== $userId) {
            throw new \Exception("Pivô não encontrado", 404);
        }
        $this->repo->remover($id);
    }
}
