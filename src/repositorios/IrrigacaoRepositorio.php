<?php
namespace App\Repositorios;

use App\Utils\ConexaoSQLite;

class IrrigacaoRepositorio
{
    public function salvar(array $irrigacao): void
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("
            INSERT INTO irrigacoes (id, pivotId, applicationAmount, irrigationDate, userId)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $irrigacao['id'],
            $irrigacao['pivotId'],
            $irrigacao['applicationAmount'],
            $irrigacao['irrigationDate'],
            $irrigacao['userId']
        ]);
    }

    public function listar_por_usuario(string $userId): array
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("SELECT * FROM irrigacoes WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscar_por_id(string $id): ?array
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("SELECT * FROM irrigacoes WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function remover(string $id): bool
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("DELETE FROM irrigacoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
