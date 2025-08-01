<?php
namespace App\Repositorios;

use App\Utils\ConexaoSQLite;

class PivoRepositorio
{
    public function salvar(array $pivo): void
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("
            INSERT INTO pivos (id, description, flowRate, minApplicationDepth, userId)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $pivo['id'],
            $pivo['description'],
            $pivo['flowRate'],
            $pivo['minApplicationDepth'],
            $pivo['userId']
        ]);
    }

    public function listar_por_usuario(string $userId): array
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("SELECT * FROM pivos WHERE userId = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscar_por_id(string $id): ?array
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("SELECT * FROM pivos WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $p ?: null;
    }

    public function atualizar(string $id, array $dados): ?array
    {
        $pdo = ConexaoSQLite::obter();
        $campos = [];
        $params = [];
        if (isset($dados['description'])) {
            $campos[] = "description = ?";
            $params[] = $dados['description'];
        }
        if (isset($dados['flowRate'])) {
            $campos[] = "flowRate = ?";
            $params[] = $dados['flowRate'];
        }
        if (isset($dados['minApplicationDepth'])) {
            $campos[] = "minApplicationDepth = ?";
            $params[] = $dados['minApplicationDepth'];
        }
        if (empty($campos)) {
            return $this->buscar_por_id($id);
        }
        $params[] = $id;
        $sql = "UPDATE pivos SET " . implode(", ", $campos) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $this->buscar_por_id($id);
    }

    public function remover(string $id): bool
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("DELETE FROM pivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
