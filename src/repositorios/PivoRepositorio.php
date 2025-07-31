<?php
namespace App\Repositorios;

class PivoRepositorio
{
    private $caminho = __DIR__ . '/../../dados/pivos.json';

    private function ler_todos(): array
    {
        $conteudo = file_get_contents($this->caminho);
        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : [];
    }

    private function salvar_todos(array $pivos): void
    {
        file_put_contents($this->caminho, json_encode($pivos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function salvar(array $pivo): void
    {
        $todos = $this->ler_todos();
        $todos[] = $pivo;
        $this->salvar_todos($todos);
    }

    public function listar_por_usuario(string $userId): array
    {
        $todos = $this->ler_todos();
        return array_values(array_filter($todos, fn($p) => $p['userId'] === $userId));
    }

    public function buscar_por_id(string $id): ?array
    {
        $todos = $this->ler_todos();
        foreach ($todos as $p) {
            if ($p['id'] === $id) return $p;
        }
        return null;
    }

    public function atualizar(string $id, array $dados): ?array
    {
        $todos = $this->ler_todos();
        foreach ($todos as &$p) {
            if ($p['id'] === $id) {
                $p = array_merge($p, $dados);
                $this->salvar_todos($todos);
                return $p;
            }
        }
        return null;
    }

    public function remover(string $id): bool
    {
        $todos = $this->ler_todos();
        $antes = count($todos);
        $todos = array_filter($todos, fn($p) => $p['id'] !== $id);
        $depois = count($todos);
        if ($antes === $depois) return false;
        $this->salvar_todos(array_values($todos));
        return true;
    }
}
