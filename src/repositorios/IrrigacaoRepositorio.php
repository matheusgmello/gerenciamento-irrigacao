<?php
namespace App\Repositorios;

class IrrigacaoRepositorio
{
    private $caminho = __DIR__ . '/../../dados/irrigacoes.json';

    private function ler_todos(): array
    {
        $conteudo = file_get_contents($this->caminho);
        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : [];
    }

    private function salvar_todos(array $registros): void
    {
        file_put_contents($this->caminho, json_encode($registros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function salvar(array $irrigacao): void
    {
        $todos = $this->ler_todos();
        $todos[] = $irrigacao;
        $this->salvar_todos($todos);
    }

    public function listar_por_usuario(string $userId): array
    {
        $todos = $this->ler_todos();
        return array_values(array_filter($todos, fn($r) => $r['userId'] === $userId));
    }

    public function buscar_por_id(string $id): ?array
    {
        $todos = $this->ler_todos();
        foreach ($todos as $r) {
            if ($r['id'] === $id) return $r;
        }
        return null;
    }

    public function remover(string $id): bool
    {
        $todos = $this->ler_todos();
        $antes = count($todos);
        $todos = array_filter($todos, fn($r) => $r['id'] !== $id);
        $depois = count($todos);
        if ($antes === $depois) return false;
        $this->salvar_todos(array_values($todos));
        return true;
    }
}
