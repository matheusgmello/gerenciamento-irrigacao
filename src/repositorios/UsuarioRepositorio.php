<?php
namespace App\Repositorios;

use App\Utils\ConexaoSQLite;

class UsuarioRepositorio
{
    private function normalizar_username(string $username): string
    {
        return mb_strtolower(trim($username));
    }

    public function criar(array $usuario): void
    {
        $pdo = ConexaoSQLite::obter();
        $username_norm = $this->normalizar_username($usuario['username']);

        // verificar duplicado case-insensitive
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE lower(username) = lower(?)");
        $stmt->execute([$username_norm]);
        if ($stmt->fetch()) {
            throw new \Exception("Username já existe", 400);
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (id, username, senha_hash) VALUES (?, ?, ?)");
        $stmt->execute([$usuario['id'], $usuario['username'], $usuario['senha_hash']]);
    }

    public function buscar_por_username(string $username): ?array
    {
        $pdo = ConexaoSQLite::obter();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE lower(username) = lower(?)");
        $stmt->execute([trim($username)]);
        $u = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $u ?: null;
    }
}
